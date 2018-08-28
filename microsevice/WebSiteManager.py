def GET(self, *uri, **params):

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
                    "last_name": user_id[2],
                    "status": "successful"
                }
                return self.to_json(user_json)

        def autocomplete_user(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            surname = "%"+p.get("query", None)+"%"

            query = f"SELECT surname FROM user WHERE surname LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(surname)], single_res=True)

            if auto_complete is None:
                return json.dumps({"surname": "User Not Found"})
            else:
                return json.dumps({"surname": auto_complete[0]})

        def autocomplete_exercise(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            exercise = "%"+p.get("query", None)+"%"

            query = f"SELECT name FROM exercise WHERE name LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(exercise)], single_res=True)

            if auto_complete is None:
                return json.dumps({"exercise": "no result"})
            else:
                return json.dumps({"exercise": auto_complete[0]})

        def autocomplete_message(p):
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            title = "%"+p.get("query", None)+"%"

            query = f"SELECT title FROM messages WHERE title LIKE ? LIMIT 5"

            auto_complete = self.db_perform_query(query, [(title)], single_res=True)

            if auto_complete is None:
                return json.dumps({"exercise": "no result"})
            else:
                return json.dumps({"exercise": auto_complete[0]})


        def manage_exercises(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_list,id_exercise,day,ripetitions,weight,details \n"
                f"FROM exercise_list \n"
                f"WHERE id_schedule = ? ORDER BY day DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query,[(id,from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
                query_name = f"SELECT name FROM exercise WHERE id_exercise = ?"
                response_name = self.db_perform_query(query_name,[(response[1])], single_res=True)

                if response_name is None:
                    return json.dumps({"status": "not-found"})
                else:
                    query_tot = f"SELECT COUNT(*) FROM exercise_list WHERE id_schedule = ?"
                    total_record = self.db_perform_query(query_tot,[(id)],single_res=True)

                    exercise_json = {
                    "status": "found",
                    "id_list": response[0],
                    "id_exercise": response[1],
                    "day": response[2],
                    "ripetitions": response[3],
                    "weight": response[4],
                    "details": response[5],
                    "name": response_name[0],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

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

            response = self.db_perform_query(query,[(id,from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM schedules WHERE id_user = ?"
                total_record = self.db_perform_query(query_tot,[(id)],single_res=True)

                exercise_json = {
                    "status": "found",
                    "id_schedule": response[0],
                    "name": response[1],
                    "details": response[2],
                    "start_date": response[3],
                    "end_date": response[4],
                    "num_days": response[5],
                    "objective": response[6],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

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

            response = self.db_perform_query(query,[(from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM user"
                total_record = self.db_perform_query(query_tot,single_res=True)

                exercise_json = {
                    "status": "found",
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

        def account_profile(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed() and not self.check_logged_user():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            user_id = self.logged_users[cookies]
            query = (
                f'SELECT name, surname\n'
                f'FROM user\n'
                f'WHERE id_user = {user_id}'
            )

            profile = self.db_perform_query(query, single_res=True)
            if profile is None:
                #  TODO: find a smarter way to handle this
                raise cherrypy.HTTPError(500, "Internal error!")
            else:
                user_json = {
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

            response = self.db_perform_query(query,[(from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM exercise"
                total_record = self.db_perform_query(query_tot,single_res=True)

                exercise_json = {
                    "status": "found",
                    "id_exercise": response[0],
                    "name": response[1],
                    "description": response[2],
                    "muscolar_zone": response[3],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

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
            response = self.db_perform_query(query,[(id)],single_res=True)
            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                exercise_json = {
                    "status" = "found",
                    "name": response[0],
                    "description": response[1],
                    "muscolar_zone": response[2],
                }
                return self.to_json(exercise_json)

        def search_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            name = p.get("search",None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_exercise, name, description, muscolar_zone \n"
                f"FROM exercise \n"
                f"WHERE  name = ? LIMIT ?,?"
            )

            response = self.db_perform_query(query,[(name,from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM exercise WHERE name = ?"
                total_record = self.db_perform_query(query_tot,[(name)],single_res=True)

                exercise_json = {
                    "status":"found",
                    "id_exercise": response[0],
                    "name": response[1],
                    "description": response[2],
                    "muscolar_zone": response[3],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

        def search_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
               
            surname = p.get("search",None)
            records_per_page = p.get("records_per_page", None)
            from_record_num = p.get("from_record_num", None)

            query = (
                f"SELECT id_user, name, surname, email \n"
                f"FROM user \n"
                f"WHERE surname = ? ORDER BY id_user DESC LIMIT ?,?"
            )

            response = self.db_perform_query(query,[(surname,from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM user WHERE surname = ?"
                total_record = self.db_perform_query(query_tot,[(surname)],single_res=True)

                user_json = {
                    "status":"found",
                    "id_user": response[0],
                    "name": response[1],
                    "surname": response[2],
                    "email": response[3],
                    "total_rows": total_record
                }
                return self.to_json(user_json)

        def update_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            name = p.get("search",None)
            day = p.get("name", None)
            ripetitions = p.get("ripetitions", None)
            weight = p.get("weight", None)
            detail = p.get("detail", None)

            query = f"SELECT id_exercise FROM exercise WHERE name = ?"
            response = self.db_perform_query(query,[(name)],single_res=True)

            if response is None:
               return json.dumps({"status": "not-found"})
            else:
                params = [(response[0],day,ripetitions,weight,detail,id)]
                query_update = (
                    f"UPDATE exercise_list SET id_exercise= ?,\n"
                    f"day=?, ripetitions=?, weight=?, details=?\n"
                    f"WHERE id_list = ?"
                ) 
                response_update = self.db_perform_query(query_update, params,single_res=True)
                if response_update is None:
                    return json.dumps({"status": "not-updated"})
                else:
                    look_updated_exercise(id)

        def update_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            name = p.get("search",None)
            description = p.get("description", None)
            muscolar_zone = p.get("muscolar_zone", None)
            url = p.get("url", None)
            
            
                params = [(name,description,muscolar_zone,url, id)]
                query_update = (
                    f"UPDATE exercise SET  name=?, description=?,\n"
                    f"muscolar_zone=?, url=?\n"
                    f"WHERE id_exercise = ?"
                ) 
                response_update = self.db_perform_query(query_update, params,single_res=True)
                if response_update is None:
                    return json.dumps({"status": "not-updated"})
                else:
                    look_updated_exercise_list(id)

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
            if response_update is None:
                return json.dumps({"status": "not-found"})
            else:
                look_updated_user(id)

        def look_updated_exercise(id_list):
            
            query = (
                f"SELECT id_exercise,day, details, weight, ripetitions\n"
                f"FROM exercise_list\n"
                f"WHERE id_list = ? LIMIT 0,1"
                )

            response = self.db_perform_query(query,[(id_list)],single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:
               query_name = (
                f"SELECT name FROM exercise \n"
                f"WHERE id_exercise =?"
                )

                response_name = self.db_perform_query(query,[(response[0])],single_res=True)

                if response_name is None:
                    return json.dumps({"status": "not-found"})
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
                f"SELECT name, description, muscolar_zone, url \n"
                f"FROM exercise \n"
                f"WHERE id_exercise = ? LIMIT 0,1"
            )

            response = self.db_perform_query(query,[(id_exercise)],single_res=True)

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

        def look_updated_user(id_user):
            
            query = (
                f"SELECT id_user, name, surname,\n"
                f"email, birth_date, address, subscription\n"
                f"FROM user\n"
                f"WHERE id_user = ? LIMIT 0,1"
                )

            response = self.db_perform_query(query,[(id_user)],single_res=True)

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

        def read_one_user(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)

            query = (
                f"SELECT id_user, name, surname, email, \n"
                f"birth_date, address, image, subscription, end_subscription \n"
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
                    "image": response[6],
                    "subscription": response[7],
                    "end_subscription": response[8]
                }
                return self.to_json(user_json)

        def read_messages
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

            response = self.db_perform_query(query,[(from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM messages"
                total_record = self.db_perform_query(query_tot,single_res=True)

                exercise_json = {
                    "status": "found",
                    "id_message": response[0],
                    "title": response[1],
                    "send_date": response[2],
                    "destination": response[3],
                    "total_rows": total_record
                }
                return self.to_json(exercise_json)

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

            response = self.db_perform_query(query,[(id)], single_res=True)

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

            response = self.db_perform_query(query,[(title,from_record_num,records_per_page)], single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                query_tot = f"SELECT COUNT(*) FROM messages WHERE title = ?"
                total_record = self.db_perform_query(query_tot,[(title)],single_res=True)

                user_json = {
                    "status": "found",
                    "id_message": response[0],
                    "title": response[1],
                    "body": response[2],
                    "send_date": response[3],
                    "destination": response[4],
                    "total_rows": total_record
                }
                return self.to_json(user_json)

        def getAllTokens():
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
               
            query = f"SELECT token_firebase FROM user"

            response = self.db_perform_query(query, single_res=True)

            if response is None:
                return json.dumps({"status": "not-found"})
            else:    
                user_json = {
                    "status": "found",
                    "tokens": response
                }
                return self.to_json(user_json)

        def account_logout():
            self.logged_users.pop(self.get_token_cookie())
            return json.dumps({"status": "successful"})

def POST(self, *uri, **params):
        def is_logged():
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            return json.dumps({"logged": self.check_logged_user()})

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

            control = self.db_perform_query(f"SELECT id_user FROM account WHERE email = ?",
                                            [(email)], single_res=True)

            # check if the user is already registered
            if control is None:
                return json.dumps({"status": "already-registered"})

            query = (
                f"INSERT INTO account (first_name, last_name, email, password) VALUES (?,?,?,?)")

            params = [(email,first_name,last_name,password)]

            registration = self.db_perform_query(query, params, single_res=True)
            if registration is None:
                return json.dumps({"status": "not-registered"})
            else:
                user_id = self.db_perform_query(f"SELECT id_user FROM user WHERE email = ?", [(email)])
                self.set_logged_user(user_id)
                return json.dumps({"status": "successful"})

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
            id_exercise = self.db_perform_query(query,[(name)], single_res=True)
            if id_exercise id None:
                return json.dumps({"status": "not-present"})
            else:
                query_ins = (
                    f"INSERT INTO exercise_list (id_schedule,id_exercise,day,ripetitions,weight,details) \n"
                    f"VALUES (?,?,?,?,?,?)"
                )
                params = [(id,id_exercise[0],day,series,weight,detail)]
                ex = self.db_perform_query(query_ins,params, single_res=True)
                if ex is None:
                    return json.dumps({"status": "not-inserted"})
                else:
                    return json.dumps({"status": "successful"})

        def create_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")

            name = p.get("name", None)
            description = p.get("description", None)
            muscolar_zone = p.get("muscolar_zone", None)
            url = p.get("url", None)

            query = f"INSERT INTO exercise (name,description,muscolar_zone,url) VALUES (?,?,?,?)"
            params = [(name,description,muscolar_zone,url)]
            inseriment = self.db_perform_query(query,params,single_res=True)

            if inseriment is None:
                return json.dumps({"status": "not-inserted"})
            else:
                return json.dumps({"status": "successful"})

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
            params = [(id,name,detail,start_date,end_date,num_days,objective)]
            schedule = self.db_perform_query(query,params,single_res=True)

            if schedule is None:
                return json.dumps({"status": "not-inserted"})
            else:
                return json.dumps({"status": "successful"})

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
            params = [(title,body,send_date,destination)]
            message = self.db_perform_query(query,params,single_res=True)

            if message is None:
                return json.dumps({"status": "not-inserted"})
            else:
                return json.dumps({"status": "successful"})

        def delete_schedules(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            query = f"DELETE FROM schedules WHERE id_user = ?"
            delete = self.db_perform_query(query,[(id)],single_res=True)

            if delete is None:
                return json.dumps({"status": "not-deleted"})
            else:
                return json.dumps({"status": "successful"})

        def delete_single_exercise(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            query = f"DELETE FROM exercise_list WHERE id_list = ?"
            delete = self.db_perform_query(query,[(id)],single_res=True)

            if delete is None:
                return json.dumps({"status": "not-deleted"})
            else:
                return json.dumps({"status": "successful"})

        def delete_single_exercise_list(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            query = f"DELETE FROM exercise WHERE id_exercise = ?"
            delete = self.db_perform_query(query,[(id)],single_res=True)

            if delete is None:
                return json.dumps({"status": "not-deleted"})
            else:
                return json.dumps({"status": "successful"})
        
        def delete_single_schedule(p):
            # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            query_sc = f"DELETE FROM schedules WHERE id_schedule = ?"
            delete_sc = self.db_perform_query(query_sc,[(id)],single_res=True)

            query_ex = f"DELETE FROM exercise_list WHERE id_schedule = ?"
            delete_ex = self.db_perform_query(query_ex,[(id)],single_res=True)

            if delete_sc is None or delete_ex is None:
                return json.dumps({"status": "not-deleted"})
            else:    
                return json.dumps({"status": "successful"})
        
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
            image = p.get("image", None)
            subscription = p.get("subscription", None)
            end_subscription = p.get("end_subscription", None)

            query_check = f"SELECT id_user FROM user WHERE email = ?"
            response_check = self.db_perform_query(query_check,[(email)], single_res=True)

            if response_check is :
                return json.dumps({"status": "already-registered"})
             

            params = [(name,surname,email,password,address,birth_date,phone,image,subscription,end_subscription)]

            query = (
                f"INSERT INTO user (name, surname, email,\n"
                f"password, address, birth_date, phone, image,\n"
                f"subscription, end_subscription)\n"
                f"VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            )

            response = self.db_perform_query(query,params,single_res=True)

            if response is None:
                return json.dumps({"status": "not-inserted"})
            else:    
                return json.dumps({"status": "successful"})

        def delete_user(p):
             # check if the user and the application are allowed to access the service
            if not self.check_application_is_allowed():
                raise cherrypy.HTTPError(401, "Not allowed to access the service!")
            
            id = p.get("id", None)
            query = f"DELETE FROM user WHERE id_user = ?"
            delete = self.db_perform_query(query,[(id)],single_res=True)

            if delete is None:
                return json.dumps({"status": "not-deleted"})
            else:
                return json.dumps({"status": "successful"})