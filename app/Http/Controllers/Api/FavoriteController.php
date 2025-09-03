<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class FavoriteController extends Controller
{
    /** POST /api/favorites body: { assetId: string } */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'assetId' => ['required','string','max:255'],
        ]);


        $fav = Favorite::firstOrCreate(['asset_id' => $validated['assetId']]);
        return response()->json($fav, 201);
    }


    /** GET /api/favorites */
    public function index(): JsonResponse
    {
        return response()->json(
            Favorite::query()->orderByDesc('created_at')->get(['id','asset_id','created_at'])
        );
    }


    /** DELETE /api/favorites/{id} */
    public function destroy(int $id): JsonResponse
    {
        $deleted = Favorite::whereKey($id)->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['message' => 'Deleted']);
    }
}