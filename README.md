# Google-Maps-Geocoding

Google Maps Geocoding Test

Тестовое задание 

Версия Laravel 5.8
Версия Vue 2.5.17

Сервис провайдер - GeocodeServiceProvider

\app\Library\Services\GeoCode.php - логика

Кэш записываеться в storage\framework\cache\data

Файлы формата .json сохряняються в storage\app

В метод geoData надо передать две переменные: $address(Адрес или координаты обьекта) и $lang(Язык вывода информации)
