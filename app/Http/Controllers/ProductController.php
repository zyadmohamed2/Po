<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\Size;
use App\Services\ManageFileService;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::getAllProduct();
        $productQuantities = [];
        $sizesDetails = [];
        $products->each(function ($product) use (&$productQuantities, &$sizesDetails) {
            $totalQuantity = $product->sizes->sum('pivot.quantity');
            $productQuantities[$product->id] = $totalQuantity;
            $details = [];
            if ($product->sizes()->count() > 0) {
                $details = $product->sizes->map(function ($size) {
                    return [
                        'size' => "Size: " . $size->name . ", Quantity: " . $size->pivot->quantity . ", Price: " . $size->pivot->price
                    ];
                })->toArray();
            }
            $sizesDetails[$product->id] = $details;
        });
        return view('backend.product.index', [
            'products' => $products,
            'productQuantities' => $productQuantities,
            'sizesDetails' => $sizesDetails
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::getAllCategory();
        $sizes = Size::all();
        return view('backend.product.create', compact('brands', 'categories', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProductSizesQuantities(Request $request, Product $product)
    {
        $sizes = $request->sizes;
        $quantities = $request->quantities;
        $prices = $request->prices;
        $discounts = $request->discounts;
        for ($index = 0; $index < count($sizes); $index++) {
            $sizeId = $sizes[$index];
            $quantity = $quantities[$index];
            $price = $prices[$index];
            $discount = $discounts[$index];
            $product->sizes()->attach($sizeId, [
                'quantity' => $quantity,
                'price' => $price,
                'discount' => $discount,
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|nullable',
            'description' => 'string|nullable',
            'cat_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_featured' => 'sometimes|in:1',
            'is_poupular'=>'sometimes|in:1',
            'is_banner'=>'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'discount' => 'nullable|numeric',
            'sizes.*' => 'required|exists:sizes,id',
            'quantities.*' => 'required|integer|min:0',
            'prices.*' => 'required|numeric|min:0',
            'discounts.*' => 'nullable|numeric|min:0',
            'photos.*' => 'required',
        ]);
            DB::beginTransaction();
            $data = $request->all();
            $data['is_featured'] = $request->input('is_featured', 0);
            $data['is_poupular'] = $request->input('is_poupular', 0);
            $data['is_banner'] = $request->input('is_banner', 0);

            $product = Product::create($data);
            foreach ($request->photos as $file) {
                $path = ManageFileService::uploadFile($file, 'product_images');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
            $this->addProductSizesQuantities($request, $product);
            request()->session()->flash('success', 'Product Successfully added');
            DB::commit();
            return redirect()->route('product.index');
            try {

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Please try again!!']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::get();
        $category = Category::get();
        $product = Product::with('category', 'brand', 'sizes', 'images')->findOrFail($id);
        // return $items;
        return view('backend.product.edit')->with('product', $product)
            ->with('brands', $brand)
            ->with('categories', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|nullable',
            'description' => 'string|nullable',
            'cat_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'discount' => 'nullable|numeric',
            'sizes.*' => 'required|exists:sizes,id',
            'quantities.*' => 'required|integer|min:0',
            'prices.*' => 'required|numeric|min:0',
            'discounts.*' => 'nullable|numeric|min:0',
        ]);
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['is_featured'] = $request->input('is_featured', 0);
            $product = Product::with('images')->findOrFail($id);
            $product->update($data);
            $product->sizes()->detach();
            $this->addProductSizesQuantities($request, $product);
            if ($request->hasFile('photos')) {
                $images = $product->images;
                foreach ($images as $image) {
                    ManageFileService::deleteFile($image->path);
                }
                foreach ($request->photos as $file) {
                    $path = ManageFileService::uploadFile($file, 'product_images');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                    ]);
                }
            }
            request()->session()->flash('success', 'Product Successfully updated');
            DB::commit();
            return redirect()->route('product.index');
        } catch (\Exception $e) {
            DB::rollBack();
            //print($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Please try again!!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $images = $product->images;
        $status = $product->delete();
        if ($status) {
            foreach ($images as $image) {
                ManageFileService::deleteFile($image->path);
            }
            request()->session()->flash('success', 'Product successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
