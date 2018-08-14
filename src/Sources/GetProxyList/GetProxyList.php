<?php

namespace Araneo\Sources\GetProxyList;

use Araneo\Contracts\SourceInterface;
use Araneo\Proxy\SelfProxy;
use Araneo\Sources\ProxySource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class GetProxyList implements SourceInterface
{
    const GETPROXYLIST_ENDPOINT = 'https://api.getproxylist.com/proxy';
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
        try {
            $req = $this->guzzle->request('GET', self::GETPROXYLIST_ENDPOINT, [
                RequestOptions::PROXY => $this->proxy->connection(ProxySource::GET_PROXY_LIST),
                RequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
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
