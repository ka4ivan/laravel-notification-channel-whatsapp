<?php

namespace NotificationChannels\Whatsapp;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Arr;
use NotificationChannels\Whatsapp\Exceptions\CouldNotSendNotification;

class Whatsapp
{

    const API_HOST = 'https://graph.facebook.com';

    /**
     * @var HttpClient HTTP Client
     */
    protected $http;

    /**
     * Access token for authenticating with the Whatsapp API.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Sender's Number ID
     *
     * @var string
     */
    protected $numberId;

    /**
     * Whatsapp API version (e.g., '22.0')
     *
     * @var string
     */
    protected $apiVersion;

    public function __construct(array $config = [], HttpClient $httpClient = null)
    {
        $this->http = $httpClient;
        $this->accessToken = Arr::get($config, 'accessToken');
        $this->numberId = Arr::get($config, 'numberId');
        $this->apiVersion = Arr::get($config, 'apiVersion');
    }

    /**
     * Get HttpClient.
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Set the access token used for authenticating API requests.
     *
     * @param string|null $accessToken Whatsapp access token.
     * @return $this
     */
    public function setAccessToken(string $accessToken = null): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Set the sender`s number ID.
     *
     * @param string|null $numberId
     * @return $this
     */
    public function setNumberId(string $numberId = null): self
    {
        $this->numberId = $numberId;

        return $this;
    }

    /**
     * Set Default Graph API Version.
     *
     * @param string|null $apiVersion
     * @return Whatsapp
     */
    public function setApiVersion(string $apiVersion = null): self
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * Send text message.
     *
     * @throws GuzzleException
     * @throws CouldNotSendNotification
     */
    public function send(array $params): ResponseInterface
    {
        return $this->post("{$this->numberId}/messages", $params);
    }

    /**
     * @throws GuzzleException
     * @throws CouldNotSendNotification
     */
    public function get(string $endpoint, array $params = []): ResponseInterface
    {
        return $this->api($endpoint, ['query' => $params]);
    }

    /**
     * @throws GuzzleException
     * @throws CouldNotSendNotification
     */
    public function post(string $endpoint, array $params = []): ResponseInterface
    {
        return $this->api($endpoint, ['json' => $params], 'POST');
    }

    /**
     * Send an API request and return response.
     *
     * @param string $endpoint
     * @param array $options
     * @param string $method
     *
     * @return mixed|ResponseInterface
     * @throws CouldNotSendNotification
     */
    protected function api(string $endpoint, array $options, string $method = 'GET')
    {
        if (
            empty($this->apiVersion) ||
            empty($this->accessToken) ||
            empty($this->numberId)
        ) {
            throw CouldNotSendNotification::whatsappTokenNotProvided('You must provide your Whatsapp tokens to make any API requests.');
        }

        $url = self::API_HOST . "/v{$this->apiVersion}/{$endpoint}";

        $options['headers']['Authorization'] = "Bearer {$this->accessToken}";
        $options['headers']['Accept'] = 'application/json';

        try {
            return $this->httpClient()->request($method, $url, $options);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::whatsappRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithWhatsapp($exception);
        }
    }
}
