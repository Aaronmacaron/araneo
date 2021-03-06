<?php

namespace Araneo\Testers;

use App\Proxy;
use Araneo\Contracts\TesterInterface;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;
use Illuminate\Support\Collection;

class IPApiTest implements TesterInterface
{
    const HTTP_CODE_OK = 200;
    const IPAPI_ENDPOINT = 'https://ipapi.co/json';
    const REQUEST_TIMEOUT = 5;

    protected $client;
    protected $logger;

    public function __construct(Client $client, Logger $logger, Proxy $proxy)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->proxy = $proxy;
    }

    public function testAndSave(Proxy $proxy): bool
    {
        $proxy->last_status = Proxy::STATUS_FAILED;

        if ($this->singleRequest($proxy->connection)) {
            $proxy->last_status = Proxy::STATUS_WORKING;
            $proxy->last_worked_at = Carbon::now();
        }

        $proxy->last_checked_at = Carbon::now();

        return $proxy->save();
    }

    public function testAndSaveBatch(Collection $proxies)
    {
        $proxies = $this->proxy->whereIn('id', $proxies)->get();

        $requests = function ($proxies) {
            for ($i = 0; $i < count($proxies); $i++) {
                yield function () use ($proxies, $i) {
                    return $this->client->requestAsync('GET', self::IPAPI_ENDPOINT, $this->requestOptions($proxies[$i]));
                };
            }
        };

        $pool = new Pool($this->client, $requests($proxies), [
            'concurrency' => $proxies->count(),
            'fulfilled' => function (Response $response, int $index) use ($proxies) {
                $proxy = $proxies[$index];

                $proxy->last_status = Proxy::STATUS_WORKING;
                $proxy->last_checked_at = Carbon::now();
                $proxy->last_worked_at = Carbon::now();
                $proxy->save();

                $this->logger->info('Proxy worked!', [
                    'proxy_id' => $proxy->id,
                    'response' => (string) $response->getBody(),
                ]);
            },
            'rejected' => function (TransferException $exception, int $index) use ($proxies) {
                $proxy = $proxies[$index];

                $proxy->last_status = Proxy::STATUS_FAILED;
                $proxy->last_checked_at = Carbon::now();
                $proxy->save();

                $this->logger->info('Proxy failed!', [
                    'proxy_id' => $proxy->id,
                    'exception' => $exception->getMessage(),
                ]);
            },
        ]);

        $promise = $pool->promise();

        return $promise->wait();
    }

    public function singleRequest(string $proxy): bool
    {
        try {
            $req = $this->client->request('GET', self::IPAPI_ENDPOINT, [
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::PROXY => $proxy,
                RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        return $req->getStatusCode() === self::HTTP_CODE_OK;
    }

    public function requestOptions(Proxy $proxy): array
    {
        return [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::PROXY => $proxy->connection,
            RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
        ];
    }
}
