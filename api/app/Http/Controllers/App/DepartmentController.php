<?php

namespace App\Http\Controllers\App;

use App\Models\App;

class DepartmentController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("department");
        return response($reply->json(), $reply->status());
    }
}
