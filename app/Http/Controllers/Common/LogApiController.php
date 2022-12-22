<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\LogApi;
use App\Models\Md\Product;
use App\Rules\checkExistingProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LogApiController extends Controller
{
    public $response = array(
        "status" => "S",
        "data" => null,
        "response" => ""
    );

    public function index()
    {
        $response = $this->response;

        $model = LogApi::query()->get();

        $response['response'] = $model;

        return $response;
    }
}
