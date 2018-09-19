import json
import time
from passlib.hash import pbkdf2_sha256

import cherrypy

from microservices.customer_account_base import CustomerAccountManagerBase
from smartgym_packages.utilities.controls import check_mandatory_parameters

# TODO: more than "found"-"not-found", I would use (in general) "OK"-"ERR" for the status field
# TODO: move these constants inside consts.py (@Matteo: ignore this TODO)
kSTATUS_OK = "found"
kSTATUS_ERR = "not-found"


class CustomAccountManagerMobileWeb(CustomerAccountManagerBase):
    """
    ##### CUSTOMER ACCOUNT MANAGER (Web App) #####
    This microservice is used to expose the SMART GYM Application API for retrieve the
    information of the user. The consumer of this should be an application which has to
    access user data for allowing user experiences.

    It's important to check if the app's token during the logging and the is-logged requests
    is included in the allowed_apps list (this is a form of security due to this service will be
    exposed with a public IP address).

    Also, during the is-logged and logging requests, a session is created through token granting
    which is needed to access all the other sensible endpoints exposed by the microservice.
    """

    def GET(self, *uri, **params):

        def read_one_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_exercise = p.get("id", None)
            check_mandatory_parameters([id_exercise], ["id"])

            # perform the query
            query = "SELECT name, description, muscular_zone, url " \
                    "FROM exercise " \
                    "WHERE id_exercise = ?"
            response = self.db_perform_query(query, (int(id_exercise),), single_res=True)

            # check the exercise exists
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # final result
                exercise_json = {
                    "status": kSTATUS_OK,
                    "name": response[0],
                    "description": response[1],
                    "muscular_zone": response[2],
                    "url": response[3]
                }
                return self.to_json(exercise_json)

        def autocomplete(p, query, dict_key):
            # check application can access the service
            self._auth()

            # retrieve parameters
            q = p.get("query", None)
            check_mandatory_parameters([q], ["query"])
            query_param = "%{}%".format(q)

            # perform the query
            auto_complete = self.db_perform_query(query, (query_param,))

            # final result
            res = kSTATUS_ERR if auto_complete is None else auto_complete
            return json.dumps({dict_key: res})

        def autocomplete_user(p):
            query = "SELECT DISTINCT surname FROM user WHERE surname LIKE ? LIMIT 5"
            return autocomplete(p, query, "surname")

        def autocomplete_exercise(p):
            query = "SELECT name FROM exercise WHERE name LIKE ? LIMIT 5"
            return autocomplete(p, query, "exercise")

        def autocomplete_message(p):
            query = f"SELECT DISTINCT title FROM messages WHERE title LIKE ? LIMIT 5"
            return autocomplete(p, query, "message")

        # === manage_schedules/manage_exercises.php ===
        def manage_exercises(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_schedule = p.get("id", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([id_schedule, records_per_page, from_record_num],
                                       ["id", "records_per_page", "from_record_num"])

            # perform the query to retrieve the exercises of a given schedule
            query = "SELECT id_list, id_exercise, day, repetitions, weight, details " \
                    "FROM exercise_schedules " \
                    "WHERE id_schedule = ? ORDER BY day DESC LIMIT ?,?"
            response = self.db_perform_query(query, (int(id_schedule),  int(from_record_num), int(records_per_page)))

            # check the schedule has some exercises
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of exercises found
                query = "SELECT COUNT(*) FROM exercise_schedules WHERE id_schedule = ?"
                total_record =  self.db_perform_query(query, (int(id_schedule),),  single_res=True)
                exercises = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    ex = {
                            "id_list": response[i][0],
                            "id_exercise": response[i][1],
                            "day": response[i][2],
                            "repetitions": response[i][3],
                            "weight": response[i][4],
                            "details": response[i][5]
                        }
                    exercises.append(ex)

                # for each exercise, retrieve also the name
                for i in range(len(exercises)):
                    query_name = "SELECT name FROM exercise WHERE id_exercise = ?"
                    response_name = self.db_perform_query(query_name, (exercises[i]["id_exercise"],), single_res=True)
                    # check the exercise exist in the exercise table
                    if not response_name:
                        return json.dumps({"status": 'exercise-'+kSTATUS_ERR})
                    else:
                    # save the name
                        exercises[i]["name"] = response_name[0]

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercises,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def manage_schedules(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([id_user, records_per_page, from_record_num],
                                       ["id", "records_per_page", "from_record_num"])

            # perform the query (all the schedules of a given user)
            query = "SELECT id_schedule, name, details, start_date, end_date, num_days, objective " \
                    "FROM schedules " \
                    "WHERE id_user = ? ORDER BY end_date DESC LIMIT ?,?"
            response = self.db_perform_query(query, (int(id_user), int(from_record_num), int(records_per_page)))

            # check there are schedules for the user
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of schedules found
                query = "SELECT COUNT(*) FROM schedules WHERE id_user = ?"
                total_record = self.db_perform_query(query, (int(id_user),), single_res=True)
                exercises = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    start_date = "{}-{}-{}".format(response[i][3].year, response[i][3].month, response[i][3].day)
                    end_date = "{}-{}-{}".format(response[i][4].year, response[i][4].month, response[i][4].day)
                    ex = {
                        "id_schedule": response[i][0],
                        "name": response[i][1],
                        "details": response[i][2],
                        "start_date": start_date,
                        "end_date": end_date,
                        "num_days": response[i][5],
                        "objective": response[i][6]
                    }
                    exercises.append(ex)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "schedules": exercises,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def manage_users(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([records_per_page, from_record_num], ["records_per_page", "from_record_num"])

            # perform the query
            query = "SELECT id_user, name, surname, email " \
                    "FROM user " \
                    "ORDER BY id_user DESC LIMIT ?,?"
            response = self.db_perform_query(query, (int(from_record_num), int(records_per_page)))

            # check that some users exist
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of users found
                query = "SELECT COUNT(*) FROM user"
                total_record = self.db_perform_query(query, single_res=True)
                users = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    us = {
                        "id_user": response[i][0],
                        "name": response[i][1],
                        "surname": response[i][2],
                        "email": response[i][3]
                    }
                    users.append(us)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "users": users,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def account_profile(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id = p.get("id", None)
            check_mandatory_parameters([id], ["id"])

            # perform the query
            query = "SELECT name, surname " \
                    "FROM user " \
                    "WHERE id_user = ?"
            profile = self.db_perform_query(query, (id,), single_res=True)

            # check the user exists
            if profile is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                user_json = {
                    "status": kSTATUS_OK,
                    "name": profile[0],
                    "surname": profile[1],
                }
                return self.to_json(user_json)

        def read_exercises(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([records_per_page, from_record_num], ["records_per_page", "from_record_num"])

            # perform the query
            query = "SELECT * FROM exercise " \
                    "LIMIT ?,?"
            response = self.db_perform_query(query, (int(from_record_num), int(records_per_page)))

            # check some exercises have been found
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                query = "SELECT COUNT(*) FROM exercise "
                total_record = self.db_perform_query(query)
                # number of exercises found
                exercises = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    ex = {
                        "id_exercise": response[i][0],
                        "name": response[i][1],
                        "description": response[i][2],
                        "muscular_zone": response[i][3]
                    }
                    exercises.append(ex)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercises,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def search_exercise_list(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            name = p.get("search", None)

            check_mandatory_parameters([name], ["search"])

            query = "SELECT id_exercise, name, description, muscular_zone " \
                    "FROM exercise " \
                    "WHERE name = ? LIMIT 0,1"
            response = self.db_perform_query(query, (name,), single_res=True)

            # check something have been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:

                # represent exercises as dictionaries
                exercise = []
                ex = {
                    "id_exercise": response[0],
                    "name": response[1],
                    "description": response[2],
                    "muscular_zone": response[3]
                }
                exercise.append(ex)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercise
                }
                return self.to_json(res)

        def search_users_by_surname(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            surname = p.get("search", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([surname, records_per_page, from_record_num],
                                       ["search", "records_per_page", "from_record_num"])

            # perform the query (list of users with the same surname?)
            query = "SELECT id_user, name, surname, email " \
                    "FROM user " \
                    "WHERE surname = ? ORDER BY id_user DESC LIMIT ?,?"
            response = self.db_perform_query(query, (surname, int(from_record_num), int(records_per_page)))

            # check if some users have been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # retrieve the number of users found
                query = "SELECT COUNT(*) FROM user WHERE surname = ?"
                total_record = self.db_perform_query(query, (surname,), single_res=True)
                users = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    us = {
                        "id_user": response[i][0],
                        "name": response[i][1],
                        "surname": response[i][2],
                        "email": response[i][3]
                    }
                    users.append(us)

                # final result
                user_json = {
                    "status": kSTATUS_OK,
                    "users": users,
                    "total_rows": total_record[0]
                }
                return self.to_json(user_json)

        def look_updated_user(p):

            self._auth()

            id_user = p.get("id_user", None)
            check_mandatory_parameters([id_user], ["id_user"])

            # retrieve user fresh information
            query = "SELECT id_user, name, surname, email, birth_date, address, subscription, end_subscription " \
                    "FROM user " \
                    "WHERE id_user = ? LIMIT 0,1"
            response = self.db_perform_query(query, (int(id_user),), single_res=True)

            # check the user exists
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                birth_date = "{}-{}-{}".format(response[4].year, response[4].month, response[4].day)
                end_subscription = "{}-{}-{}".format(response[7].year, response[7].month, response[7].day)
                user_json = {
                    "status": kSTATUS_OK,
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": birth_date,
                    "address": response[5],
                    "subscription": response[6],
                    "end_subscription":end_subscription

                }
                return self.to_json(user_json)

        def look_updated_exercise(p):
            # retrieve exercise schedule info
            # check if the user and the application are allowed to access the service
            self._auth()

            id_list = p.get("id_list", None)

            check_mandatory_parameters([id_list], ["id_list"])

            query = "SELECT id_exercise, day, details, weight, repetitions " \
                    "FROM exercise_schedules " \
                    "WHERE id_list = ?"
            response = self.db_perform_query(query, (int(id_list),), single_res=True)

            # check it has been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # retrieve exercise's name
                query_name = "SELECT name FROM exercise " \
                             "WHERE id_exercise = ?"
                response_name = self.db_perform_query(query_name, (int(response[0]),), single_res=True)

                if response_name is None:
                    return json.dumps({"status": "exercise_name-not-found"})
                else:
                    # final result
                    exercise_json = {
                        "status": kSTATUS_OK,
                        "id_exercise": response[0],
                        "day": response[1],
                        "details": response[2],
                        "weight": response[3],
                        "repetitions": response[4],
                        "name": response_name[0]
                    }

                    return self.to_json(exercise_json)

        def look_updated_exercise_list(p):
            # retrieve exercise info
            self._auth()

            id_exercise = p.get("id_exercise", None)
            check_mandatory_parameters([id_exercise], ["id_exercise"])

            query = "SELECT name, description, muscular_zone, url " \
                    "FROM exercise " \
                    "WHERE id_exercise = ?"
            response = self.db_perform_query(query, (int(id_exercise),), single_res=True)
            # check exercise exists
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # final result
                exercise_json = {
                    "status": kSTATUS_OK,
                    "name": response[0],
                    "description": response[1],
                    "muscular_zone": response[2],
                    "url": response[3],
                }
                return self.to_json(exercise_json)

        def read_one_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            check_mandatory_parameters([id_user], ["id"])

            # perform the query
            query = "SELECT id_user, name, surname, email, birth_date, address, subscription, end_subscription " \
                    "FROM user " \
                    "WHERE id_user = ?"
            response = self.db_perform_query(query, (int(id_user),), single_res=True)

            # final result
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                birth_date = "{}-{}-{}".format(response[4].year, response[4].month, response[4].day)
                end_subscription = "{}-{}-{}".format(response[7].year, response[7].month, response[7].day)
                user_json = {
                    "status": kSTATUS_OK,
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": birth_date,
                    "address": response[5],
                    "subscription": response[6],
                    "end_subscription": end_subscription
                }
                return self.to_json(user_json)

        def read_messages(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([records_per_page, from_record_num], ["records_per_page", "from_record_num"])

            # perform the query
            query = "SELECT id_message, title, send_date, destination " \
                    "FROM messages " \
                    "ORDER BY send_date DESC LIMIT ?,?"
            response = self.db_perform_query(query, (int(from_record_num), int(records_per_page)))

            # check some messages have been found
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of messages found
                query = "SELECT COUNT(*) FROM messages"
                total_record = self.db_perform_query(query, single_res=True)
                messages = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    send_date = "{}-{}-{}".format(response[i][2].year, response[i][2].month, response[i][2].day)
                    mes = {
                        "id_message": response[i][0],
                        "title": response[i][1],
                        "send_date": send_date,
                        "destination": response[i][3]
                    }
                    messages.append(mes)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "messages": messages,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def search_messages(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve the parameters
            title = p.get("search", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([title, records_per_page, from_record_num],
                                       ["search", "records_per_page", "from_record_num"])

            # retrieve the messages from DB (given the title)
            query = "SELECT id_message, title, body, send_date, destination " \
                    "FROM messages " \
                    "WHERE title = ? " \
                    "ORDER BY send_date DESC LIMIT ?,?"
            response = self.db_perform_query(query, (title, int(from_record_num), int(records_per_page),))

            # check if some messages have been retrieved
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of messages found
                query = "SELECT COUNT(*) FROM messages WHERE title = ?"
                total_record = self.db_perform_query(query, (title,), single_res=True)
                messages = []
                # represent exercises as array of dictionaries
                for i in range(len(response)):
                    send_date = "{}-{}-{}".format(response[i][3].year, response[i][3].month, response[i][3].day)
                    mes = {
                        "id_message": response[i][0],
                        "title": response[i][1],
                        "body": response[i][2],
                        "send_date": send_date,
                        "destination": response[i][4]
                    }
                    messages.append(mes)

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "messages": messages,
                    "total_rows": total_record[0]
                }
                return self.to_json(res)

        def read_one_message(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id = p.get("id", None)
            check_mandatory_parameters([id], ["id"])

            # perform the query
            query = "SELECT id_message, title, body, send_date, destination " \
                    "FROM messages " \
                    "WHERE id_message = ?"
            response = self.db_perform_query(query, (int(id),), single_res=True)

            # final result
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                send_date = "{}-{}-{}".format(response[3].year, response[3].month, response[3].day)
                res = {
                    "status":kSTATUS_OK,
                    "id_message": response[0],
                    "title": response[1],
                    "body": response[2],
                    "send_date": send_date,
                    "destination": response[4]
                }
                return self.to_json(res)

        def account_logout(p):
            token = self.get_token_cookie()
            if token in self.logged_users:
                del self.logged_users[token]
            return json.dumps({"status": "successful"})

        def is_logged(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            return json.dumps({"logged": self.check_logged_user()})

        def default(p):
            return cherrypy.HTTPError(400, "The URL is not valid")

        endpoints = {
            "manage_schedules/read_one_exercise": read_one_exercise,
            "manage_schedules/autocomplete": autocomplete_user,
            "manage_schedules/autocomplete_exercise": autocomplete_exercise,
            "manage_schedules/manage_schedules": manage_schedules,
            "manage_schedules/manage_users": manage_users,
            "manage_schedules/manage_exercises": manage_exercises,
            "manage_schedules/read_exercises": read_exercises,
            "manage_schedules/search_user": search_users_by_surname,
            "manage_schedules/search_exercise_list": search_exercise_list,
            "manage_schedules/look_updated_exercise": look_updated_exercise,
            "manage_schedules/look_updated_exercise_list": look_updated_exercise_list,
            "manage_user/autocomplete": autocomplete_user,
            "manage_user/look_updated_user": look_updated_user,
            "manage_user/read_one_user": read_one_user,
            "send_messages/autocomplete": autocomplete_message,
            "send_messages/read_messages": read_messages,
            "send_messages/read_one_message": read_one_message,
            "send_messages/search": search_messages,
            "account/logout": account_logout,
            "account/is_logged": is_logged,
            "account/profile": account_profile
        }

        return endpoints.get("/".join(uri), default)(params)

    def POST(self, *uri, **params):

        def logging(p):
            # check if the the application is allowed to access the service
            self._auth_app()

            # retrieve parameters
            email = p.get("email", None)
            password = p.get('password')

            check_mandatory_parameters([email, password], ["email", "password"])

            # perform the query
            query = "SELECT id_account, first_name, last_name, password FROM account WHERE email = ?"
            response = self.db_perform_query(query, (email,), single_res=True)

            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:

                if not pbkdf2_sha256.verify(password, response[3]):
                    return json.dumps({"status": 'wrong-pass'})

                cherrypy.response.cookie["token"] = self.set_logged_user(response[0])
                user_json = {
                    "first_name": response[1],
                    "last_name": response[2],
                    "status": "successful"
                }
                return self.to_json(user_json)

        def register_account(p):

            # check if the application is allowed to access the service
            self._auth_app()

            # retrieve parameters
            email = p.get("email", None)
            first_name = p.get("first_name", None)
            last_name = p.get("last_name", None)
            password = p.get("password", None)
            check_mandatory_parameters([email, first_name, last_name, password],
                                       ["email", "first_name", "last_name", "password"])

            # maybe the user was already registered
            query = "SELECT id_account FROM account WHERE email = ?"
            control = self.db_perform_query(query, (email,), single_res=True)

            if control is None:

                hash = pbkdf2_sha256.hash(password)

                # try to register the user
                query = "INSERT INTO account (first_name, last_name, email, password) VALUES (?,?,?,?)"
                params = (first_name, last_name, email, hash)
                self.db_perform_query(query, params, single_res=True)

                if self.db_manager.row_count == 0:
                    return json.dumps({"status": "not-registered"})

                # retrieve the generated user ID
                query = "SELECT id_account FROM account WHERE email = ?"
                account_id = self.db_perform_query(query, (email,), single_res=True)
                cherrypy.response.cookie["token"] = self.set_logged_user(account_id[0])

                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "already-registered"})


        def create_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_schedule = p.get("id", None)
            name = p.get("name", None)
            day = p.get("day", None)
            series = p.get("series", None)
            weight = p.get("weight", None)
            details = p.get("details", None)
            check_mandatory_parameters([id_schedule, name, day, series, weight, details],
                                       ["id", "name", "day", "series", "weight", "details"])

            # check the exercise exists
            query = "SELECT id_exercise FROM exercise WHERE name = ?"
            id_exercise = self.db_perform_query(query, (name,), single_res=True)
            if id_exercise is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # assign the exerise to the schedule
                query_ins = "INSERT INTO exercise_schedules " \
                            "(id_schedule, id_exercise, day, repetitions, weight, details) " \
                            "VALUES (?,?,?,?,?,?)"
                query_params = (int(id_schedule), int(id_exercise[0]), int(day), int(series), int(weight), details)
                self.db_perform_query(query_ins, query_params)

                # final result
                status = "not-inserted" if self.db_manager.row_count() == 0 else "successful"
                return json.dumps({"status": status})

        def create_exercise_list(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            name = p.get("name", None)
            description = p.get("description", None)
            muscular_zone = p.get("muscular_zone", None)
            url = p.get("url", None)
            check_mandatory_parameters([name, description, muscular_zone, url],
                                       ["name", "description", "muscular_zone", "url"])

            # create the exercise
            query = "INSERT INTO exercise (name, description, muscular_zone, url) VALUES (?,?,?,?)"
            query_params = (name, description, muscular_zone, url)

            self.db_perform_query(query, query_params)

            # final result
            status = "not-inserted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def create_schedule(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            name = p.get("name", None)
            details = p.get("details", None)
            start_date = p.get("start_date", None)
            end_date = p.get("end_date", None)
            num_days = p.get("num_days", None)
            objective = p.get("objective", None)
            check_mandatory_parameters([id_user, name, details, start_date, end_date, num_days, objective],
                                       ["id", "name", "details", "start_date", "end_date", "num_days", "objective"])

            # perform the query
            query = "INSERT INTO schedules (id_user,name,details,start_date,end_date,num_days,objective) " \
                    "VALUES (?,?,?,?,?,?,?)"
            params = (int(id_user), name, details, start_date, end_date, int(num_days), objective)
            self.db_perform_query(query, params)

            status = "not-inserted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def delete_schedules(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            check_mandatory_parameters([id_user], ["id"])

            # delete all the schedules for a given user
            query = "SELECT id_schedule FROM schedules WHERE id_user = ?"
            response = self.db_perform_query(query, (int(id_user),))

            if response is not None:
                # for each schedule, delete all the references in tables 'schedules' and 'exercise_schedule'
                for i in range(len(response)):

                    # 2. Delete schedule
                    query_sc = "DELETE FROM schedules WHERE id_schedule = ?"
                    delete_sc = self.db_perform_query(query_sc, (int(response[i][0]),))
                    if delete_sc is None:
                        return json.dumps({"status": "not-deleted"})

                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def delete_single_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_list = p.get("id", None)
            check_mandatory_parameters([id_list], ["id"])

            # delete the exercise list
            query = "DELETE FROM exercise_schedules WHERE id_list = ?"
            self.db_perform_query(query, (int(id_list),))

            status = "not-deleted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def delete_single_exercise_list(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_exercise = p.get("id", None)
            check_mandatory_parameters([id_exercise], ["id"])

            # delete the exercise
            query = "DELETE FROM exercise WHERE id_exercise = ?"
            self.db_perform_query(query, (int(id_exercise),))

            status = "not-deleted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def delete_single_schedule(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_schedule = p.get("id", None)
            check_mandatory_parameters([id_schedule], ["id"])

            # perform the queries

            # 1. Delete the schedule
            query_sc = "DELETE FROM schedules WHERE id_schedule = ?"
            self.db_perform_query(query_sc, (int(id_schedule),))
            if self.db_manager.row_count == 0:
                return json.dumps({"status": "not-deleted"})

            return json.dumps({"status": "successful"})

        def create_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            name = p.get("name", None)
            surname = p.get("surname", None)
            email = p.get("email", None)
            password = p.get("password", None)
            address = p.get("address", None)
            birth_date = p.get("birth_date", None)
            phone = p.get("phone", None)
            subscription = p.get("subscription", None)
            end_subscription = p.get("end_subscription", None)
            check_mandatory_parameters(
                [name, surname, email, password, address, birth_date, phone, subscription, end_subscription],
                ["name", "surname", "email", "password", "address", "birth_date", "phone", "subscription",
                 "end_subscription"])

            hash = pbkdf2_sha256.hash(password)

            # check errors (user already existing or DB error)
            query_check = "SELECT id_user FROM user WHERE email = ?"
            response_check = self.db_perform_query(query_check, (email,), single_res=True)

            if response_check is None:
                # try to create the user
                query = "INSERT INTO user " \
                        "(name, surname, email, password, address, birth_date, phone, subscription, end_subscription) " \
                        "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                query_params = (
                name, surname, email, hash, address, birth_date, phone, subscription, end_subscription)
                self.db_perform_query(query, query_params, single_res=True)

                # final result
                if self.db_manager.row_count != 0:
                    return json.dumps({"status": "successful"})
                else:
                    return json.dumps({"status": "not-inserted"})
            else:
                return json.dumps({"status": "already-registered"})

        def update_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            name = p.get("name", None)
            surname = p.get("surname", None)
            email = p.get("email", None)
            birth_date = p.get("birth_date", None)
            address = p.get("address", None)
            subscription = p.get("subscription", None)
            end_subscription = p.get("end_subscription", None)

            check_mandatory_parameters(
                [id_user, name, surname, email, birth_date, address, subscription, end_subscription],
                ["id", "search", "surname", "email", "birth_date", "address", "subscription", "end_subscription"])

            # execute the query
            query_update = "UPDATE user " \
                           "SET name=?, surname=?, email=?,birth_date=?, address=?,subscription=?, end_subscription=?" \
                           "WHERE id_user = ?"
            query_params = (name, surname, email, birth_date, address, subscription, end_subscription, int(id_user))
            self.db_perform_query(query_update, query_params)

            # final result
            if self.db_manager.row_count == 0:
                return json.dumps({"status": 'user_not_updated'})
            else:
                return json.dumps({"status": 'user_updated'})

        def update_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_list = p.get("id", None)
            day = p.get("day", None)
            repetitions = p.get("repetitions", None)
            weight = p.get("weight", None)
            details = p.get("details", None)
            check_mandatory_parameters([id_list, day, repetitions, weight, details],
                                       ["id", "day", "repetitions", "weight", "details"])

            # Update general info inside exercise_schedules table
            query = "UPDATE exercise_schedules " \
                    "SET day=?, repetitions=?, weight=?, details=? " \
                    "WHERE id_list = ?"
            query_params = (int(day), int(repetitions), int(weight), details, int(id_list))
            self.db_perform_query(query, query_params)

            # check the exercise has been updated
            if self.db_manager.row_count == 0:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                return json.dumps({"status": kSTATUS_OK})

        def update_exercise_list(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_exercise = p.get("id", None)
            name = p.get("name", None)
            description = p.get("description", None)
            muscular_zone = p.get("muscular_zone", None)
            url = p.get("url", None)
            check_mandatory_parameters([id_exercise, name, description, muscular_zone, url],
                                       ["id", "name", "description", "muscular_zone", "url"])

            # perform the query
            query = "UPDATE exercise " \
                    "SET name=?, description=?, muscular_zone=?, url=? " \
                    "WHERE id_exercise = ?"
            params = (name, description, muscular_zone, url, int(id_exercise))
            self.db_perform_query(query, params)

            if self.db_manager.row_count() == 0:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                return json.dumps({"status": kSTATUS_OK})

        def delete_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            check_mandatory_parameters([id_user], ["id"])

            # execute the query
            query = "DELETE FROM user WHERE id_user = ?"
            self.db_perform_query(query, (int(id_user),))

            # final result
            status = "not-deleted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def create_message(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            title = p.get("title", None)
            body = p.get("body", None)
            send_date = p.get("send_date", None)
            destination = p.get("destination", None)
            check_mandatory_parameters([title, body, send_date, destination],
                                       ["title", "body", "send_date", "destination"])

            # create the message
            query = "INSERT INTO messages " \
                    "(title, body, send_date, destination)" \
                    "VALUES (?,?,?,?)"
            query_params = (title, body, send_date, destination)
            self.db_perform_query(query, query_params)

            # final result
            status = "not-inserted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def default(p):
            return cherrypy.HTTPError(400, "The URL is not valid")

        endpoints = {
            "login_website/login": logging,
            "login_website/register": register_account,
            "manage_schedules/create_exercise": create_exercise,
            "manage_schedules/create_exercise_list": create_exercise_list,
            "manage_schedules/create_schedule": create_schedule,
            "manage_schedules/delete_schedules": delete_schedules,
            "manage_schedules/delete_single_exercise": delete_single_exercise,
            "manage_schedules/delete_single_exercise_list": delete_single_exercise_list,
            "manage_schedules/delete_single_exercise_schedule": delete_single_schedule,
            "manage_user/create_user": create_user,
            "manage_user/update_user": update_user,
            "manage_schedules/update_exercise_list": update_exercise_list,
            "manage_schedules/update_exercise": update_exercise,
            "manage_user/delete_user": delete_user,
            "send_messages/create_message": create_message
        }

        return endpoints.get("/".join(uri), default)(params)

    def application_logic_exe(self):
        # create a connection to the DB
        self.db_connect()

        # TODO: the loop
        print('Customer Account Manager Web module started.')
        while True:
            time.sleep(60)


if __name__ == '__main__':
    from smartgym_packages.core.launcher import Launcher
    CONF = 'config_files/customer_manager_web.conf'

    l = Launcher()
    l.start_catalog()
    l.start('CAM_web', CustomAccountManagerMobileWeb.start_ms_server_mode,
            args=(10, CONF ))

    # import time
    # time.sleep(5)
    # CustomAccountManagerMobileWeb.start_ms_server_mode(10, CONF, verbose=True )
    #
    # l.stop_all()
