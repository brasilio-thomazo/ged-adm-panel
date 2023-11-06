<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCacheConfigRequest;
use App\Http\Requests\UpdateCacheConfigRequest;
use App\Models\CacheConfig;

class CacheConfigController extends Controller
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
    public function store(StoreCacheConfigRequest $request): \Illuminate\Http\JsonResponse
    {
        $custom = $request->get('custom', false);
        $driver = config("cache.default");
        $save = [
            'custom' => $custom,
            'driver' => $custom ? $request->get('driver') : $driver,
            'host' => $custom ? $request->get('host') : config("database.redis.cache.host"),
            'port' => $custom ? $request->get('port') : config("database.redis.cache.port")
        ];
        $cacheConfig = CacheConfig::create($save);
        return response()->json($cacheConfig);
    }

    /**
     * Display the specified resource.
     */
    public function show(CacheConfig $cacheConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCacheConfigRequest $request, CacheConfig $cacheConfig)
    {
        $custom = $request->get('custom', false);
        $driver = config("cache.default");
        $save = [
            'custom' => $custom,
            'driver' => $custom ? $request->get('driver') : $driver,
            'host' => $custom ? $request->get('host') : config("database.redis.cache.host"),
            'port' => $custom ? $request->get('port') : config("database.redis.cache.port")
        ];
        $cacheConfig->update($save);
        return response()->json($cacheConfig);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CacheConfig $cacheConfig)
    {
        //
    }
}
