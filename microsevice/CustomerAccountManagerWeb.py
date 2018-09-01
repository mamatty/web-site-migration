import json

import cherrypy

from microservices.customer_account_base import CustomerAccountManagerBase


class CustomAccountManagerMobileWev(CustomerAccountManagerBase):
    """
    ##### CUSTOMER ACCOUNT MANAGER (Web App) #####
    This microservice is used to expose the SMART GYM Application API for retrieve the
    information of the user. The consumer of this should be an application which has to
    user data for allowing user experiences.

    It's important to check if the app's token during the logging and the is-logged requests
    is included in the allowed_apps list (this is a form of security due to this service will be
    exposed with a public IP address).

    Also, during the is-logged and logging requests, a session is created through token granting
    which is needed to access all the other sensible endpoints exposed by the microservice.
    """

    def _set_config_settings(self, settings: dict) -> None:
        self.db_user = self.config_settings.get("db_user", None)
        self.db_password = self.config_settings.get("sd_password", None)
        self.db_ip_address = self.config_settings.get("db_ip_address", None)
        self.allowed_users = self.config_settings.get("allowed-users", [])

    def GET(self, *uri, **params):

        def look_updated_exercise(id_list, id_exercise):

            query = (
                f"SELECT id_exercise,day, details, weight, repetitions\n"
                f"FROM exercise_list\n"
                f"WHERE id_list = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query, [(id_list)], single_res=True)

            if response is None:
                return json.dumps({"status": "exercise-not-found"})
            else:

                query_name = (
                    f"SELECT name FROM exercise \n"
                    f"WHERE id_exercise =?"
                )

                response_name = self.db_perform_query(query_name, [(id_exercise)], single_res=True)

                if response_name is None:
                    return json.dumps({"status": "exercise_name-not-found"})
                else:
                    
                    exercise_json = {
                        "status": "found",
                        "id_user": response[0],
                        "day": response[1],
                        "details": response[2],
                        "weight": response[3],
                        "ripetitions": response[4],
                        "name": response_name[0]
                    }
                        
                return self.to_json(exercise_json)

        def look_updated_exercise_list(id_exercise):
            query = (
                f"SELECT name, description, muscular_zone, url \n"
                f"FROM exercise \n"
                f"WHERE id_exercise = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query, [(id_exercise)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                exercise_json = {
                    "status": "found",
                    "name": response[0],
                    "description": response[1],
                    "muscolar_zone": response[2],
                    "url": response[3],
                }
                return self.to_json(exercise_json)

        def autocomplete_user(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            surname = "%" + p.get("query", None) + "%"

            query = f"SELECT surname FROM user WHERE surname LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(surname)], single_res=False)

            if auto_complete is None:
                return json.dumps({"surname": "no-result"})
            else:
                auto_array = []

                for res in auto_complete:
                    auto_json = {
                            "surname": res[0]
                        }

                    auto_array.append(auto_json)

                return self.to_json(auto_json)

        def autocomplete_exercise(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            exercise = "%" + p.get("query", None) + "%"

            query = f"SELECT name FROM exercise WHERE name LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(exercise)], single_res=True)

            if auto_complete is None:
                return json.dumps({"exercise": "no-result"})
            else:
                auto_array = []

                for res in auto_complete:
                    auto_json = {
                            "exercise": res[0]
                        }

                    auto_array.append(auto_json)

                return self.to_json(auto_json)

        def autocomplete_message(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            title = "%" + p.get("query", None) + "%"

            query = f"SELECT title FROM messages WHERE title LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(title)], single_res=True)

            if auto_complete is None:
                return json.dumps({"message": "no-result"})
            else:
                auto_array = []

                for res in auto_complete:
                    auto_json = {
                            "message": res[0]
                        }

                auto_array.append(auto_json)

        # manage_schedules/manage_exercises.php
        def manage_exercises(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_list,id_exercise,day,repetitions,weight,details \n"
                f"FROM exercise_list \n"
                f"WHERE id_schedule = ? ORDER BY day DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(id, from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "exercise-not-found"})
            else:
                exercise_array = []

                for res in response:
                    query_name = f"SELECT name FROM exercise WHERE id_exercise = ?"
                    response_name = self.db_perform_query(query_name, [(res[1])], single_res=True)

                    if response_name is None:
                        return json.dumps({"status": "exercise_name-not-found"})
                    else:
                    
                        exercise_json = {
                            "id_list": res[0],
                            "id_exercise": res[1],
                            "day": res[2],
                            "repetitions": res[3],
                            "weight": res[4],
                            "details": res[5],
                            "name": res[0]
                        }

                        exercise_array.append(exercise_json)

                return self.to_json(exercise_array)

        def manage_schedules(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_schedule, name, details, start_date, end_date, num_days, objective \n"
                f"FROM schedules \n"
                f"WHERE id_user = ? ORDER BY end_date DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(id, from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                schedules_array = []
                for res in response:

                    schedules_json = {
                        "id_schedule": res[0],
                        "name": res[1],
                        "details": res[2],
                        "start_date": res[3],
                        "end_date": res[4],
                        "num_days": res[5],
                        "objective": res[6]                    
                    }
                    schedules_array.append(schedules_json)

                return self.to_json(schedules_array)

        def manage_users(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_user, name, surname, email \n"
                f"FROM user \n"
                f"ORDER BY id_user DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                
                user_array = []
                for res in response:

                    user_json = {
                        "id_user": res[0],
                        "name": res[1],
                        "surname": res[2],
                        "email": res[3]                        
                    }
                    user_array.append(user_json)

                return self.to_json(user_array)

        def account_profile():
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed() and not self.check_logged_user():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            user_id = self.logged_users[self.get_token_cookie()]
            query = (
                f'SELECT name, surname\n'
                f'FROM user\n'
                f'WHERE id_user = {user_id}'
            )

            profile = self.db_perform_query(query, single_res=True)
            if profile is None:
                #  TODO: find a smarter way to handle this
                raise cherrypy.HTTPError(500, "Internal error!")
                #return self.dumps("status":"not-found")
            else:
                user_json = {
                    "status":"found",
                    "name": profile[0],
                    "surname": profile[1],
                }
                return self.to_json(user_json)

        def read_exercises(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT * FROM exercise \n"
                f"LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                exercise_array = []
                for res in response: 

                    exercise_json = {
                        "id_exercise": res[0],
                        "name": res[1],
                        "description": res[2],
                        "muscolar_zone": res[3]
                    }
                    exercise_array.append(exercise_json)

                return self.to_json(exercise_array)

        def read_one_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)

            query = (
                f"SELECT name, description, muscolar_zone \n"
                f"FROM exercise \n"
                f"WHERE id_exercise = ? LIMIT 0,1"
            )
            response = self.db_perform_query(query, [(id)], single_res=True)
            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                exercise_json = {
                    "status": "found",
                    "name": response[0],
                    "description": response[1],
                    "muscolar_zone": response[2],
                }
                return self.to_json(exercise_json)

        def search_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            name = p.get("search", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_exercise, name, description, muscular_zone \n"
                f"FROM exercise \n"
                f"WHERE name = ? LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(name, from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                exercise_array = []
                for res in response:

                    exercise_json = {
                        "id_exercise": res[0],
                        "name": res[1],
                        "description": res[2],
                        "muscolar_zone": res[3]
                    }
                    exercise_array.append(exercise_json)

                return self.to_json(exercise_array)

        def search_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            surname = p.get("search", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_user, name, surname, email \n"
                f"FROM user \n"
                f"WHERE surname = ? ORDER BY id_user DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(surname, from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                user_array = []
                for res in response:

                    user_json = {
                        "id_user": res[0],
                        "name": res[1],
                        "surname": res[2],
                        "email": res[3]
                    }
                    user_array.append(user_json)

                return self.to_json(user_array)

        def update_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            name = p.get("search", None)
            day = p.get("day", None)
            ripetitions = p.get("ripetitions", None)
            weight = p.get("weight", None)
            detail = p.get("detail", None)

            query = f"SELECT id_exercise FROM exercise WHERE name = ?"
            response = self.db_perform_query(query, [(name)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                params = [(response[0], day, ripetitions, weight, detail, id)]
                query_update = (
                    f"UPDATE exercise_list SET id_exercise= ?,\n"
                    f"day=?, repetitions=?, weight=?, details=?\n"
                    f"WHERE id_list = ?"
                )
                response_update = self.db_perform_query(query_update, params, single_res=True)
                if response_update.row_count() > 0:
                    look_updated_exercise(id, response[0])
                else:
                    return json.dumps({"status": "not-updated"})

        def update_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            name = p.get("search", None)
            description = p.get("description", None)
            muscolar_zone = p.get("muscolar_zone", None)
            url = p.get("url", None)

            params = [(name, description, muscolar_zone, url, id)]
            query_update = (
                f"UPDATE exercise SET  name=?, description=?,\n"
                f"muscular_zone=?, url=?\n"
                f"WHERE id_exercise = ?"
            )
            response_update = self.db_perform_query(query_update, params, single_res=True)
            if response_update.row_count() > 0:
                look_updated_exercise_list(id)
            else:
                return json.dumps({"status": "not-updated"})

        def read_one_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)

            query = (
                f"SELECT id_user, name, surname, email, \n"
                f"birth_date, address, subscription, end_subscription \n"
                f"FROM user \n"
                f"WHERE id_user = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query,[(id)], single_res=True)

            if response is None:
               return json.dumps({"status": "not-found"})
            else:
                user_json = {
                    "status": "found",
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": response[4],
                    "address": response[5],
                    "subscription": response[6],
                    "end_subscription": response[7]
                }
                return self.to_json(user_json)

        def read_messages(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_message, title, send_date, destination \n"
                f"FROM messages \n"
                f"ORDER BY send_date DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query, [(from_record_num, records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                message_array = []
                for res in response:

                    message_json = {
                        "id_message": res[0],
                        "title": res[1],
                        "send_date": res[2],
                        "destination": res[3]
                    }
                    message_array.append(message_json)

                return self.to_json(message_array)

        def search_message(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
               
            title = p.get("search",None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_message, title, body,  send_date, destination \n"
                f"FROM messages \n"
                f"WHERE title = ? ORDER BY send_date DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query,[(title,from_record_num,records_per_page)], single_res=False)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                message_array = []
                for res in response:

                    message_json = {
                        "id_message": res[0],
                        "title": res[1],
                        "body": res[2],
                        "send_date": res[3],
                        "destination": res[4]
                    }
                    message_array.append(message_json)

                return self.to_json(message_array)

        def read_one_message(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)

            query = (
                f"SELECT id_message, title, \n"
                f"body, send_date, destination \n"
                f"FROM messages\n"
                f"WHERE id_message = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query, [(id)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                message_json = {
                    "status": "found",
                    "id_message": response[0],
                    "title": response[1],
                    "body": response[2],
                    "send_date": response[3],
                    "destination": response[4]
                }
                return self.to_json(message_json)

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
            "manage_schedules/look_updated_exercise": look_updated_exercise(params),
            "manage_schedules/look_updated_exercise_list": look_updated_exercise_list(params),
            "manage_user/autocomplete": autocomplete_user(params),
            "manage_user/read_one_user": read_one_user(params),
            "send_messages/autocomplete": autocomplete_message(params),
            "send_messages/read_messages": read_messages(params),
            "send_messages/read_one_message": read_one_message(params),
            "send_messages/search": search_message(params),
            "account/logout": account_logout(),
            "account/is_logged": is_logged(),
            "account/profile": account_profile()
        }

        endpoints.get("/".join(uri), default())

    def POST(self, *uri, **params):

        #  TODO: ask @MATTEO why so many log-stuff
        def logging(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            email = p.get("email", None)
            password = p.get("password", None)

            query = f"SELECT id_user,first_name, last_name FROM user WHERE email = ? and password = ?"

            user_id = self.db_perform_query(query, [(email, password)], single_res=True)
            if user_id is None:
                return json.dumps({"status": "not-found"})
            else:
                self.set_logged_user(user_id[0])
                user_json = {
                    "first_name": user_id[1],
                    "last_name": user_id[2]
                }
                return self.to_json(user_json)

        def register_account(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            # TODO: check if the user is already registered. Use the email as secondary key
            # TODO: need to provide the cookie
            email = p.get("email", None)
            first_name = p.get("first_name", None)
            last_name = p.get("last_name", None)
            password = p.get("password", None)

            control = self.db_perform_query(f"SELECT id_user FROM user WHERE email = ?",
                                            [(email)], single_res=True)

            # check if the user is already registered
            if control is None:
                return json.dumps({"status": "already-registered"})

            query = (
                f"INSERT INTO account (first_name, last_name, email, password) VALUES (?,?,?,?)")

            params = [( first_name, last_name, email, password)]

            registration = self.db_perform_query(query, params, single_res=True)
            if registration.row_count() > 0:
                user_id = self.db_perform_query(f"SELECT id_user FROM user WHERE email = ?", [(email)])
                self.set_logged_user(user_id)
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-registered"})

        def create_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            name = p.get("name", None)
            day = p.get("day", None)
            series = p.get("series", None)
            weight = p.get("weight", None)
            detail = p.get("detail", None)

            query = f"SELECT id_exercise FROM exercise WHERE name = ?"
            id_exercise = self.db_perform_query(query, [(name)], single_res=True)
            if id_exercise is None:
                return json.dumps({"status": "not-present"})
            else:
                query_ins = (
                    f"INSERT INTO exercise_list (id_schedule,id_exercise,day,repetitions,weight,details) \n"
                    f"VALUES (?,?,?,?,?,?)"
                )
                params = [(id, id_exercise[0], day, series, weight, detail)]
                ex = self.db_perform_query(query_ins, params, single_res=True)
                if ex.row_count() > 0:
                    return json.dumps({"status": "successful"})
                else:
                    return json.dumps({"status": "not-inserted"})

        def create_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            name = p.get("name", None)
            description = p.get("description", None)
            muscolar_zone = p.get("muscolar_zone", None)
            url = p.get("url", None)

            query = f"INSERT INTO exercise (name,description,muscolar_zone,url) VALUES (?,?,?,?)"
            params = [(name, description, muscolar_zone, url)]
            inseriment = self.db_perform_query(query, params, single_res=True)

            if inseriment.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-inserted"})

        def create_schedule(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            name = p.get("name", None)
            detail = p.get("detail", None)
            start_date = p.get("start_date", None)
            end_date = p.get("end_date", None)
            num_days = p.get("num_days", None)
            objective = p.get("objective", None)

            query = (
                f"INSERT INTO schedules (id_user,name,details,start_date,end_date,num_days,objective) \n"
                f"VALUES (?,?,?,?,?,?,?)"
            )
            params = [(id, name, detail, start_date, end_date, num_days, objective)]
            schedule = self.db_perform_query(query, params, single_res=True)

            if schedule.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-inserted"})

        def delete_schedules(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            query = f"DELETE FROM schedules WHERE id_user = ?"
            delete = self.db_perform_query(query, [(id)], single_res=True)

            if delete.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def delete_single_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            query = f"DELETE FROM exercise_list WHERE id_list = ?"
            delete = self.db_perform_query(query, [(id)], single_res=True)

            if delete.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def delete_single_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            query = f"DELETE FROM exercise WHERE id_exercise = ?"
            delete = self.db_perform_query(query, [(id)], single_res=True)

            if delete.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def delete_single_schedule(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            query_sc = f"DELETE FROM schedules WHERE id_schedule = ?"
            delete_sc = self.db_perform_query(query_sc, [(id)], single_res=True)

            query_ex = f"DELETE FROM exercise_list WHERE id_schedule = ?"
            delete_ex = self.db_perform_query(query_ex, [(id)], single_res=True)

            if delete_sc.row_count() > 0 and delete_ex.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def create_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            name = p.get("name", None)
            surname = p.get("surname", None)
            email = p.get("email", None)
            password = p.get("password", None)
            address = p.get("address", None)
            birth_date = p.get("birth_date", None)
            phone = p.get("phone", None)
            subscription = p.get("subscription", None)
            end_subscription = p.get("end_subscription", None)

            query_check = f"SELECT id_user FROM user WHERE email = ?"
            response_check = self.db_perform_query(query_check,[(email)], single_res=True)

            if response_check is None :
                return json.dumps({"status": "already-registered"})
             

            params = [(name,surname,email,password,address,birth_date,phone,subscription,end_subscription)]

            query = (
                f"INSERT INTO user (name, surname, email,\n"
                f"password, address, birth_date, phone, \n"
                f"subscription, end_subscription)\n"
                f"VALUES (?, ?, ?, ?, ?, ?, ?, ?,  ?)"
            )

            response = self.db_perform_query(query,params,single_res=True)

            if response.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:    
                return json.dumps({"status": "not-inserted"})

        def look_updated_user(id_user):

            query = (
                f"SELECT id_user, name, surname,\n"
                f"email, birth_date, address, subscription\n"
                f"FROM user\n"
                f"WHERE id_user = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query, [(id_user)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                user_json = {   
                    "status": "found",
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "birth_date": response[4],
                    "address": response[5],
                    "subscription": response[6]
                }
                return self.to_json(user_json)

        def update_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            name = p.get("search",None)
            surname = p.get("surname", None)
            email = p.get("email", None)
            birth_date = p.get("birth_date", None)
            address = p.get("address", None)
            subscription = p.get("subscription", None)
            end_subscription = p.get("end_subscription", None)

            params = [(name,surname,email,birth_date,address,subscription,end_subscription,id)]
            query_update = (
                f"UPDATE user SET name=?, surname=?,\n"
                f"email=?,birth_date=?, address=?,subscription=?, end_subscription=?\n"
                f"WHERE id_user = ?"
            ) 
            response_update = self.db_perform_query(query_update, params,single_res=True)
            if response_update.row_count() > 0:
                look_updated_user(id)
            else:
                return json.dumps({"status": "not-updated"})

        def delete_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            id = p.get("id", None)
            query = f"DELETE FROM user WHERE id_user = ?"
            delete = self.db_perform_query(query, [(id)], single_res=True)

            if delete.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-deleted"})

        def create_message(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            title = p.get("title", None)
            body = p.get("body", None)
            send_date = p.get("send_date", None)
            destination = p.get("destination", None)

            query = (
                f"INSERT INTO messages (title, body, send_date, destination)\n"
                f"VALUES (?,?,?,?)"
            )
            params = [(title, body, send_date, destination)]
            message = self.db_perform_query(query, params, single_res=True)

            if message.row_count() > 0:
                return json.dumps({"status": "successful"})
            else:
                return json.dumps({"status": "not-inserted"})

        def default():
            return cherrypy.HTTPError(400, "The URL is not valid")

        endpoints = {
            "login_website/login": logging(params),
            "login_website/register": register_account(params),
            "manage_schedules/create_exercise": create_exercise(params),
            "manage_schedules/create_exercise_list": create_exercise_list(params),
            "manage_schedules/create_schedule": create_schedule(params),
            "manage_schedules/delete_schedule": delete_schedules(params),
            "manage_schedules/delete_single_exercise": delete_single_exercise(params),
            "manage_schedules/delete_single_exercise_list": delete_single_exercise_list(params),
            "manage_schedules/delete_single_exercise_schedule": delete_single_schedule(params),
            "manage_user/create_user": create_user(params),
            "manage_user/update_user": update_user(params),
            "manage_user/look_updated_user": look_updated_user(params),
            "manage_user/delete_user": delete_user(params),
            "send_messages/create_message": create_message(params)
        }

        endpoints.get("/".join(uri), default())

    def application_logic_exe(self):
        # create a connection to the DB
        self.db_connect()