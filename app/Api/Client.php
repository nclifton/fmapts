<?php


namespace App\Api;


use App\Api\Endpoints\ApiInterface;
use BadMethodCallException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\MessageBag;
use InvalidArgumentException;
use App\Api\Endpoints;

class Client
{
    /**
     * The HTTP client instance used to communicate with API.
     *
     * @var HttpClient
     */
    private $httpClient;
    /**
     * @var MessageBag
     */
    private $errors;

    /**
     * Instantiate a new client that accepts json.
     * @param HttpClient|null $httpClient
     */
    public function __construct(HttpClient $httpClient=null)
    {
        $this->httpClient = $httpClient ?? new HttpClient(
            [
                'headers' => [
                    'Accept'     => 'application/json'
                ]
            ]
            );
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        switch ($name)
        {
            case 'products':
                $api = new Endpoints\Products($this);
                break;

//todo define the other endpoints we'll need here

            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function __call($name, $args)
    {
        try {

            // return the endpoint for the model
            return $this->api($name);
        }
        catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }

    public function get($uri)
    {
        try {
            $response = $this->httpClient->request('GET',$uri,[
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);
            $status = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = mb_convert_encoding($body,'UTF-8','utf-16le');

            $array = json_decode($body);
            return $array;
        } catch (RequestException $e) {

            $this->errors = new MessageBag();
            $this->errors->add('http',$e->getMessage());

        }
    }

    public function errors()
    {
        return $this->errors;
    }

}