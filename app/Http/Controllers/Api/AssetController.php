<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;


class AssetController extends Controller
{
    /**
    * GET /api/assets
    */
    public function index(Request $request): JsonResponse
    {
        $key = 'coingecko.top10.v1';

        $assets = Cache::remember($key, 60, function () {
            $response = Http::baseUrl('https://api.coingecko.com/api/v3')
                        ->get('/coins/markets', [
                            'vs_currency' => 'usd',
                            'order' => 'market_cap_desc',
                            'per_page' => 10,
                            'page' => 1,
                            'sparkline' => false,
                            'price_change_percentage' => '24h',
                        ]);

            abort_unless($response->ok(), $response->status(), 'CoinGecko upstream error');
            return collect($response->json())->map(function ($c) {
                return [
                    'id' => $c['id'],
                    'symbol' => strtoupper($c['symbol'] ?? ''),
                    'name' => $c['name'] ?? '',
                    'image' => $c['image'] ?? null,
                    'current_price' => $c['current_price'] ?? null,
                    'price_change_percentage_24h' => $c['price_change_percentage_24h'] ?? null,
                ];
            })->all();
        });

        return response()->json($assets);
    }


    /**
    * GET /api/assets/{id} – details
    */
    public function show(string $id): JsonResponse
    {
        $key = "coingecko.asset.$id.v1";

        $asset = Cache::remember($key, 60, function () use ($id) {
            $response = Http::baseUrl('https://api.coingecko.com/api/v3')
                            ->get("/coins/{$id}", [
                                'localization' => false,
                                'tickers' => false,
                                'market_data' => true,
                                'community_data' => false,
                                'developer_data' => false,
                                'sparkline' => false,
                            ]);

            abort_unless($response->ok(), $response->status(), 'CoinGecko upstream error');
            $c = $response->json();

            return [
                'id' => $c['id'] ?? $id,
                'symbol' => strtoupper($c['symbol'] ?? ''),
                'name' => $c['name'] ?? '',
                'image' => $c['image']['large'] ?? ($c['image']['thumb'] ?? null),
                'current_price' => $c['market_data']['current_price']['usd'] ?? null,
                'price_change_percentage_24h' => $c['market_data']['price_change_percentage_24h'] ?? null,
                'market_cap' => $c['market_data']['market_cap']['usd'] ?? null,
                'high_24h' => $c['market_data']['high_24h']['usd'] ?? null,
                'low_24h' => $c['market_data']['low_24h']['usd'] ?? null,
                'homepage' => $c['links']['homepage'][0] ?? null,
                'description' => $c['description']['en'] ?? null,
            ];
        });

        return response()->json($asset);
    }

}