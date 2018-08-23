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
            ->recent(60)
            ->working()
            ->anonymous()
            ->random()
            ->where('proxy_source', '<>', $current)
            ->first();

        if (!$proxy) {
            return false;
        }

        return $proxy->connection;
    }
}
