<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class GeoCoderController extends Controller
{
    public function geoData(Request $request)
    {
        $address = $request->input('geodata');
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/geocode/']);
        $lang = $request->input('leng');
        $json = 'json?';
        $uri = $json . 'address=' . $address . '&language='.$lang.'&key=AIzaSyCPpwBYN1iXCGHyONVfLB71lPc8LbpMFsI';

        if ($address == true) {
            $geodate = \GuzzleHttp\json_decode($client->request('GET', $uri)->getBody()->getContents());
            $data = $geodate->results[0];
            
            $disk = Storage::disk('local');
            if (!Storage::disk('local')->exists($address.'.json')){
                Storage::put(str_replace(' ','_', $this->transcript($address)).'.json', json_encode($geodate));
            }

            Cache::put($address, $data, 5000000);

            if (Cache::has($address)){
                $data = Cache::get($address);
                return response()->json([
                    'error' => null,
                    'data' => $data
                ]);
            }else{
                return response()->json([
                    'error' => null,
                    'data' => $data
                ]);
            }
        }else{
            $data = "Вы не ввели Запрос";
            return response()->json([
                'error' => true,
                'data'  => $data
            ]);
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
