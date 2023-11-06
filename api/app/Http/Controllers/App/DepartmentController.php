<?php

namespace App\Http\Controllers\App;

use App\Models\App;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("department");
        return response($reply->json(), $reply->status());
    }

    public function store(App $app, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->post("department", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function show(App $app, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("department/{$id}");
        return response($reply->json(), $reply->status());
    }

    public function update(App $app, Request $request, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->put("department/{$id}", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function destroy(App $app, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->delete("department/{$id}");
        return response($reply->json(), $reply->status());
    }
}
