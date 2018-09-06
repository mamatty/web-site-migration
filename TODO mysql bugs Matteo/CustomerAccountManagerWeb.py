import json
import time

import cherrypy

from microservices.customer_account_base import CustomerAccountManagerBase
from smartgym_packages.utilities.controls import check_mandatory_parameters

# TODO: more than "found"-"not-found", I would use (in general) "OK"-"ERR" for the status field
# TODO: move these constants inside consts.py (@Matteo: ignore this TODO)
kSTATUS_OK = "found"
kSTATUS_ERR = "not-found"


class CustomAccountManagerMobileWeb(CustomerAccountManagerBase):
    """
    ##### CUSTOMER ACCOUNT MANAGER (Web App) #####
    This microservice is used to expose the SMART GYM Application API for retrieve the
    information of the user. The consumer of this should be an application which has to
    access user data for allowing user experiences.

    It's important to check if the app's token during the logging and the is-logged requests
    is included in the allowed_apps list (this is a form of security due to this service will be
    exposed with a public IP address).

    Also, during the is-logged and logging requests, a session is created through token granting
    which is needed to access all the other sensible endpoints exposed by the microservice.
    """

    def _set_config_settings(self, settings: dict) -> None:
        try:
            self.db_user = settings['config_settings']["db_user"]
            self.db_password = settings['config_settings']["db_password"]
            self.db_ip_address = settings['config_settings']["db_ip_address"]
            self.db_name = settings['config_settings']["db_name"]
            self.allowed_users = settings['config_settings']["allowed-apps"]
        except KeyError:
            print('Bad microservice configuration: something went wrong when contacting the catalog')
            raise

    def GET(self, *uri, **params):

        def autocomplete(p, query, dict_key):
            # check application can access the service
            self._auth()

            # retrieve parameters
            q = p.get("query", None)
            check_mandatory_parameters([q], ["query"])
            query_param = "%{}%".format(q)

            # perform the query
            auto_complete = self.db_perform_query(query, (query_param,), single_res=True)

            # final result
            res = "no result" if auto_complete is None else auto_complete[0]
            return json.dumps({dict_key: res})

        def autocomplete_user(p):
            # TODO: maybe LIMIT is 1?
            # @Giorgio: When i am looking for something and I start my research typing in the form,
            # I would like to expect a certain number of results:
            # Example:
            # If I am looking for 'Avalle', I start typing A, then v and so on.
            # Obviously, there will be more than one user, or exercise or other things, that starts with A.
            # So, what we do is to show to the user 5 possible results depending on his initial research.
            query = "SELECT surname FROM user WHERE surname LIKE ? LIMIT 5"
            return autocomplete(p, query, "surname")

        def autocomplete_exercise(p):
            # TODO: maybe LIMIT is 1?
            # The same of above
            query = "SELECT name FROM exercise WHERE name LIKE ? LIMIT 5"
            return autocomplete(p, query, "exercise")

        def autocomplete_message(p):
            # TODO: maybe LIMIT is 1?
            # The same of above
            query = f"SELECT title FROM messages WHERE title LIKE ? LIMIT 5"
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
            response = self.db_perform_query(query, (id_schedule, records_per_page, from_record_num))

            # check the schedule has some exercises
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of exercises found
                total_record = len(response)
                # represent exercises as dictionaries
                exercises = list(map(
                    lambda u: {
                        "id_list": response[0],
                        "id_exercise": response[1],
                        "day": response[2],
                        "repetitions": response[3],
                        "weight": response[4],
                        "details": response[5]
                    },
                    response
                ))

                # for each exercise, retrieve also the name
                for i in range(len(exercises)):
                    query_name = "SELECT name FROM exercise WHERE id_exercise = ?"
                    response_name = self.db_perform_query(query_name, (exercises[i]["id_exercise"],), single_res=True)
                    # check the exercise exist in the exercise table
                    if not response_name:
                        return json.dumps({"status": kSTATUS_ERR})
                    else:
                        # save the name
                        exercises[i]["name"] = response_name[0]

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercises,
                    "total_rows": total_record
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
            response = self.db_perform_query(query, (id_user, records_per_page, from_record_num))

            # check there are schedules for the user
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of schedules found
                total_record = len(response)
                # represent schedules as dictionaries
                schedules = list(map(
                    lambda s: {
                        "id_schedule": response[0],
                        "name": response[1],
                        "details": response[2],
                        "start_date": response[3],
                        "end_date": response[4],
                        "num_days": response[5],
                        "objective": response[6]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "schedules": schedules,
                    "total_rows": total_record
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
            response = self.db_perform_query(query, (records_per_page, from_record_num))

            # check that some users exist
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of users found
                total_record = len(response)
                # represent users as dictionaries
                users_info = list(map(
                    lambda u: {
                        "id_user": u[0],
                        "name": u[1],
                        "surname": u[2],
                        "email": u[3]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "users": users_info,
                    "total_rows": total_record
                }
                return self.to_json(res)

        def account_profile():
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            user_id = self.logged_users[self.get_token_cookie()]

            # perform the query
            query = "SELECT name, surname " \
                    "FROM user " \
                    "WHERE id_user = ?"
            profile = self.db_perform_query(query, (user_id,), single_res=True)

            # check the user exists
            # TODO: this is the only one that raises an exception. Is it fine? (logged but non-existing user)
            # @Giorgio: This specific function was implemented by Marco at the very begining and there was this exception
            # Do you think is necessary to handle it in another way or maybe remove it?
            if profile is None:
                # TODO: find a smarter way to handle this
                raise cherrypy.HTTPError(500, "Internal error!")
            else:
                user_json = {
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
            response = self.db_perform_query(query, (records_per_page, from_record_num))

            # check some exercises have been found
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # number of exercises found
                total_record = len(response)
                # represent exercises as dictionaries
                exercises = list(map(
                    lambda e: {
                        "id_exercise": e[0],
                        "name": e[1],
                        "description": e[2],
                        "muscular_zone": e[3]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercises,
                    "total_rows": total_record
                }
                return self.to_json(res)

        def read_one_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_exercise = p.get("id", None)
            check_mandatory_parameters([id_exercise], ["id"])

            # perform the query
            query = "SELECT name, description, muscular_zone " \
                    "FROM exercise " \
                    "WHERE id_exercise = ?"
            response = self.db_perform_query(query, (id_exercise,), single_res=True)

            # check the exercise exists
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # final result
                exercise_json = {
                    "status": kSTATUS_OK,
                    "name": response[0],
                    "description": response[1],
                    "muscular_zone": response[2]
                }
                return self.to_json(exercise_json)

        def search_exercise_list(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            name = p.get("search", None)

            check_mandatory_parameters([name], ["search"])

            # perform the query (list of exercises having a same name?)
            # TODO: why? we are looking for exercises with the same name. Review database structure
            # @Giorgio: Here we perform a search about an exercise that is present inside the database.
            query = "SELECT id_exercise, name, description, muscular_zone " \
                    "FROM exercise " \
                    "WHERE name = ? "
            response = self.db_perform_query(query, (name,), single_res=True)

            # check something have been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # retrieve the number of results that have been found
                total_record = len(response)
                # represent exercises as dictionaries
                exercises = list(map(
                    lambda e: {
                        "id_exercise": e[0],
                        "name": e[1],
                        "description": e[2],
                        "muscular_zone": e[3]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "exercises": exercises,
                    "total_rows": total_record
                }
                return self.to_json(res)

        def search_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            surname = p.get("search", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)
            check_mandatory_parameters([surname, records_per_page, from_record_num],
                                       ["search", "records_per_page", "from_record_num"])

            # perform the query (list of users with the same surname?)
            # TODO: Why? this is searching all the users with a given surname. Review the query
            # @Giorgio: exactly. When we perform the query, we do it per surname. At the end we could have many results with the same surname, not only one
            query = "SELECT id_user, name, surname, email " \
                    "FROM user " \
                    "WHERE surname = ? ORDER BY id_user DESC LIMIT ?,?"
            response = self.db_perform_query(query, (surname, records_per_page, from_record_num))

            # check if some users have been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # retrieve the number of users found
                total_record = len(response)
                # represent users as dictionaries
                users = list(map(
                    lambda u: {
                        "id_user": u[0],
                        "name": u[1],
                        "surname": u[2],
                        "email": u[3]
                    },
                    response
                ))

                # final result
                user_json = {
                    "status": kSTATUS_OK,
                    "users": users,
                    "total_rows": total_record
                }
                return self.to_json(user_json)

        def update_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            # TODO: "id_list" is always missing. Not clear the distiction between "id", "id_exercise" and "id_list"
            # @Giogio: Here the query structure was completely different, I corrected it.

            id_list = p.get("id", None)
            name = p.get("name", None)
            day = p.get("day", None)
            repetitions = p.get("repetitions", None)
            weight = p.get("weight", None)
            details = p.get("details", None)
            check_mandatory_parameters([id_list,  name, day, repetitions, weight, details],
                                       ["id", "name", "day", "repetitions", "weight", "details"])

            # perform the query
            # TODO: would be useful to have DB transactions for these two consecutive updates, in order to have a rollback in case of errors
            # TODO: or better... Why should I want to change the exercise name together with schedule information? Remove the "name" parameter
            # @Giorgio: About the transaction, I agree with you. I need to understand the right sintax in order to call it.
            # About the possibility to change the exercise name, is due to the fact that sometimes is enough to change an exercise with another one
            # maybe because that exercise is too tough for you o maybe because is dangerous for your health in some way. Deleting this step
            # what the operator has to do is to delete the current exercise and create a new one. With this implementation, this step is overcomed.
            # We talked about it last time via email.

            # 1. Check if the the exercise name is present inside the DB
            query = "SELECT id_exercise " \
                    "FROM exercise " \
                    "WHERE name = ?"
            query_params = (name,)
            id_exercise = self.db_perform_query(query, query_params, single_res=True)
            # check the exercise has been updated
            if id_exercise is None:
                return json.dumps({"status": kSTATUS_ERR})

            # 2. Update general info inside exercise_schedules table
            query = "UPDATE exercise_schedules " \
                    "SET id_exercise= ?, day=?, repetitions=?, weight=?, details=? " \
                    "WHERE id_list = ?"
            query_params = (id_exercise[0], day, repetitions, weight, details, id_list)
            self.db_perform_query(query, query_params)

            # check the exercise has been updated
            if self.db_manager.row_count == 0:
                return json.dumps({"status": kSTATUS_ERR})

            # final result
            look_updated_exercise(id_list, id_exercise)

        def look_updated_exercise(id_list, id_exercise):
            # TODO: instead of performing the queries, I think you can directly return the values you have written in DB
            # @Giorgio: This is a method that i call also when I open the page for the update, in order to show the exercise informations.
            # If I pass the parameters directly with the response, anyway I have to create a new method to obtain the informations.
            # At the end is the same thing, but this approach is probably better in my opinion.
            # retrieve exercise schedule info
            query = "SELECT id_exercise, day, details, weight, repetitions " \
                    "FROM exercise_schedules " \
                    "WHERE id_list = ?"
            response = self.db_perform_query(query, (id_list,), single_res=True)
            # check it has been found
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # retrieve exercise's name
                query_name = "SELECT name FROM exercise " \
                             "WHERE id_exercise = ?"
                response_name = self.db_perform_query(query_name, (id_exercise,), single_res=True)
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
            params = (name, description, muscular_zone, url, id_exercise)
            self.db_perform_query(query, params)

            if self.db_manager.row_count() == 0:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                look_updated_exercise_list(id_exercise)

        def look_updated_exercise_list(id_exercise):
            # TODO: instead of performing the query, I think you can directly return the values you have written in DB
            # @Giorgio: same of above
            # retrieve exercise info
            query = "SELECT name, description, muscular_zone, url " \
                    "FROM exercise " \
                    "WHERE id_exercise = ?"
            response = self.db_perform_query(query, (id_exercise,), single_res=True)
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
            query = "SELECT id_user, name, surname, email, birth_date, address, image, subscription, end_subscription " \
                    "FROM user " \
                    "WHERE id_user = ?"
            response = self.db_perform_query(query, (id_user,), single_res=True)

            # final result
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                user_json = {
                    "status": kSTATUS_OK,
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": response[4],
                    "address": response[5],
                    "image": response[6],
                    "subscription": response[7],
                    "end_subscription": response[8]
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
            response = self.db_perform_query(query, [(from_record_num, records_per_page)])

            # check some messages have been found
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of messages found
                total_record = len(response)
                # represent messages as dictionaries
                messages = list(map(
                    lambda m: {
                        "id_message": m[0],
                        "title": m[1],
                        "send_date": m[2],
                        "destination": m[3]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "messages": messages,
                    "total_rows": total_record
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
            response = self.db_perform_query(query, (title, from_record_num, records_per_page))

            # check if some messages have been retrieved
            if not response:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                # evaluate the number of messages found
                total_record = len(response)
                # represent messages as dictionaries
                messages = list(map(
                    lambda m: {
                        "id_message": m[0],
                        "title": m[1],
                        "body": res[2],
                        "send_date": m[2],
                        "destination": m[3]
                    },
                    response
                ))

                # final result
                res = {
                    "status": kSTATUS_OK,
                    "messages": messages,
                    "total_rows": total_record
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
            response = self.db_perform_query(query, (id,), single_res=True)

            # final result
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                res = {
                    "id_message": response[0],
                    "title": response[1],
                    "body": response[2],
                    "send_date": response[3],
                    "destination": response[4]
                }
                return self.to_json(res)

        def account_logout():
            self.logged_users.pop(self.get_token_cookie())
            return json.dumps({"status": "successful"})

        def is_logged():
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            return json.dumps({"logged": self.check_logged_user()})

        def default():
            return cherrypy.HTTPError(400, "The URL is not valid")

        endpoints = {
            "manage_schedules/autocomplete": autocomplete_user(params),
            "manage_schedules/autocomplete_exercise": autocomplete_exercise(params),
            "manage_schedules/manage_schedules": manage_schedules(params),
            "manage_schedules/manage_users": manage_users(params),
            "manage_schedules/manage_exercises": manage_exercises(params),
            "manage_schedules/read_exercises": read_exercises(params),
            "manage_schedules/read_one_exercise": read_one_exercise(params),
            "manage_schedules/search_user": search_user(params),
            "manage_schedules/search_exercise_list": search_exercise_list(params),
            "manage_schedules/update_exercise": update_exercise(params),
            "manage_schedules/update_exercise_list": update_exercise_list(params),
            "manage_user/autocomplete": autocomplete_user(params),
            "manage_user/read_one_user": read_one_user(params),
            "send_messages/autocomplete": autocomplete_message(params),
            "send_messages/read_messages": read_messages(params),
            "send_messages/read_one_message": read_one_message(params),
            "send_messages/search": search_messages(params),
            "account/logout": account_logout(),
            "account/is_logged": is_logged(),
            "account/profile": account_profile()
        }

        endpoints.get("/".join(uri), default())

    def POST(self, *uri, **params):

        #  TODO: ask @MATTEO why so many log-stuff -> @Giorgio: here looks like an "is_logged" feature
        def logging(p):
            # check if the the application is allowed to access the service
            self._auth_app()

            # retrieve parameters
            email = p.get("email", None)
            password = p.get("password", None)
            check_mandatory_parameters([email, password], ["email", "password"])

            # perform the query
            query = "SELECT id_user, first_name, last_name FROM user WHERE email = ? and password = ?"
            response = self.db_perform_query(query, (email, password), single_res=True)

            if response is None:
                # TODO: here status was "not-found". So, I kept it
                return json.dumps({"status": kSTATUS_ERR})
            else:
                self.set_logged_user(response[0])
                user_json = {
                    "first_name": response[1],
                    "last_name": response[2],
                    "status": "successful"
                }
                return self.to_json(user_json)

        def register_account(p):
            # check if the application is allowed to access the service
            self._auth_app()

            # TODO: need to provide the cookie
            # @Giorgio: yes, requests are implemented yet with the cookie.

            # retrieve parameters
            email = p.get("email", None)
            first_name = p.get("first_name", None)
            last_name = p.get("last_name", None)
            password = p.get("password", None)
            check_mandatory_parameters([email, first_name, last_name, password],
                                       ["email", "first_name", "last_name", "password"])

            # try to register the user
            # TODO: Create a UNIQUE index over the "email" field. In this way, the control is executed only in case of errors... and user research is speed-up
            # @Giorgio: Correction done. Now email is a varchar of lenght 255, unique key. Do you think at this point that
            # the check is still necessary?
            query = "INSERT INTO account (first_name, last_name, email, password) VALUES (?,?,?,?)"
            params = (email, first_name, last_name, password)
            self.db_perform_query(query, params)
            if self.db_manager.row_count() == 0:
                # maybe the user was already registered
                query = "SELECT id_user FROM user WHERE email = ?"
                control = self.db_perform_query(query, (email,), single_res=True)
                if control is None:
                    return json.dumps({"status": "already-registered"})
                else:
                    # another kind of error occured
                    return json.dumps({"status": "not-registered"})
            else:
                # retrieve the generated user ID
                query = "SELECT id_user FROM user WHERE email = ?"
                user_id = self.db_perform_query(query, (email,), single_res=True)[0]
                self.set_logged_user(user_id)
                return json.dumps({"status": "successful"})

        # TODO: is the name correct? maybe you are scheduling an existing exercise
        # @Giorgio: yes, is correct! When you look the key word 'list', it means that
        # i'm referring to the exercise present in the database, otherwise i mean the
        # exercises inserted in the user's schedules.
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
                query_params = (id_schedule, id_exercise[0], day, series, weight, details)
                self.db_perform_query(query_ins, query_params)

                # final result
                status = "not-inserted" if self.db_manager.row_count() == 0 else "successful"
                return json.dumps({"status": status})

        # TODO: is the name correct? maybe you are creating an exercise
        # @Giorgio: same of above.
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
            params = (id_user, name, details, start_date, end_date, num_days, objective)
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
            self.db_perform_query(query, (id_user,))

            # for each schedule, delete all the references in tables 'schedules' and 'exercise_schedule'
            for i in range(len(query)):

                # 2. Delete schedule
                query_sc = "DELETE FROM schedules WHERE id_schedule = ?"
                self.db_perform_query(query_sc, (query[i],))
                if self.db_manager.row_count == 0:
                    return json.dumps({"status": "not-deleted"})

                # 3. Delete its exercises
                query_ex = "DELETE FROM exercise_schedules WHERE id_schedule = ?"
                self.db_perform_query(query_ex, (query[i],))
                if self.db_manager.row_count == 0:
                    return json.dumps({"status": "not-deleted"})

            return json.dumps({"status": "successful"})

        def delete_single_exercise(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_list = p.get("id", None)
            check_mandatory_parameters([id_list], ["id"])

            # delete the exercise list
            query = "DELETE FROM exercise_schedules WHERE id_list = ?"
            self.db_perform_query(query, (id_list,))

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
            self.db_perform_query(query, (id_exercise,))

            status = "not-deleted" if self.db_manager.row_count == 0 else "successful"
            return json.dumps({"status": status})

        def delete_single_schedule(p):
            # TODO: maybe this method is duplicated? "delete_schedule" already exists, check there is a difference
            # @Giorgio: No. This one is necessary to delete a single schedule.
            # delete_schedules is necessary to delete all the schedules of a user.

            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_schedule = p.get("id", None)
            check_mandatory_parameters([id_schedule], ["id"])

            # perform the queries
            # TODO: would be useful to have DB transactions here, in order to rollback in case of errors
            # TODO: or, better... Define FOREIGN KEY with UPDATE = CASCADE, in order to propagate the deletion
            # @Giorgio: ok, now i try to understand how to do.

            # 1. Delete the schedule
            query_sc = "DELETE FROM schedules WHERE id_schedule = ?"
            self.db_perform_query(query_sc, (id_schedule,))
            if self.db_manager.row_count == 0:
                return json.dumps({"status": "not-deleted"})
            # 2. Delete its exercises
            query_ex = "DELETE FROM exercise_schedules WHERE id_schedule = ?"
            self.db_perform_query(query_ex, (id_schedule,))
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

            # try to create the user
            # TODO: Please, I need a UNIQUE index over the "email" field
            # @Giorgio: done!
            query = "INSERT INTO user " \
                    "(name, surname, email, password, address, birth_date, phone, subscription, end_subscription) " \
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            query_params = (name, surname, email, password, address, birth_date, phone, subscription, end_subscription)
            self.db_perform_query(query, query_params, single_res=True)

            # final result
            if self.db_manager.row_count > 0:
                return json.dumps({"status": "successful"})
            else:
                # check errors (user already existing or DB error)
                query_check = "SELECT id_user FROM user WHERE email = ?"
                response_check = self.db_perform_query(query_check, (email,), single_res=True)
                if response_check is None:
                    return json.dumps({"status": "already-registered"})
                else:
                    return json.dumps({"status": "not-inserted"})

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

            check_mandatory_parameters([id_user, name, surname, email, birth_date, address, subscription, end_subscription],
                                       ["id", "search", "surname", "email", "birth_date", "address", "subscription", "end_subscription"])

            # execute the query
            query_update = "UPDATE user " \
                           "SET name=?, surname=?, email=?,birth_date=?, address=?,subscription=?, end_subscription=?" \
                           "WHERE id_user = ?"
            query_params = (name, surname, email, birth_date, address, subscription, end_subscription, id_user)
            self.db_perform_query(query_update, query_params)

            # final result
            if self.db_manager.row_count == 0:
                # TODO: status was "not-found"
                return json.dumps({"status": kSTATUS_ERR})
            else:
                look_updated_user(id_user)

        # TODO: actually, instead of calling this method you could return directly the updated fresh value
        # @Giorgio: Same of above (for other look_.. methods)
        def look_updated_user(id_user):
            # retrieve user fresh information
            query = "SELECT id_user, name, surname, email, birth_date, address, id_subscription " \
                    "FROM user " \
                    "WHERE id_user = ?"
            response = self.db_perform_query(query, (id_user,), single_res=True)

            # check the user exists
            if response is None:
                return json.dumps({"status": kSTATUS_ERR})
            else:
                user_json = {
                    "status": kSTATUS_OK,
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": response[4],
                    "address": response[5],
                    "id_subscription": response[6]
                }
                return self.to_json(user_json)

        def delete_user(p):
            # check if the user and the application are allowed to access the service
            self._auth()

            # retrieve parameters
            id_user = p.get("id", None)
            check_mandatory_parameters([id_user], ["id"])

            # execute the query
            query = "DELETE FROM user WHERE id_user = ?"
            self.db_perform_query(query, (id_user,))

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

        def default():
            return cherrypy.HTTPError(400, "The URL is not valid")

        endpoints = {
            "login_website/login": logging(params),
            "login_website/register": register_account(params),
            "manage_schedules/create_exercise": create_exercise(params),
            "manage_schedules/create_exercise_list": create_exercise_list(params),
            "manage_schedules/create_schedule": create_schedule(params),
            "manage_schedules/delete_schedules": delete_schedules(params),
            "manage_schedules/delete_single_exercise": delete_single_exercise(params),
            "manage_schedules/delete_single_exercise_list": delete_single_exercise_list(params),
            "manage_schedules/delete_single_exercise_schedule": delete_single_schedule(params),
            "manage_user/create_user": create_user(params),
            "manage_user/update_user": update_user(params),
            "manage_user/delete_user": delete_user(params),
            "send_messages/create_message": create_message(params)
        }

        endpoints.get("/".join(uri), default())

    def application_logic_exe(self):
        # create a connection to the DB
        self.db_connect()

        # TODO: the loop
        while True:
            time.sleep(60)
