<?php

namespace App\Http\Controllers\App;

use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GroupController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("group");
        return response($reply->json(), $reply->status());
    }

    public function store(App $app, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->post("group", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function show(App $app, $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("group/{$id}");
        return response($reply->json(), $reply->status());
    }

    public function update(App $app, Request $request, $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->put("group/{$id}", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function destroy(App $app, $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->delete("group/{$id}");
        return response($reply->json(), $reply->status());
    }
}
