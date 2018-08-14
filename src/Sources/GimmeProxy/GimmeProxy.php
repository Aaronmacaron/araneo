<?php

namespace Araneo\Sources\GimmeProxy;

use Araneo\Contracts\SourceInterface;
use Araneo\Proxy\SelfProxy;
use Araneo\Sources\ProxySource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\Logger;

class GimmeProxy implements SourceInterface
{
    const GIMMEPROXY_ENDPOINT = 'https://gimmeproxy.com/api/getProxy';
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
            $req = $this->guzzle->request('GET', self::GIMMEPROXY_ENDPOINT, [
                RequestOptions::PROXY => $this->proxy->connection(ProxySource::GIMME_PROXY),
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
