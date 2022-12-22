<?php

namespace App\Models\Inv;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsIssue extends Model
{
    protected $table = 'goods_issue';

    protected  $rules = [
        "date" => "required",
        "productDetail" => "required",
    ];

    public function getRules()
    {
        return $this->rules;
    }

}
