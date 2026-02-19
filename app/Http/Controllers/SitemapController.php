<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product; // Assuming you have a Product model
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class SitemapController extends Controller
{
    public function sitemap()
    {
        return $this->buildSitemap()->toResponse(request());
    }

    public function generate()
    {
        $this->buildSitemap()->writeToFile(public_path('sitemap.xml'));

        return back()->with('success', 'Sitemap generated successfully.');
    }

    protected function buildSitemap()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/about'))
            ->add(Url::create('/contact'))
            ->add(Url::create('/shop'));

        // Add products to sitemap
        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create(route('shop.show', $product->id)));
        });

        return $sitemap;
    }

    public function feed()
    {
        // This method might not be needed if using the package's route macro, 
        // but often we want custom control or just to define the items here.
        // The package usually expects a static method on the model or a controller method returning items.
        
        $products = Product::latest()->take(50)->get();

        return $products->map(function (Product $product) {
            return FeedItem::create([
                'id' => $product->id,
                'title' => $product->name,
                'summary' => $product->notes ?? '', 
                'updated' => $product->updated_at,
                'link' => route('shop.show', $product->id),
                'authorName' => 'Admin', // Or config('app.name')
            ]);
        });
    }
}
