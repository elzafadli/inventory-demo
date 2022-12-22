<?php

namespace App\Models\Inv;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceipt extends Model
{
    protected $table = 'goods_receipt';

    protected  $rules = [
        "date" => "required",
        "date_expired" => "required",
        "productDetail" => "required",
    ];

    public function getRules()
    {
        return $this->rules;
    }

}
