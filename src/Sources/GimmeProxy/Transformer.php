<?php

namespace Araneo\Sources\GimmeProxy;

use Araneo\Contracts\SourceTransformerInterface;
use Araneo\Sources\ProxySource;
use GuzzleHttp\Psr7\Response;

class Transformer implements SourceTransformerInterface
{
    public function transform(Response $response): array
    {
        $payload = json_decode($response->getBody());

        return [
            'anonymity_level' => (int) $payload->anonymityLevel,
            'country' => $payload->country,
            'ip_address' => $payload->ip,
            'port' => (int) $payload->port,
            'protocol' => $payload->protocol,
            'proxy_source' => ProxySource::GIMME_PROXY,
            'supports_cookies' => (bool) $payload->cookies,
            'supports_custom_headers' => false,
            'supports_https' => (bool) $payload->supportsHttps,
            'supports_method_get' => (bool) $payload->get,
            'supports_method_post' => (bool) $payload->post,
            'supports_referer' => (bool) $payload->referer,
            'supports_user_agent' => (bool) $payload->{"user-agent"},
        ];
    }
}
