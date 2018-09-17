import telepot
import json
import requests
import numpy
import time

from microservices.microservice import Microservice

class TelegramBot(Microservice):

    def __init__(self, waiting: int, config_path: str):
        super().__init__(waiting, config_path)

    def _set_config_settings(self, settings: dict) -> None:
        try:
            self.token = settings['config_settings']["ThgSpk-Token"]
            self.channel_id = settings['config_settings']["ThgSpk-Channel-ID"]
            self.api_key = settings['config_settings']["ThgSpk-Api-Key"]
        except KeyError:
            print('Bad microservice configuration: something went wrong when contacting the catalog')
            raise

    def retrieve_data(self, msg):

        content_type, chat_type, chat_id = telepot.glance(msg)

        # Getting data from ThingSpeak
        url_get = "http://api.thingspeak.com/channels/{}/feed.json".format(self.channel_id)
        headers_get = {
            'host': 'api.thingspeak.com',
            'Connection': 'close'
        }

        data = 'api_key={}'.format(self.api_key)

        try:
            r_get = requests.get(url_get, headers=headers_get, params=data)
            temp = []
            humidity = []

            if r_get.status_code == 200:
                wjson = r_get.json()

                for res in wjson['feeds']:

                    if res['field1'] is not None:
                        date = res['created_at'].split('T')[0]

                        results_tm = {
                            'value': res['field1'],
                            'date': date
                        }

                        temp.append(results_tm)

                    if res['field2'] is not None:
                        date = res['created_at'].split('T')[0]

                        results_tm = {
                            'value': res['field2'],
                            'date': date
                        }

                        humidity.append(results_tm)

                return temp, humidity

            else:
                self.bot.sendMessage(chat_id, 'Error on GET request!')

        except requests.exceptions.RequestException:
            self.bot.sendMessage(chat_id, 'Problem in ThingSpeak Connection!')

    def mean_values(self, data):

        date = time.strftime("%Y-%m-%d")
        sum = 0
        count = 0

        for x in data:
            if x['date'] == date:
                sum = sum + int(x['value'])
                count = count + 1

        try:
            return sum/count
        except ZeroDivisionError:
            return 0

    def on_chat_message(self, msg):

        content_type, chat_type, chat_id = telepot.glance(msg)

        if content_type == 'text' and msg['text'][0] == '/':

            [temp, humidity] = self.retrieve_data(msg)
            date = time.strftime("%Y-%m-%d")

            if msg['text'] == '/start':
                self.bot.sendMessage(chat_id, 'Welcome to the SmartGym Telegram Bot! Description above.')

            elif msg['text'] == '/get_temperature':
                try:
                    if temp[-1]['value'] is not None and temp[-1]['date'] == date:
                        self.bot.sendMessage(chat_id, 'The actual temperature is: '+str(temp[-1]['value'])+' °C')
                        return
                    else:
                        self.bot.sendMessage(chat_id, 'No temperature measurement are available!')
                        return

                except Exception:
                    self.bot.sendMessage(chat_id, "Error in retrieving temperature")
                    return

            elif msg['text'] == '/get_humidity':
                try:
                    if humidity[-1]['value'] is not None and humidity[-1]['date'] == date :
                        self.bot.sendMessage(chat_id, 'The actual humidity is: '+str(humidity[-1]['value'])+' g/m³')
                        return
                    else:
                        self.bot.sendMessage(chat_id, 'No humidity measurement are available!')
                        return

                except Exception:
                    self.bot.sendMessage(chat_id, "Error in retrieving humidity")
                    return

            elif msg['text'] == '/get_today_temperature':
                try:
                    mean_temp = self.mean_values(temp)
                    if mean_temp != 0:
                        self.bot.sendMessage(chat_id, 'Today '+str(date)+' the mean temperature is: '+str(mean_temp)+' °C')
                        return
                    else:
                        self.bot.sendMessage(chat_id, 'No temperature measurement are available!')
                        return

                except Exception:
                    self.bot.sendMessage(chat_id, "Error in retrieving temperature")
                    return

            elif msg['text'] == '/get_today_humidity':
                try:
                    mean_hum = self.mean_values(temp)
                    if mean_hum != 0:
                        self.bot.sendMessage(chat_id, 'Today ' + str(date) + ' the mean humidity is: ' + str(mean_hum)+' g/m³')
                        return
                    else:
                        self.bot.sendMessage(chat_id, 'No humidity measurement are available!')
                        return

                except Exception:
                    self.bot.sendMessage(chat_id, "Error in retrieving humidity")
                    return

            elif msg['text'] == '/help':
                self.bot.sendMessage(chat_id, 'List of available commands:\n'
                                '/get_temperature - retrieve the actual temperature inside the gym \n'
                                '/get_humidity - retrieve the actual humidity inside the gym \n'
                                '/get_today_temperature - retrieve the mean value of the temperature of the current day \n'
                                '/get_today_humidity - retrieve the mean value of the humidity of the current day \n')

            else:
                self.bot.sendMessage(chat_id, 'Command not found! See /help for the list of available commands')
                return

        else:
            self.bot.sendMessage(chat_id, 'Command not valid! See /help for the list of available commands')
            return

    def application_logic_exe(self):

        if self.token is not None:
            self.bot = telepot.Bot(self.token)
            print("Correct Bot setup")
        else:
            print("No correct parameter")
            exit()

        self.bot.message_loop(self.on_chat_message)

        print('Listening ...')


if __name__ == '__main__':
    from smartgym_packages.core.launcher import Launcher

    CONF = 'config_files/telegram_bot.conf'

    l = Launcher()
    l.start_catalog()
    l.start('CAM_web', TelegramBot.start_ms_normal, args=(10, CONF))
