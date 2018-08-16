<?php

namespace Araneo\Testers;

use App\Proxy;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class LumTest
{
    const HTTP_CODE_OK = 200;
    const LUMTEST_ENDPOINT = 'https://lumtest.com/myip.json';
    const REQUEST_TIMEOUT = 5;

    protected $client;
    protected $logger;

    public function __construct(Client $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function testAndSave(Proxy $proxy)
    {
        $proxy->last_status = Proxy::STATUS_FAILED;

        if ($this->request($proxy->connection)) {
            $proxy->last_status = Proxy::STATUS_WORKING;
            $proxy->last_worked_at = Carbon::now();
        }

        $proxy->last_checked_at = Carbon::now();
        $proxy->save();
    }

    public function request(string $proxy): bool
    {
        try {
            $req = $this->client->request('GET', self::LUMTEST_ENDPOINT, [
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::PROXY => $proxy,
                RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        return $req->getStatusCode() === self::HTTP_CODE_OK;
    }
}
