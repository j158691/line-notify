<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class LineNotifyService
{
    /**
     * @var Client
     */
    protected $client;
    protected $notifyClientId;
    protected $notifyClientSecret;
    /**
     * @var bool
     */
    protected $sslVerify;

    public function __construct(
        Client $client
    ) {
        $this->client             = $client;
        $this->client             = $client;
        $this->notifyClientId     = env('LINE_NOTIFY_CLIENT_ID');
        $this->notifyClientSecret = env('LINE_NOTIFY_CLIENT_SECRET');
        $this->sslVerify          = (env('APP_ENV', '') == 'local') ? false : true;
    }

    /**
     * @return string
     */
    public function getAuthorizeCodeUrl()
    {
        $uri     = 'https://notify-bot.line.me/oauth/authorize?';
        $options = [
            'response_type' => 'code',
            'client_id'     => $this->notifyClientId,
            'redirect_uri'  => env('APP_URL', '').'/token',
            'scope'         => 'notify',
            'state'         => Str::random(12),
            'code'          => Str::random(12),
        ];

        return $uri.http_build_query($options);
    }

    /**
     * @param $code
     * @return array
     * @throws GuzzleException
     */
    public function postOauthToken($code)
    {
        $method  = 'POST';
        $uri     = 'https://notify-bot.line.me/oauth/token';
        $options = [
            'headers'     => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'redirect_uri'  => env('APP_URL', '').'/token',
                'client_id'     => $this->notifyClientId,
                'client_secret' => $this->notifyClientSecret,
            ],
            'verify'      => $this->sslVerify,
        ];

        $curl = $this->client->request($method, $uri, $options);
        $curl = $curl->getBody()->getContents();
        $result = json_decode($curl, true);

        return [
            'status'       => $result['status'],
            'message'      => $result['message'],
            'access_token' => $result['access_token'],
        ];
    }

    /**
     * @param $accessToken
     * @throws GuzzleException
     */
    public function postRevoke($accessToken)
    {
        $method  = 'POST';
        $uri     = 'https://notify-bot.line.me/oauth/token';
        $options = [
            'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer '.$accessToken,
            ],
            'verify'      => $this->sslVerify,
        ];

        $curl = $this->client->request($method, $uri, $options);
        $curl = $curl->getBody()->getContents();
        $result = json_decode($curl, true);
    }

    /**
     * @param $message
     * @param $accessToken
     * @throws GuzzleException
     */
    public function postNotify($message, $accessToken)
    {
        $method  = 'POST';
        $uri     = 'https://notify-api.line.me/api/notify';
        $options = [
            'headers'     => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer '.$accessToken,
            ],
            'form_params' => [
                'message' => $message,
                'imageThumbnail' => 'https://upload.cc/i1/2023/03/15/DH8e5V.png',
                'imageFullsize' => 'https://upload.cc/i1/2023/03/15/DH8e5V.png'
                ],
            'verify'      => $this->sslVerify,
        ];

        $curl = $this->client->request($method, $uri, $options);
        $curl = $curl->getBody()->getContents();
        $result = json_decode($curl, true);
    }
}
