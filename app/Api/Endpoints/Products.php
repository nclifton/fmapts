<?php


namespace App\Api\Endpoints;

use App\Api\Client;

class Products implements ApiInterface
{
    /**
     * The client.
     *
     * @var Client
     */
    protected $client;

    const DEFAULT_PER_PAGE = 10;

    /**
     * Search constructor.
     * @param Client $param
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

    }

    public function all(array $params=[])
    {
        // todo 'get' all matching results from the api

        $key = config('atlas.key','123456789101112');
        $response = $this->client->get("https://atlas.atdw-online.com.au/api/atlas/products?key=$key&cla=APARTMENT&term=views&out=json");

        return [
            'pagination'=>[
                'per_page'=> self::DEFAULT_PER_PAGE,
                'total' => $response->numberOfResults
            ],
            'items'=>array_map(function($item){return (array)$item;},$response->products)
        ];

    }


}