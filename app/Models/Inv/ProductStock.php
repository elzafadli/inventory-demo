<?php

namespace App\Models\Inv;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductStock extends Model
{
    protected $table = 'product_stock';

    protected  $rules = [
        "tgl" => "required",
        "tgl_expired" => "required",
        "productDetail" => "required",
    ];

    public function getRules()
    {
        return $this->rules;
    }

    public function checkStockAvailable($productId, $amount)
    {
        $model = ProductStock::where([
            'product_id' => $productId,
            'status' => null,
        ])
            ->where('date_expired', '>', DB::raw('CURDATE()'))
            ->orderBy('date_expired', 'desc')
            ->limit($amount)
            ->get();

        $stock = count($model);
        
        if ($stock == $amount) {
            ProductStock::whereIn('id', $model->pluck('id'))
                ->update(['status' => 'out']);

            return true;
        } else {
            return false;
        }
    }
}
