<?php

namespace Araneo\Sources\GetProxyList;

use Araneo\Contracts\SourceTransformerInterface;
use Araneo\Sources\ProxySource;
use GuzzleHttp\Psr7\Response;

class Transformer implements SourceTransformerInterface
{
    public function transform(Response $response): array
    {
        $payload = json_decode($response->getBody());

        return [
            'anonymity_level' => $this->resolveAnonymityLevel($payload->anonymity),
            'country' => $payload->country,
            'ip_address' => $payload->ip,
            'port' => (int) $payload->port,
            'protocol' => $payload->protocol,
            'proxy_source' => ProxySource::GET_PROXY_LIST,
            'supports_cookies' => (bool) $payload->allowsCookies,
            'supports_custom_headers' => (bool) $payload->allowsCustomHeaders,
            'supports_https' => (bool) $payload->allowsHttps,
            'supports_method_get' => true,
            'supports_method_post' => (bool) $payload->allowsPost,
            'supports_referer' => (bool) $payload->allowsRefererHeader,
            'supports_user_agent' => (bool) $payload->allowsUserAgentHeader,
        ];
    }

    private function resolveAnonymityLevel(string $anonymity): int
    {
        if ($anonymity === 'transparent') {
            return 0;
        }

        if ($anonymity === 'anonymous') {
            return 1;
        }

        return 2;
    }
}
