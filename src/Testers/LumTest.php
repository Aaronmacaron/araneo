<?php

namespace Araneo\Testers;

use App\Proxy;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class LumTest
{
    const HTTP_CODE_OK = 200;
    const LUMTEST_ENDPOINT = 'https://lumtest.com/myip.json';
    const REQUEST_TIMEOUT = 30;

    protected $client;
    protected $logger;

    public function __construct(Client $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function test(Proxy $proxy): bool
    {
        try {
            $req = $this->client->request('GET', self::LUMTEST_ENDPOINT, [
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::PROXY => (string) $proxy->connection,
                RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        return $req->getStatusCode() === self::HTTP_CODE_OK;
    }
}
