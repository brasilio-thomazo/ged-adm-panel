<?php

namespace App\Http\Controllers\App;

use App\Models\App;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("user");
        return response($reply->json(), $reply->status());
    }

    public function store(App $app, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->post("user", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function update(App $app, string $id, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->put("user/{$id}", $request->all());
        return response($reply->json(), $reply->status());
    }
}
