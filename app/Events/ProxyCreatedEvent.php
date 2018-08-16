<?php

namespace App\Events;

use App\Proxy;

class ProxyCreatedEvent extends Event
{
    public $proxy;

    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }
}
