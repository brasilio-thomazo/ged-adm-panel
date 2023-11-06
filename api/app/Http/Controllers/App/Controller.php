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
    public function getBaseURL(App $app): string
    {
        $container = config('app.kubernetes.client_container');
        $host = ($app->domain ? $app->domain : ($container ? "{$container}-{$app->path}" : "localhost:8010"));
        return "http://{$host}/api";
    }

    public function getHeaders(App $app): array
    {
        $token = $this->getToken($app);
        return [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => $token
        ];
    }

    public function getToken(App $app): string
    {
        $token = Cache::get("app.{$app->id}.token");
        $baseURL = $this->getBaseURL($app);
        if (!$token) {
            $password = config("app.passwords.system", "system");
            $reply = Http::baseUrl($baseURL)->post("/login", [
                "username" => "system",
                "password" => $password,
                "device_name" => "adm-panel"
            ]);
            if (!$reply->successful())
                throw new AuthenticationException();
            $token = "Bearer " . $reply->json("token");
            Cache::put("app.{$app->id}.token", $token);
        }
        return $token;
    }

    public function getHttp(App $app): PendingRequest
    {
        $token = $this->getToken($app);
        return Http::withHeaders($this->getHeaders($app))->baseUrl($this->getBaseURL($app));
    }
}
