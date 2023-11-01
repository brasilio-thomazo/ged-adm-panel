<?php

namespace App\Http\Controllers\App;

use App\Models\App;

class UserController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("user");
        return response($reply->json(), $reply->status());
    }
}
