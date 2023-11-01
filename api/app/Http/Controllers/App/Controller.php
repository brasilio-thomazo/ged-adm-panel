<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller as BaseController;
use App\Models\App;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    private function getBaseURL(App $app): string
    {
        $container = config('app.client_container');
        $host = ($app->domain ? $app->domain : ($container ? "{$container}-{$app->path}" : "localhost:8010"));
        return "http://{$host}/api";
    }

    public function getToken(App $app): string
    {
        $token = Cache::get("token_" . $app->id);
        $baseURL = $this->getBaseURL($app);
        if (!$token) {
            $password = config("install.system.password", "system");
            $reply = Http::baseUrl($baseURL)->post("/login", [
                "username" => "system",
                "password" => $password,
                "device_name" => "adm-panel"
            ]);
            if (!$reply->successful())
                throw new AuthenticationException();
            Cache::put("token_" . $app->id, "Bearer " . $reply->json("token"));
        }
        return $token;
    }

    public function getHttp(App $app): PendingRequest
    {
        $token = $this->getToken($app);
        return Http::withHeader("Authorization", $token)->baseUrl($this->getBaseURL($app));
    }
}
