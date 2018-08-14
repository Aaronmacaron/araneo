<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    const STATUS_WORKING = 'working';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'ip_address', 'country', 'protocol', 'port', 'anonymity_level',
        'supports_method_get', 'supports_method_post', 'supports_cookies',
        'supports_referer', 'supports_user_agent', 'supports_https',
        'supports_custom_headers', 'proxy_source',
    ];

    protected $appends = ['connection'];

    public function getConnectionAttribute(): string
    {
        return sprintf('%s://%s:%s', $this->protocol, $this->ip_address, $this->port);
    }
}
