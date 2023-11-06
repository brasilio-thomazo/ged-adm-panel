<?php

namespace App\Http\Controllers\App;

use App\Models\App;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("document_type");
        return response($reply->json(), $reply->status());
    }

    public function store(App $app, Request $request)
    {
        $http = $this->getHttp($app);
        $reply = $http->post("document_type", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function show(App $app, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("document_type/{$id}");
        return response($reply->json(), $reply->status());
    }

    public function update(App $app, Request $request, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->put("document_type/{$id}", $request->all());
        return response($reply->json(), $reply->status());
    }

    public function destroy(App $app, string $id)
    {
        $http = $this->getHttp($app);
        $reply = $http->delete("document_type/{$id}");
        return response($reply->json(), $reply->status());
    }
}
