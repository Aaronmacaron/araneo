<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProxyController extends Controller
{
    private $searchable = [
        'country', 'protocol', 'port', 'anonymity_level', 'supports_method_get',
        'supports_method_post', 'supports_cookies', 'supports_referer', 'supports_user_agent',
        'supports_https', 'supports_custom_headers', 'last_status',
    ];

    public function list(Request $request): JsonResponse
    {
        $proxies = Proxy::orderBy('created_at')
            ->where($request->only($this->searchable))
            ->paginate(20);

        return response()->json($proxies);
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(Proxy::findOrFail($id));
    }

    public function random(Request $request): JsonResponse
    {
        $proxy = Proxy::random()
            ->where($request->only($this->searchable))
            ->first();

        if ($proxy) {
            return response()->json($proxy);
        }

        throw new NotFoundHttpException("There are no proxy matching your query.");
    }
}
