<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'photo', 'status'];


    public static function getAllCategory()
    {
        return  Category::orderBy('id', 'DESC')->paginate(10);
    }
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')->where('status', 'active');
    }
    public static function getProductByCat($slug)
    {
        // dd($slug);
        return Category::with('products')->where('slug', $slug)->first();
        // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    }
    public static function countActiveCategory()
    {
        $data = Category::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }
}
