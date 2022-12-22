<?php

namespace App\Http\Controllers\Inv;

use App\Helpers\UtilNoBukti;
use App\Http\Controllers\Controller;
use App\Models\Common\LogApi;
use App\Models\Inv\GoodsIssue;
use App\Models\Inv\GoodsIssueDetail;
use App\Models\Inv\ProductStock;
use App\Models\Md\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GoodsIssueController extends Controller
{
    public $response = array(
        "status" => "S",
        "data" => null,
        "response" => ""
    );

    public function create(Request $request)
    {
        $response = $this->response;

        // validate request using rules in model
        $model = new GoodsIssue();
        $productStock = new ProductStock();

        $validator = Validator::make($request->all(), $model->getRules());

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
            $response['status'] = "E";
        } else {

            DB::beginTransaction();
            try {
                $model->nonota   = UtilNoBukti::generate('INV/GI');
                $model->date = $request->date;
                $model->description = $request->description;
                $model->user_created = 'admin';

                $model->save();

                $tempDetail = [];
                $isError = false;

                //insert detail product
                foreach ($request->productDetail as $r) {
                    $detail = new GoodsIssueDetail();

                    // check product id exist or not
                    $product = Product::where([
                        'id' => $r['product_id'],
                        'deleted_at' => null
                    ])->first();


                    $detail->goods_issue_id = $model->id;

                    if ($product) {
                        $detail->product_id = $r['product_id'];
                        $detail->product_name = $product->name;

                    } else {

                        $detail->product_id = "Product ID not found";
                        $detail->product_name = "Product not found";

                        $isError = true;
                    }

                    // check stock available
                    if($productStock->checkStockAvailable($r['product_id'], $r['amount'])){
                        $detail->amount = $r['amount'];

                        $detail->save();
                    }else{
                        $detail->amount = "Stock not available";

                        $isError = true;
                    }

                    array_push($tempDetail, $detail);
                };

                $model->detail =  $tempDetail;

                if ($isError) {
                    $response['data'] = $model;
                    $response['status'] = 'E';
                    $response['response'] = "Data saved unsuccessfully";

                    DB::rollBack();
                } else {
                    $response['data'] = $model;
                    $response['response'] = "Data saved successfully";

                 

                    DB::commit();
                }
            } catch (\Exception $e) {
                return $e;
            }
        }

        //insert log api
        $log = new LogApi();
        $log->insertLog($request, 'Create Goods Issue');

        return $response;
    }
}
