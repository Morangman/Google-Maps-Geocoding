<?php
namespace App\Library\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GeoCode
{
    public function geoData($address, $lang)
    {

        /*General request to Google Maps Geocoding API*/
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/geocode/']);

        /*The format of the response received*/
        $json = 'json?';

        /*Google API KEY*/
        $key = 'AIzaSyCPpwBYN1iXCGHyONVfLB71lPc8LbpMFsI';

        /*Query Template with Parameters*/
        $uri = $json . 'address=' . $address . '&language=' . $lang . '&key=' . $key;

        if (($address) == true) {

            /*Receiving and processing a response from Google Maps Geocoding API*/
            $geodate = \GuzzleHttp\json_decode($client->request('GET', $uri)->getBody()->getContents());
            if ($geodate->status == 'OK') {
                $data = $geodate->results[0]; //Display the main information about the object in view

                $name = md5($address);
                $disk = Storage::disk('local');
                if (!Storage::disk('local')->exists($name . '.json')) {
                    Storage::put(str_replace(' ', '_', $this->transcript($name)) . '.json', json_encode($geodate));
                }

                Cache::put($address, $data, 50000);

                if (Cache::has($address)) {
                    $data = Cache::get($address);
                    return $data;
                } else {
                    return $data;
                }
            } else {
                $data = "Вы не ввели запрос или ввели несуществующий адрес";
                return response()->json([
                    'error' => $data
                ]);
            }
        }
    }

    public function transcript($st){
        $translit = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'j',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'x',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'\'',
            'э' => 'e\'',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'J',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',
            'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',
            'Ь' => '\'',  'Ы' => 'Y\'',   'Ъ' => '\'\'',
            'Э' => 'E\'',   'Ю' => 'YU',  'Я' => 'YA',
        );
        $str = strtr($st, $translit);
        return $str;
    }
}