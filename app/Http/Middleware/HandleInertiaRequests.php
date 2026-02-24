<?php

namespace App\Http\Middleware;

use App\Models\Page;
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
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
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

                $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->unit_price);

                return [
                    'count' => $cart->items->sum('quantity'),
                    'summary' => [
                        'subtotal' => $subtotal,
                        'total' => $subtotal, 
                    ],
                    'items' => $cart->items()
                        ->with(['product:id,name,slug,image,unit_price,product_code,unit_id,stock_unit,pcs_in_ctn,unit_value', 'product.unit'])
                        ->latest()
                        ->get()
                        ->map(fn ($item) => [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'name' => $item->product->name,
                            'slug' => $item->product->slug,
                            'image' => $item->product->image,
                            'unit_price' => $item->product->unit_price,
                            'quantity' => (int) $item->quantity,
                            'total' => $item->quantity * $item->product->unit_price,
                            'unit' => $item->product->unit ? $item->product->unit->code : ($item->product->unit_value ? $item->product->unit_value : 'unit'),
                            'price_per_stock_unit' => $item->product->price_per_stock_unit,
                            'stock_unit_label' => $item->product->stock_unit_label,
                        ]),
                ];
            },
            'wishlist' => function () use ($request) {
                if (!$request->user()) {
                    return [];
                }
                return $request->user()->wishlist()->pluck('product_id');
            },
            'pages' => fn () => Page::orderBy('title')->get(['id', 'title', 'slug']),
            'settings' => function () {
                $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
                return [
                    'site_name'        => $settings['site_name'] ?? config('app.name'),
                    'site_logo'        => isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/Zam_logo-120x99.png'),
                    'site_favicon'     => isset($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('favicon.ico'),
                    'social_facebook'  => $settings['social_facebook'] ?? '',
                    'social_instagram' => $settings['social_instagram'] ?? '',
                    'social_linkedin'  => $settings['social_linkedin'] ?? '',
                    'social_twitter'   => $settings['social_twitter'] ?? '',
                    'social_youtube'   => $settings['social_youtube'] ?? '',
                    // Contact page
                    'address'          => $settings['address'] ?? '',
                    'address_alt'      => $settings['address_alt'] ?? '',
                    'contact_email'    => $settings['contact_email'] ?? '',
                    'contact_phone'    => $settings['contact_phone'] ?? '',
                    'contact_cell'     => $settings['contact_cell'] ?? '',
                    'contact_email_alt_1' => $settings['contact_email_alt_1'] ?? '',
                    'contact_email_alt_2' => $settings['contact_email_alt_2'] ?? '',
                ];
            },
        ];
    }
}
