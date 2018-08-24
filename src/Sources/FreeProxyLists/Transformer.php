<?php

namespace Araneo\Sources\FreeProxyLists;

use Araneo\Contracts\SourceTransformerInterface;
use Araneo\Sources\ProxySource;
use DOMElement;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class Transformer implements SourceTransformerInterface
{
    public function transform(Response $response): array
    {
//        dd((string) $response->getBody());

        $crawler = new Crawler((string) $response->getBody());
        dd((string) $response->getBody(), $crawler->html());

        $table = $crawler->filterXPath('/html/body/div[1]/div[2]/table/tbody/tr');
        $proxies = [];


        dd($table->html());

        foreach ($table as $row) {
            dd($row);
            $proxies[] = $this->parseRow($row);
        }

        return $proxies;
    }

    private function parseRow(DOMElement $row): array
    {
        dd($row);
        return [
            'anonymity_level' => $this->resolveAnonymous($row->childNodes->item(3)->textContent),
            'country' => $row->childNodes->item(2)->textContent,
            'ip_address' => $row->childNodes->item(0)->textContent,
            'port' => $row->childNodes->item(1)->textContent,
            'protocol' => 'http',
            'proxy_source' => ProxySource::FREE_PROXY_LIST,
            'supports_cookies' => false,
            'supports_custom_headers' => false,
            'supports_https' => $row->childNodes->item(6)->textContent === 'yes',
            'supports_method_get' => false,
            'supports_method_post' =>false,
            'supports_referer' => false,
            'supports_user_agent' => false,
        ];
    }

    private function resolveAnonymous(string $level): int
    {
        if ($level === 'anonymous') {
            return 1;
        }

        if ($level === 'elite proxy') {
            return 2;
        }

        return 0;
    }
}
