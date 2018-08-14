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
        return response()->json(Proxy::all());
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(Proxy::findOrFail($id));
    }

    public function random(Request $request): JsonResponse
    {
        $proxy = Proxy::random()->where($request->only([
            'country', 'anonymity_level', 'last_status'
        ]))->first();

        if ($proxy) {
            return response()->json($proxy);
        }

        throw new NotFoundHttpException("There are no proxy matching your query.");
    }
}
