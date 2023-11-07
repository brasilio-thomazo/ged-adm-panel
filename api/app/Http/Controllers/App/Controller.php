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
        $token = Cache::get("app.{$app->id}.token");
        return [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => $token
        ];
    }

    public function login(App $app)
    {
        $credentials = [
            'username' => 'system',
            'password' => config("app.passwords.system", "system"),
            'device_name' => 'adm-panel',
        ];
        $baseURL = $this->getBaseURL($app);
        $headers = $this->getHeaders($app);
        $http = Http::baseUrl($baseURL)->withHeaders($headers)->post("/login", $credentials);
        if (!$http->successful())
            throw new AuthenticationException($http->json("message"));
        $token = "Bearer " . $http->json("token");
        Cache::put("app.{$app->id}.token", $token);
    }

    public function me(App $app)
    {
        $baseURL = $this->getBaseURL($app);
        $headers = $this->getHeaders($app);
        $http = Http::baseUrl($baseURL)->withHeaders($headers)->get("/me");
        return $http->status();
    }

    public function getToken(App $app): string
    {
        $token = Cache::get("app.{$app->id}.token");
        if (!$token) {
            $this->login($app);
            $token = Cache::get("app.{$app->id}.token");
        }
        return $token;
    }

    public function getHttp(App $app): PendingRequest
    {
        if ($this->me($app) === 401) {
            $this->login($app);
        }
        return Http::withHeaders($this->getHeaders($app))->baseUrl($this->getBaseURL($app));
    }
}
