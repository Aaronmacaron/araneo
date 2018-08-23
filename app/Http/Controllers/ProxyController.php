<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProxyController extends Controller
{
    public function list(): JsonResponse
    {
        $proxies = Proxy::orderBy('created_at')
            ->filtered()
            ->sorted()
            ->paginate(20);

        return response()->json($proxies);
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(Proxy::findOrFail($id));
    }

    public function random(): JsonResponse
    {
        $proxy = Proxy::random()
            ->filtered()
            ->first();

        if ($proxy) {
            return response()->json($proxy);
        }

        throw new NotFoundHttpException("There are no proxies that match your query.");
    }
}
