<?php

namespace NotificationChannels\Whatsapp;

use GuzzleHttp\Client as HttpClient;

class Whatsapp
{

    const API_HOST = 'https://graph.facebook.com';

    /**
     * @var HttpClient HTTP Client
     */
    protected $http;

    /**
     * Whatsapp API version (e.g., '22.0')
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * Access token for authenticating with the Whatsapp API.
     *
     * @var string
     */
    protected $accessToken;

    public function __construct(array $config = [], HttpClient $httpClient = null)
    {
        $this->http = $httpClient;
        $this->apiVersion = Arr::get($config, 'apiVersion');
        $this->accessToken = Arr::get($config, 'accessToken');
    }

}
