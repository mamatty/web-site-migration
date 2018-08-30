<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 19/03/2018
 * Time: 11:45
 */
/*
 define('DB_USERNAME','root');
 define('DB_PASSWORD','');
 define('DB_NAME','smartgym');
 define('DB_HOST','localhost');
 */

#Credentials
define('USERNAME','');
define('PASSWORD','');
define('IP_ADDRESS', '');

/**
 *
 */

#URLs
define('LOGIN','IP_ADDRESS/login_website/login');
define('REGISTER','IP_ADDRESS/login_website/register');
define('AUTOCOMPLETE_SCHEDULES','IP_ADDRESS/manage_schedules/autocomplete');
define('AUTOCOMPLETE_EXERCISE','IP_ADDRESS/manage_schedules/autocomplete_exercise');
define('MANAGE_SCHEDULES','IP_ADDRESS/manage_schedules/manage_schedules');
define('MANAGE_USERS','IP_ADDRESS/manage_schedules/manage_users');
define('MANAGE_EXERCISES','IP_ADDRESS/manage_schedules/manage_exercises');
define('READ_EXERCISES','IP_ADDRESS/manage_schedules/read_exercises');
define('READ_ONE_EXERCISE','IP_ADDRESS/manage_schedules/read_one_exercise');
define('SEARCH_USER','IP_ADDRESS/manage_schedules/search_user');
define('SEARCH_EXERCISE_LIST','IP_ADDRESS/manage_schedules/search_exercise_list');
define('UPDATE_EXERCISE','IP_ADDRESS/manage_schedules/update_exercise');
define('UPDATE_EXERCISE_LIST','IP_ADDRESS/manage_schedules/update_exercise_list');
define('LOOK_UPDATED_EXERCISE','IP_ADDRESS/manage_schedules/look_updated_exercise');
define('LOOK_UPDATED_EXERCISE_LIST','IP_ADDRESS/manage_schedules/look_updated_exercise_list');
define('CREATE_EXERCISE','IP_ADDRESS/manage_schedules/create_exercise');
define('CREATE_EXERCISE_LIST','IP_ADDRESS/manage_schedules/create_exercise_list');
define('CREATE_SCHEDULE','IP_ADDRESS/manage_schedules/create_schedule');
define('DELETE_SCHEDULE','IP_ADDRESS/manage_schedules/delete_schedule');
define('DELETE_SINGLE_EXERCISE','IP_ADDRESS/manage_schedules/delete_single_exercise');
define('DELETE_SINGLE_EXERCISE_LIST','IP_ADDRESS/manage_schedules/delete_single_exercise_list');
define('DELETE_SINGLE_EXERCISE_SCHEDULE','IP_ADDRESS/manage_schedules/delete_single_exercise_schedule');
define('AUTOCOMPLETE_USER','IP_ADDRESS/manage_user/autocomplete');
define('READ_ONE_USER','IP_ADDRESS/manage_user/read_one_user');
define('CREATE_USER','IP_ADDRESS/manage_user/create_user');
define('UPDATE_USER','IP_ADDRESS/manage_user/update_user');
define('LOOK_UPDATED_USER','IP_ADDRESS/manage_user/look_updated_user');
define('DELETE_USER','IP_ADDRESS/manage_user/delete_user');
define('AUTOCOMPLETE_MESSAGE','IP_ADDRESS/send_messages/autocomplete');
define('READ_MESSAGES','IP_ADDRESS/send_messages/read_messages');
define('READ_ONE_MESSAGE','IP_ADDRESS/send_messages/read_one_message');
define('SEARCH_MESSAGE','IP_ADDRESS/send_messages/search');
define('GET_ALL_TOKENS','IP_ADDRESS/send_messages/get_all_tokens');
define('CREATE_MESSAGE','IP_ADDRESS/send_messages/create_message');
define('LOGOUT','IP_ADDRESS/account/logout');
define('IS_LOGGED','IP_ADDRESS/account/is_logged');
define('PROFILE','IP_ADDRESS/account/profile');

//ThingSpeak request
define('THINGSPEAK','http://api.thingspeak.com/channels/565919/feed.json');

//defined a new constant for firebase api key
define('FIREBASE_API_KEY', 'AAAA-nqedSg:APA91bGsIWfBM52TvOfXwsLsG_yvHz-l2x2-d76PeADV5Ci5mINkbsYEVhCkSTMeOW3bwEyQ1C12wY5ebwyUFmAdYtFgHfdfkJZhsqDoTXjwqi3psfIPexsEy4P1iMy-UNQ8b1VZ4xiU');
