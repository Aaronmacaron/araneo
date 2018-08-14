<?php

namespace Araneo\Sources\GimmeProxy;

use Araneo\Contracts\SourceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Log\Logger;

class GimmeProxy implements SourceInterface
{
    const GIMMEPROXY_ENDPOINT = 'https://gimmeproxy.com/api/getProxy';

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
            $req = $this->guzzle->request('GET', self::GIMMEPROXY_ENDPOINT);

            return $this->transformer->transform($req);
        } catch (ClientException $exception) {
            $this->logger->error('Cannot get a proxy this time.', [
                'exception' => $exception,
            ]);
        }
    }
}
