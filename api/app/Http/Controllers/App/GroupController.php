<?php

namespace App\Http\Controllers\App;

use App\Models\App;

class GroupController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("group");
        return response($reply->json(), $reply->status());
    }
}