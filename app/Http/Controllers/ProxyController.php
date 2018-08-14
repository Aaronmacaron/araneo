<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\JsonResponse;

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
}
