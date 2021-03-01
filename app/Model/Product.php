<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id','product_name','product_quantity','product_image', 'selling_price','product_code','buying_price','supplier_id','purchase_date'
    ];
}
