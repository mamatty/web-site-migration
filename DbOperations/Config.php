<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 19/03/2018
 * Time: 11:45
 */


#Credential
define('APP_ID','QOqMXxfzVhVL0lq4iJ2uK1k7O');
define('IP_ADDRESS', '127.0.0.1');
define('PORT',9090);
define('URL', 'http://'.IP_ADDRESS.':'.PORT);

#URLs
define('LOGIN',URL.'/login_website/login');
define('REGISTER',URL.'/login_website/register');
define('AUTOCOMPLETE_SCHEDULES',URL.'/manage_schedules/autocomplete');
define('AUTOCOMPLETE_EXERCISE',URL.'/manage_schedules/autocomplete_exercise');
define('MANAGE_SCHEDULES',URL.'/manage_schedules/manage_schedules');
define('MANAGE_USERS',URL.'/manage_schedules/manage_users');
define('MANAGE_EXERCISES',URL.'/manage_schedules/manage_exercises');
define('READ_EXERCISES',URL.'/manage_schedules/read_exercises');
define('READ_ONE_EXERCISE',URL.'/manage_schedules/read_one_exercise');
define('SEARCH_USER',URL.'/manage_schedules/search_user');
define('SEARCH_EXERCISE_LIST',URL.'/manage_schedules/search_exercise_list');
define('UPDATE_EXERCISE',URL.'/manage_schedules/update_exercise');
define('UPDATE_EXERCISE_LIST',URL.'/manage_schedules/update_exercise_list');
define('LOOK_UPDATED_EXERCISE',URL.'/manage_schedules/look_updated_exercise');
define('LOOK_UPDATED_EXERCISE_LIST',URL.'/manage_schedules/look_updated_exercise_list');
define('CREATE_EXERCISE',URL.'/manage_schedules/create_exercise');
define('CREATE_EXERCISE_LIST',URL.'/manage_schedules/create_exercise_list');
define('CREATE_SCHEDULE',URL.'/manage_schedules/create_schedule');
define('DELETE_SCHEDULES',URL.'/manage_schedules/delete_schedules');
define('DELETE_SINGLE_EXERCISE',URL.'/manage_schedules/delete_single_exercise');
define('DELETE_SINGLE_EXERCISE_LIST',URL.'/manage_schedules/delete_single_exercise_list');
define('DELETE_SINGLE_EXERCISE_SCHEDULE',URL.'/manage_schedules/delete_single_exercise_schedule');
define('AUTOCOMPLETE_USER',URL.'/manage_user/autocomplete');
define('READ_ONE_USER',URL.'/manage_user/read_one_user');
define('CREATE_USER',URL.'/manage_user/create_user');
define('UPDATE_USER',URL.'/manage_user/update_user');
define('LOOK_UPDATED_USER',URL.'/manage_user/look_updated_user');
define('DELETE_USER',URL.'/manage_user/delete_user');
define('AUTOCOMPLETE_MESSAGE',URL.'/send_messages/autocomplete');
define('READ_MESSAGES',URL.'/send_messages/read_messages');
define('READ_ONE_MESSAGE',URL.'/send_messages/read_one_message');
define('SEARCH_MESSAGE',URL.'/send_messages/search');
define('GET_ALL_TOKENS',URL.'/send_messages/get_all_tokens');
define('CREATE_MESSAGE',URL.'/send_messages/create_message');
define('LOGOUT',URL.'/account/logout');
define('IS_LOGGED',URL.'/account/is_logged');
define('PROFILE',URL.'/account/profile');

//ThingSpeak request
define('THINGSPEAK','http://api.thingspeak.com/channels/565919/feed.json');

//defined a new constant for firebase api key
define('FIREBASE_API_KEY', 'AAAAyhqO-H8:APA91bHIvhjSR5mLdNKw6f1AFJkm-qH-gRFXVvo9N77KmnoPfMo2cFVmsAFTsQGgvgzz0V6en-Ee6b4luoYbQUqi47NVhBoZcmz0oIC02l7OkQ7z12mY04V4haPEhllTUIeyWyXyPhfR');
#AAAA-GfsNEY:APA91bEoMzRpctgZ4Hu7iFI5Wic70Ufqb2GfDs0qwG3z8hTuBMaRKru2_BfvvNfR9yci-Rj24aAH2CMNfYVV7EkHL9jQUd50L79E_G3rSziGdLE2Z_mafgdhhe6gYAkTcWamFrIIqX4n