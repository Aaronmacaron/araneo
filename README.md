# Araneo - Your Own Random Proxy Endpoint

Areneo is a microservice written in PHP that crawls, indexes and monitors proxies.

* Algorithm to keep proxies monitored and flag them as healthy or unhealthy
* Proxy crawler from multiples data sources
* [Idempotency feature][1] for easier proxy sticking within sessions
* Random proxy endpoint with query interface
* Provides REST API

## REST API

Araneo has only two endpoints, both have the same parameters.

### Parameters

| Parameter | Description  
|-----------|-------------|  
| anonymity_level | Search by anonymity level (you can use some smart query here) |
| country | Search by country using [ISO-3166-1 Alpha-2][1] pattern. |
| ip_address | Search by an specific endpoint. |
| last_checked_at | Search by datetime, you can use some smart query here | 
| last_status | Search by last status, you can use `failed` and `working` | 
| last_worked_at | Search by datetime, you can use some smart query here | 
| port | Search by port (you can use some smart query here) |
| protocol | Search by protocol. E.g. socks5 |
| proxy_source | Search proxies by source |
| supports_cookies | Search proxies that supports cookies, you can use boolean values |
| supports_custom_headers | Search proxies that supports custom headers, you can use boolean values |
| supports_https | Search proxies that supports HTTP protocol, you can use boolean values |
| supports_method_get | Search proxies that supports GET, you can use boolean values |
| supports_method_post | Search proxies that supports POST, you can use boolean values |
| supports_referer | Search proxies that supports referer header, you can use boolean values |
| supports_user_agent | Search proxies that supports custom user agent header, you can use boolean values |

### 1.`GET /proxy`

Returns a single proxy and has a [idempotency feature][1].

#### Response

```json
{
    "id": 82245,
    "ip_address": "141.136.64.25",
    "country": "AM",
    "protocol": "http",
    "port": 32431,
    "anonymity_level": 0,
    "supports_method_get": 0,
    "supports_method_post": 0,
    "supports_cookies": 0,
    "supports_referer": 0,
    "supports_user_agent": 0,
    "supports_https": 0,
    "last_status": "working",
    "last_checked_at": "2018-09-04 07:55:29",
    "created_at": "2018-09-04 07:55:07",
    "updated_at": "2018-09-05 05:01:10",
    "supports_custom_headers": 0,
    "proxy_source": "freeproxylist",
    "last_worked_at": "2018-09-04 07:55:29",
    "connection": "http://141.136.64.25:32431"
}
```
 
### 2. `GET /proxies`

Returns a list of proxies.

#### Response

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 255,
            "ip_address": "5.135.164.72",
            "country": "FR",
            "protocol": "http",
            "port": 3128,
            "anonymity_level": 0,
            "supports_method_get": 0,
            "supports_method_post": 0,
            "supports_cookies": 0,
            "supports_referer": 0,
            "supports_user_agent": 0,
            "supports_https": 1,
            "last_status": "working",
            "last_checked_at": "2018-09-05 22:09:38",
            "created_at": "2018-08-24 20:20:02",
            "updated_at": "2018-09-05 22:09:38",
            "supports_custom_headers": 0,
            "proxy_source": "freeproxylist",
            "last_worked_at": "2018-09-05 22:09:38",
            "connection": "http://5.135.164.72:3128"
        },
        ...
    ],
    "first_page_url": "https://localhost/api/proxies?page=1",
    "from": 1,
    "last_page": 685,
    "last_page_url": "https://localhost/api/proxies?page=685",
    "next_page_url": "https://localhost/api/proxies?page=2",
    "path": "https://localhost/api/proxies",
    "per_page": 20,
    "prev_page_url": null,
    "to": 20,
    "total": 13694
}
```

## Setup

### Requirements

- PHP 7.1 or later
- HTTP server with PHP support (E.g. Apache, Nginx, Caddy)
- Composer
- A supported database: PostgreSQL

### Schedules

Araneo uses schedules to monitor and crawl proxies.

| Cron | Command | Description  
|------|---------|------------|  
| 0 * * * * | php artisan araneo:purge 24 | Purge unhealthy proxies from database. |  
| * * * * * | php artisan araneo:crawler:gimmeproxy 10 | Dispatch 10 requests against GimmeProxy asking for proxy. |  
| * * * * * | php artisan araneo:crawler:getproxylist 10 | Dispatch 10 requests against GetProxyList asking for proxy. |  
| * * * * * | php artisan araneo:crawler:freeproxylist | Dispatch 10 requests against FreeProxyList asking for proxy. |  
| */15 * * * * | php artisan araneo:check:lumtest 15 | Dispatch into the queue jobs to check all proxies health. |  

### Queues

For a database with 20k proxies, we need around 5 workers.  

You can run a worker with `php artisan queue:work --sleep=3 --tries=3 --daemon --queue=high,low`.

## Idempotency feature

## How health checking is done

To be added.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

[1]: #idempotency-feature
[2]: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
