<?php

namespace Araneo\Contracts;

use App\Proxy;
use Illuminate\Database\Eloquent\Collection;


interface TesterInterface
{
    public function testAndSave(Proxy $proxy): bool;
    public function testAndSaveBatch(Collection $proxies);
    public function singleRequest(string $proxy): bool;
    public function requestOptions(Proxy $proxy): array;
}
