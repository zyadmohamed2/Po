<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=['order_number','user_id','sub_total','delivery_charge','status','total_amount','first_name','country','post_code','address1','address2','phone','email','payment_method','payment_status','shipping_id','coupon'];

    public function shipping(){
        return $this->belongsTo(Shipping::class,'shipping_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'cart_id', 'product_id')->withPivot(['size_id', 'quantity'])
            ->with(['category', 'brand', 'images', 'getReview', 'sizes']);
    }




}
