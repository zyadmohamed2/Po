<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\ManageFileService;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    use HelperApi;
    public  function getAllCategories()
    {
        $categories = Category::where('status', 'active')->get();
        return $this->onSuccess(200, __('site.all_cat'), CategoryData($categories));
    }

    public  function getAllBanners()
    {

        $products = Product::where('status', 'active')->where('is_banner', 1)->with(['category', 'brand', 'images', 'getReview'])->get();
        return $this->onSuccess(200, 'All Banners', ProductsData($products));
    }

    public function getAllProducts()
    {
        $products = Product::where('status', 'active')->with(['category', 'brand', 'images', 'getReview'])->get();
        return $this->onSuccess(200, 'All Products', ProductsData($products));
    }
    public function getCategoryProducts($cat_id)
    {
        $products = Product::where('status', 'active')->where('cat_id', $cat_id)->with(['category', 'brand', 'images', 'getReview'])->get();
        return $this->onSuccess(200, 'Products on this Categroy', ProductsData($products));
    }
    public function getPopularProducts()
    {
        $products = Product::where('status', 'active')->where('is_popular', 1)->with(['category', 'brand', 'images', 'getReview'])->get();
     return $this->onSuccess(200, 'Popular Products', ProductsData($products));
    }
    public function getProduct($id)
    {
        $product = Product::with(['category', 'brand', 'images', 'getReview', 'sizes'])->find($id);
        return $this->onSuccess(200, "Product Detials", productDetails($product));
    }
    public function addReview(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1',
            'review' => 'nullable|string',
            'product_id' => 'exists:products,id',
            'user_id' => 'exists:users,id',
        ]);
        if ($validator->fails()) {
            return $this->onError(500, 'Valdtion error', $validator->errors());
        }
        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rate' => $request->rate,
            'review' => $request->review,
        ]);
        return $this->onSuccess(200, 'Review added successfully', $review);
    }
}
