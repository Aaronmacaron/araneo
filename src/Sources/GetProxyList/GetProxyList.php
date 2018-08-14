<?php

namespace Araneo\Sources\GetProxyList;

use Araneo\Contracts\SourceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Log\Logger;

class GetProxyList implements SourceInterface
{
    const GETPROXYLIST_ENDPOINT = 'https://api.getproxylist.com/proxy';

    protected $guzzle;
    protected $logger;
    protected $transformer;

    public function __construct(Client $guzzle, Logger $logger, Transformer $transformer)
    {
        $this->guzzle = $guzzle;
        $this->logger = $logger;
        $this->transformer = $transformer;
    }

    public function random(): array
    {
        try {
            $req = $this->guzzle->request('GET', self::GETPROXYLIST_ENDPOINT);

            return $this->transformer->transform($req);
        } catch (ClientException $exception) {
            $this->logger->error('Cannot get a proxy this time.', [
                'response_code' => (int) $exception->getResponse()->getStatusCode(),
                'payload' => (string) $exception->getResponse()->getBody(),
            ]);
        }
    }
}
