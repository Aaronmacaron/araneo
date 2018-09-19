<?php

namespace App\Jobs;

use App\Proxy;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class AraneoPageImportJob extends Job
{
    protected $endpoint;
    protected $page;

    public function __construct(string $endpoint, string $page)
    {
        $this->endpoint = $endpoint;
        $this->page = $page;
    }

    public function handle(Client $guzzle, Logger $logger, Proxy $proxy)
    {
        $logger->info('Importing Araneo page.', [
            'endpoint' => $this->endpoint,
            'page' => $this->page,
        ]);

        $request = $guzzle->get($this->endpoint, [
            RequestOptions::QUERY => [
                'page' => $this->page,
            ],
        ]);

        $proxies = json_decode($request->getBody())->data;

        foreach ($proxies as $row) {
            $proxy->create((array) $row);
        }

        $logger->info('Importing Araneo page is done', [
            'endpoint' => $this->endpoint,
            'page' => $this->page,
        ]);
    }
}
