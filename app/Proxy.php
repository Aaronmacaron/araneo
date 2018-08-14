<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $fillable = [
        'ip_address', 'country', 'protocol', 'port', 'anonymity_level',
        'supports_method_get', 'supports_method_post', 'supports_cookies',
        'supports_referer', 'supports_user_agent', 'supports_https',
    ];
}
