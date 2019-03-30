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

    public function all(array $params = [])
    {
        $params = array_merge([
            'key' => config('atlas.key', '123456789101112'),
            'out' => 'json'
        ], $params);

        $page = request('page');
        if ($page){
            $params['pge'] = $page;
        }

        \Log::debug('products with params: '.json_encode($params));

        $response = $this->client->get(
            "https://atlas.atdw-online.com.au/api/atlas/products", $params);

        return [
            'pagination' => [
                'per_page' => self::DEFAULT_PER_PAGE,
                'total'    => $response->numberOfResults ?? 0
            ],
            'items'      => array_map(function ($item) {
                return (array)$item;
            }, $response->products ?? [])
        ];

    }

    public function find($productId)
    {
        $params = [
            'key' => config('atlas.key', '123456789101112'),
            'productId' => $productId,
            'out' => 'json',
        ];

        \Log::debug('product with id: '.$productId);

        $response = $this->client->get(
            "https://atlas.atdw-online.com.au/api/atlas/product", $params);

        return $response;

    }


}