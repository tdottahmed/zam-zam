<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'cart' => function () use ($request) {
                if (!$request->user()) {
                    return [
                        'count' => 0,
                        'items' => [],
                    ];
                }
                
                $cart = $request->user()->cart;
                
                if (!$cart) {
                    return [
                        'count' => 0,
                        'items' => [],
                    ];
                }

                return [
                    'count' => $cart->items->sum('quantity'),
                    'total' => $cart->items->sum(fn($item) => $item->quantity * $item->product->unit_price),
                    'items' => $cart->items()
                        ->with(['product:id,name,unit_price,product_code,unit_id', 'product.unit'])
                        ->latest()
                        ->get()
                        ->map(fn ($item) => [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'name' => $item->product->name,
                            'image' => $item->product->image,
                            'unit_price' => $item->product->unit_price,
                            'quantity' => $item->quantity,
                            'total' => $item->quantity * $item->product->unit_price,
                            'unit' => $item->product->unit ? $item->product->unit->name : null,
                        ]),
                ];
            },
        ];
    }
}
