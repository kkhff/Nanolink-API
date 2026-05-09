<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Url;
use App\Models\UrlClick;
use App\Jobs\ProcessUrlClick;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        do {
            $shortCode = Str::random(6);
        } while (Url::where('new_url', $shortCode)->exists());

        $url = Url::create([
            'url' => $request->url,
            'new_url' => $shortCode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Url berhasil di buat!',
            'Url' => "http://localhost/api/go/{$shortCode}",
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $shortCode)
    {
        $urlData = Cache::rememberForever("Url_{$shortCode}", function () use ($shortCode) {
            $url = Url::where('new_url', $shortCode)->firstOrFail();

            return [
                'id' => $url->id,
                'target_url' => $url->url
            ];
        });

        ProcessUrlClick::dispatch($urlData['id'], $request->ip(), $request->userAgent());

        return redirect()->away($urlData['target_url']);
    }

    public function stats($shortCode)
    {
        $url = Url::where('new_url', $shortCode)
                  ->withCount('url_clicks')
                  ->with(['url_clicks' => function ($query) {
                    $query->latest()->take(5);
                  }])
                  ->firstOrFail();


        return response()->json([
            'success' => true,
            'data' => [
                'short_code' => $url->new_url,
                'target_url' => $url->url,
                'total_click' => $url->url_clicks_count,
                'recent_clicks' => $url->url_clicks->map(function ($click) {
                    return [
                        'ip_address' => $click->ip_address,
                        'user_agent' => $click->user_agent,
                        'clicked_at' => $click->created_at,
                    ];
                })
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
