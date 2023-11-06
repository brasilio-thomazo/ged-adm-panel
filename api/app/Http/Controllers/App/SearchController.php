<?php

namespace App\Http\Controllers\App;

use App\Models\App;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("search");
        return response($reply->json(), $reply->status());
    }

    public function store(App $app, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->post("search", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function show(App $app, $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("search/$id");
        return response($reply->json(), $reply->status());
    }

    public function update(App $app, $id, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->put("search/$id", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function destroy(App $app, $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->delete("search/$id");
        return response($reply->json(), $reply->status());
    }
}
