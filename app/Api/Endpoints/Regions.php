<?php

namespace App\Api\Endpoints;

use App\Api\Client;

class Regions implements ApiInterface
{
    /**
     * The client.
     *
     * @var Client
     */
    protected $client;


    /**
     * Search constructor.
     * @param Client $param
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

    }

    public function all(array $params = [])
    {
        $params = array_merge([
            'key' => config('atlas.key', '123456789101112'),
            'out' => 'json'
        ], $params);

        $response = $this->client->get(
            "https://atlas.atdw-online.com.au/api/atlas/regions", $params);

        return [
            'pagination' => [
                'per_page' => count($response),
                'total'    => count($response)
            ],
            'items' => array_map(
                function ($item) {
                    return (array)$item;
                }, $response ?? [])
        ];
    }


}