<?php
/**
 * Created by PhpStorm.
 * User: pogho
 * Date: 8/31/2019
 * Time: 1:22 AM
 */

namespace App\Services\SmsSender;


use GuzzleHttp\Client;

class SmsSender
{
    public function send($phoneNumber, $message)
    {
        $client = new Client();
        $url = env('KAZINFOTEH_URL');

        $data = [
            'action' => 'sendmessage',
            'username' => env('KAZINFOTEH_LOGIN'),
            'password' => env('KAZINFOTEH_PASSWORD'),
            'recipient' => $phoneNumber,
            'messagetype' => 'SMS:TEXT',
            'originator' => 'INFO_KAZ',
            'messagedata' => $message,
        ];

        $response = $client->post($url, [
            'form_params' => $data
        ]);

        return $response->getStatusCode() == 200;
    }

}
