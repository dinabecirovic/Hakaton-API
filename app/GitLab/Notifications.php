<?php


namespace App\GitLab;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Notifications
{

    /**
     * @param $impersonation_token
     * @param $level
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public static function update($impersonation_token, $level)
    {

        $client = new Client();

        try{

            $r = $client->put(config("gitlab.connections.main.url") . "/api/v4/notification_settings", [
                'headers' => [
                    'PRIVATE-TOKEN' => $impersonation_token,
                ],
                'json' => [
                    'level' => $level
                ]
            ]);

        }
        catch (\Exception $e){

            Log::error("Error trying to disable notifications for user...", [
                'error' => $e,
            ]);

            return false;

        }

    }


}
