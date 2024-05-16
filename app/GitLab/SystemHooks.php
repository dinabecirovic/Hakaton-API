<?php


namespace App\GitLab;


use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SystemHooks
{

    /**
     * @return bool|\Psr\Http\Message\ResponseInterface
     */
    public static function all()
    {

        $client = new Client();

        try{

            $r = $client->get(config("gitlab.connections.main.url") . "/api/v4/hooks", [
                'headers' => [
                    'PRIVATE-TOKEN' => config("gitlab.connections.main.token"),
                ],
            ]);

            return json_decode($r->getBody()->getContents());

        }
        catch (\Exception $e){

            Log::error("Error trying to get system hooks...", [
                'error' => $e,
            ]);

            return false;

        }

    }


    /**
     * @param $impersonation_token
     * @param $level
     * @return array|string
     * @throws \Http\Client\Exception
     */
    public static function add($data)
    {

        $client = new Client();

        try{

            $r = $client->post(config("gitlab.connections.main.url") . "/api/v4/hooks", [
                'headers' => [
                    'PRIVATE-TOKEN' => config("gitlab.connections.main.token"),
                ],
                'json' => $data
            ]);

            return $r->getStatusCode() == Response::HTTP_OK
                || $r->getStatusCode() == Response::HTTP_CREATED
                || $r->getStatusCode() == Response::HTTP_ACCEPTED;

        }
        catch (\Exception $e){

            Log::error("Error trying to add system hook...", [
                'error' => $e,
            ]);

            return false;

        }

    }


}
