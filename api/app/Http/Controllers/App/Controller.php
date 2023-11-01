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
    public function getToken(App $app): string
    {
        $token = Cache::get("token_" . $app->id);
        if (!$token) {
            $password = config("install.system.password", "system");
            $reply = Http::post("http://localhost:8010/api/login", [
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
        $baseURL = "http://localhost:8010/api";
        $token = $this->getToken($app);
        return Http::withHeader("Authorization", $token)->baseUrl($baseURL);
    }
}
