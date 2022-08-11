<?php

namespace Imega\DataReporting\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Imega\DataReporting\Http\Controllers\Controller;
use Imega\DataReporting\Models\Order;
use Imega\DataReporting\Requests\ListOrderRequest;

final class OrdersController extends Controller
{
    public function index(ListOrderRequest $request): JsonResponse
    {
        return response()->json((new Order)->getOrdersByFilter($request));
    }
}
