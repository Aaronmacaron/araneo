<?php

namespace App;

use App\Events\ProxyCreatedEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    const ANONYMITY_LEVEL_HIGH = 2;
    const ANONYMITY_LEVEL_LOW = 0;
    const ANONYMITY_LEVEL_MEDIUM = 1;
    const STATUS_FAILED = 'failed';
    const STATUS_WORKING = 'working';

    protected $fillable = [
        'ip_address', 'country', 'protocol', 'port', 'anonymity_level',
        'supports_method_get', 'supports_method_post', 'supports_cookies',
        'supports_referer', 'supports_user_agent', 'supports_https',
        'supports_custom_headers', 'proxy_source',
    ];

    protected $appends = ['connection'];

    protected $dispatchesEvents = [
        'created' => ProxyCreatedEvent::class,
    ];

    public function getConnectionAttribute(): string
    {
        return sprintf('%s://%s:%s', $this->protocol, $this->ip_address, $this->port);
    }

    public function scopeWorking($query): Builder
    {
        return $query->whereLastStatus(Proxy::STATUS_WORKING);
    }

    public function scopeRandom($query): Builder
    {
        return $query->inRandomOrder();
    }

    public function scopeAnonymous($query): Builder
    {
        return $query->where('anonymity_level', '>=', self::ANONYMITY_LEVEL_MEDIUM);
    }
}
