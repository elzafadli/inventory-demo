<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use App\Models\Md\Product;
use App\Rules\checkExistingProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public $response = array(
        "status" => "S",
        "data" => null,
        "response" => ""
    );

    public function index()
    {
        $response = $this->response;

        $model = Product::where([
            'deleted_at' => null
        ])->get();

        $response['response'] = $model;

        return $response;
    }


    public function create(Request $request)
    {
        $response = $this->response;

        // validate request using rules in model
        $model = new Product();
        $validator = Validator::make($request->all(), $model->getRules());

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
            $response['status'] = "E";
        } else {

            $model->name   = $request->name;
            $model->save();

            $response['data'] = $model;
            $response['response'] = "Data saved successfully";
        }

        return $response;
    }

    public function update(Request $request)
    {
        $response = $this->response;

        $model = Product::where([
            'id' => $request->id,
        ])->first();

        if ($model) {
            // validate request using rules in model
            $validator = Validator::make($request->all(), $model->getRules());

            if ($validator->fails()) {
                $response['response'] = $validator->messages();
                $response['status'] = "E";
            } else {

                $model->name   = $request->name;
                $model->save();

                $response['data'] = $model;
                $response['response'] = "Data saved successfully";
            }
        } else {
            $response['response'] = "Product not found";
            $response['status'] = "E";
        }

        return $response;
    }

    public function delete($id)
    {
        $response = $this->response;

        // check model exist or not
        $model = Product::where([
            'id' => $id,
            'deleted_at' => null
        ])->first();

        if ($model) {
            $model->delete();

            $response['response'] = "Data deleted successfully";
        } else {

            $response['response'] = "Product not found";
            $response['status'] = "E";
        }

        return $response;
    }
}
