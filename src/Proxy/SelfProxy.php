<?php

namespace Araneo\Proxy;

use App\Proxy;

class SelfProxy
{
    protected $proxy;

    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function connection(string $current)
    {
        $proxy = $this->proxy
            ->where('proxy_source', '<>', $current)
            ->inRandomOrder()
            ->first();

        if (!$proxy) {
            return false;
        }

        return $proxy->connection;
    }
}
