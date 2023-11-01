<?php

namespace App\Http\Controllers\App;

use App\Models\App;

class DocumentTypeController extends Controller
{
    public function index(App $app)
    {
        $http = $this->getHttp($app);
        $reply = $http->get("document_type");
        return response($reply->json(), $reply->status());
    }
}
