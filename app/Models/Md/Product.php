<?php

namespace App\Models\Md;

use App\Rules\checkExistingProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory
    ;
    protected $table = 'products';
    protected $hidden = [
        'deleted_at'
    ];
    protected $fillable = ['name'];

    protected  $rules = [
        "name" => "required|unique:products,name|max:50",
       
    ];

    public function getRules()
    {
        return $this->rules;
    }
}
