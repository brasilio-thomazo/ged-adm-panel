<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDatabaseConfigRequest;
use App\Http\Requests\UpdateDatabaseConfigRequest;
use App\Models\DatabaseConfig;

class DatabaseConfigController extends Controller
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
    public function store(StoreDatabaseConfigRequest $request): \Illuminate\Http\JsonResponse
    {
        $custom = $request->get('custom', false);
        $driver = config("database.default");
        $section = "database.connections.{$driver}";
        $save = [
            'app_id' => $request->get('app_id'),
            'custom' => $custom,
            'driver' => $custom ? $request->get('driver') : $driver,
            'writer_host' => $custom ? $request->get('writer_host') : config("{$section}.write.host"),
            'writer_port' => $custom ? $request->get('writer_port') : config("{$section}.write.port"),
            'reader_host' => $custom ? $request->get('reader_host') : config("{$section}.read.host"),
            'reader_port' => $custom ? $request->get('reader_port') : config("{$section}.read.port"),
            'database' => $request->get('database'),
        ];
        $databaseConfig = DatabaseConfig::create($save);
        return response()->json($databaseConfig, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DatabaseConfig $databaseConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatabaseConfigRequest $request, DatabaseConfig $databaseConfig)
    {
        $custom = $request->get('custom', false);
        $driver = config("database.default");
        $section = "database.connections.{$driver}";
        $save = [
            'custom' => $custom,
            'driver' => $custom ? $request->get('driver') : $driver,
            'writer_host' => $custom ? $request->get('writer_host') : config("{$section}.write.host"),
            'writer_port' => $custom ? $request->get('writer_port') : config("{$section}.write.port"),
            'reader_host' => $custom ? $request->get('reader_host') : config("{$section}.read.host"),
            'reader_port' => $custom ? $request->get('reader_port') : config("{$section}.read.port"),
        ];
        $databaseConfig->update($save);
        return response()->json($databaseConfig);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseConfig $databaseConfig)
    {
        //
    }
}
