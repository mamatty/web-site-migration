import requests

#Posting data on ThingSpeak
url_post = "http://api.thingspeak.com/update.json"
headers_post = {
    'host': 'api.thingspeak.com',
    'Connection': 'close',
    'Content-Type': 'application/x-www-form-urlencoded'
}
#questi sono valori statici. Passare qua i valori ottenuti dalla rasp
temp = 15
humidity = 7
data = 'api_key=DNBQVGCP1QCJVQ6T&field1='+str(temp)+'&field2='+str(humidity)

r_post = requests.post(url_post, headers=headers_post, params=data)

if r_post.status_code == 200:
    print('Good Request')

    #Getting data from ThingSpeak
    url_get = "http://api.thingspeak.com/channels/565919/feed.json"
    headers_get = {
        'host': 'api.thingspeak.com',
        'Connection': 'close'
    }

    data = 'api_key=0FUJJDZFVYDH2PLB'

    r_get = requests.get(url_get, headers=headers_get, params=data)

    if r_get.status_code == 200:
        wjson = r_get.json()
        for res in wjson['feeds']:
            print('Temp: '+str(res['field1'])+' & Humidity: '+str(res['field2']))
    else:
        print('Error on GET request!')
else:
    print('Error on POST request!')