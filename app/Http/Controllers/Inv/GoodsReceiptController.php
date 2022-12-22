<?php

namespace App\Http\Controllers\Inv;

use App\Helpers\UtilNoBukti;
use App\Http\Controllers\Controller;
use App\Models\Inv\GoodsReceipt;
use App\Models\Inv\GoodsReceiptDetail;
use App\Models\Common\LogApi;
use App\Models\Inv\ProductStock;
use App\Models\Md\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GoodsReceiptController extends Controller
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
        $model = new GoodsReceipt();
        $validator = Validator::make($request->all(), $model->getRules());


        if ($validator->fails()) {
            $response['response'] = $validator->messages();
            $response['status'] = "E";
        } else {

            DB::beginTransaction();
            try {
                $model->nonota   = UtilNoBukti::generate('INV/GR');
                $model->date = $request->date;
                $model->date_expired = $request->date_expired;
                $model->description = $request->description;
                $model->user_created = 'admin';

                $model->save();

                $tempDetail = [];
                $isError = false;

                //insert detail product
                foreach ($request->productDetail as $r) {
                    $detail = new GoodsReceiptDetail();

                    // check product id exist or not
                    $product = Product::where([
                        'id' => $r['product_id'],
                        'deleted_at' => null
                    ])->first();

                    $detail->goods_receipt_id = $model->id;
                    $detail->amount = $r['amount'];

                    if ($product) {
                        $detail->product_id = $r['product_id'];
                        $detail->product_name = $product->name;

                        $detail->save();
                    } else {

                        $detail->product_id = "Product ID not found";
                        $detail->product_name = "Product not found";

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

                    // insert to stock
                    $tempStock = [];
                    foreach ($model->detail as $r) {
                        for ($i = 0; $i < $r->amount; $i++) {

                            $stock = [
                                'nonota' => $model->nonota,
                                'date_expired' => $model->date_expired,
                                'amount' => 1,
                                'product_id' => $r->product_id,
                                'product_name' => $r->product_name,
                                'created_at' => Carbon::now()
                            ];

                            array_push($tempStock, $stock);
                        }
                    }

                    ProductStock::insert($tempStock);

                    DB::commit();
                }
            } catch (\Exception $e) {
                return $e;
            }
        }

        //insert log api
        $log = new LogApi();
        $log->insertLog($request, 'Create Goods Receipt');

        return $response;
    }
}
