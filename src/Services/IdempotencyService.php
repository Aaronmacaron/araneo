<?php

namespace Araneo\Services;

use App\Proxy;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;

class IdempotencyService
{
    const IDEMPOTENCY_HEADER = 'X-Idempotency';
    const IDEMPOTENCY_TTL = 360;

    protected $cache;
    protected $proxy;

    public function __construct(Cache $cache, Proxy $proxy)
    {
        $this->cache = $cache;
        $this->proxy = $proxy;
    }

    public function lock(Request $request, callable $cb): Proxy
    {
        if ($request->hasHeader(self::IDEMPOTENCY_HEADER)) {
            $header = $request->header(self::IDEMPOTENCY_HEADER);

            if ($this->cache->has($header)) {
                $cachedId = $this->cache->get($header);
                $proxy = $this->proxy->whereId($cachedId)->first();

                if ($proxy) {
                    return $proxy;
                }
            }

            return $this->lockAndReturn($header, $cb);
        }

        return $cb($this->proxy);
    }

    private function lockAndReturn(string $header, callable $cb): Proxy
    {
        $proxy = $cb($this->proxy);

        $this->cache->put($header, $proxy->id, self::IDEMPOTENCY_TTL);

        return $proxy;
    }
}
