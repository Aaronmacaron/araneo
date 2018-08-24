<?php

namespace Araneo\Sources\FreeProxyList;

use Araneo\Contracts\SourceInterface;
use Araneo\Proxy\SelfProxy;
use Araneo\Sources\ProxySource;
use Campo\UserAgent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class FreeProxyList implements SourceInterface
{
    const FREEPROXYLIST_ENDPOINT = 'http://free-proxy-list.net/';
    const REQUEST_TIMEOUT = 30;

    protected $guzzle;
    protected $logger;
    protected $proxy;
    protected $transformer;

    public function __construct(
        Client $guzzle,
        Logger $logger,
        SelfProxy $proxy,
        Transformer $transformer
    ) {
        $this->guzzle = $guzzle;
        $this->logger = $logger;
        $this->proxy = $proxy;
        $this->transformer = $transformer;
    }

    public function random(): array
    {
        return [];
    }

    public function list(): array
    {
        try {
            $req = $this->guzzle->request('GET', self::FREEPROXYLIST_ENDPOINT, [
                RequestOptions::PROXY => $this->proxy->connection(ProxySource::FREE_PROXY_LIST),
                RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
                RequestOptions::HEADERS => [
                    'Accept-Language' => 'en-US,en;q=0.9,pt;q=0.8',
                    'Accept' => 'text/html; charset=utf-8',
                    'User-Agent' => UserAgent::random(),
                ],
            ]);

            return $this->transformer->transform($req);
        } catch (ClientException $exception) {
            $this->logger->error('Cannot get a proxy this time.', [
                'response_code' => (int) $exception->getResponse()->getStatusCode(),
                'payload' => (string) $exception->getResponse()->getBody(),
            ]);
        }

        throw new \Exception('Cannot get a proxy this time.');
    }
}
