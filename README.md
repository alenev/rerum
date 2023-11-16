Запуск на локалі:
- composer install
- php artisan serve 

http://127.0.0.1:8000/documents

HowTo: https://drive.google.com/file/d/1hYi1tZ-iM-h92XPj5_x1ZHZ50vOtbu6m/view?usp=sharing


ТЗ:
Имеется набор документов, представленных в виде массива одноуровневых JSON. Массив хранится в файле. 
Требуется:
по набору документов и заданному полю сформировать бинарное сбалансированное дерево поиска (поисковый индекс); в том случае, если в документе поле не представлено, он в индекс не попадает
сохранить индекс в отдельный файл в любом формате на ваше усмотрение;
реализовать поиск по набору документов как с использованием индекса, так и без него (последовательным перебором); условие поиска - строгое соответствие; поиск должен выводить найденные документы (если таковые есть), а также количество операций сравнения, которые понадобились для их обнаружения

Интерфейс пользователя (GUI / CLI) - на усмотрение выполняющего. 

Пример файла с исходными данными:

[
{"name":"Aachen","id":"1","nametype":"Valid","recclass":"L5","mass":"21","fall":"Fell","year":"1880-01-01T00:00:00.000","reclat":"50.775000","reclong":"6.083330","geolocation":{"type":"Point","coordinates":[6.08333,50.775]}}
,{"name":"Aarhus","id":"2","nametype":"Valid","recclass":"H6","mass":"720","fall":"Fell","year":"1951-01-01T00:00:00.000","reclat":"56.183330","reclong":"10.233330","geolocation":{"type":"Point","coordinates":[10.23333,56.18333]}}
,{"name":"Abee","id":"6","nametype":"Valid","recclass":"EH4","mass":"107000","fall":"Fell","year":"1952-01-01T00:00:00.000","reclat":"54.216670","reclong":"-113.000000","geolocation":{"type":"Point","coordinates":[-113,54.21667]}}
,{"name":"Acapulco","id":"10","nametype":"Valid","recclass":"Acapulcoite","mass":"1914","fall":"Fell","year":"1976-01-01T00:00:00.000","reclat":"16.883330","reclong":"-99.900000","geolocation":{"type":"Point","coordinates":[-99.9,16.88333]}}
,{"name":"Achiras","id":"370","nametype":"Valid","recclass":"L6","mass":"780","fall":"Fell","year":"1902-01-01T00:00:00.000","reclat":"-33.166670","reclong":"-64.950000","geolocation":{"type":"Point","coordinates":[-64.95,-33.16667]}}
,{"name":"Adhi Kot","id":"379","nametype":"Valid","recclass":"EH4","mass":"4239","fall":"Fell","year":"1919-01-01T00:00:00.000","reclat":"32.100000","reclong":"71.800000","geolocation":{"type":"Point","coordinates":[71.8,32.1]}}
]


