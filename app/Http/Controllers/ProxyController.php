<?php

namespace App\Http\Controllers;

use App\Proxy;
use Araneo\Services\IdempotencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProxyController extends Controller
{
    protected $idempotency;
    protected $proxy;

    public function __construct(IdempotencyService $idempotency, Proxy $proxy)
    {
        $this->idempotency = $idempotency;
        $this->proxy = $proxy;
    }

    public function list(): JsonResponse
    {
        $proxies = $this->proxy->orderBy('created_at')
            ->filtered()
            ->sorted()
            ->paginate(20);

        return response()->json($proxies);
    }

    public function get(int $id): JsonResponse
    {
        return response()->json($this->proxy->findOrFail($id));
    }

    public function random(Request $request): JsonResponse
    {
        $proxy = $this->idempotency->lock($request, function (Proxy $proxy) {
            return $proxy->random()->filtered()->first();
        });

        if (!$proxy) {
            throw new NotFoundHttpException("There are no proxies that match your query.");
        }

        return response()->json($proxy);
    }
}
