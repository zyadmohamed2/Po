<?php
if (!function_exists('CategoryData')) {
    function CategoryData($categories)
    {
        $data = [];
        $url = "http://127.0.0.1:8000/";
        foreach ($categories as $category) {
            $photo = $category->photo;
            if ($category->photo !== Null) {
                $photo = $url . $category->photo;
            }
            $data[] = [
                'id' => $category->id,
                'title' => $category->title,
                'photo' => $photo,
            ];
        }

        return $data;
    }
}
if (!function_exists('BannerData')) {
    function BannerData($banners)
    {
        $data = [];
        $url = "http://127.0.0.1:8000/";
        foreach ($banners as $Banner) {
            $photo = $Banner->photo;
            if ($Banner->photo !== Null) {
                $photo = $url . $Banner->photo;
            }
            $data[] = [
                'id' => $Banner->id,
                'title' => $Banner->title,
                'description' => $Banner->description,
                'photo' => $photo,
            ];
        }

        return $data;
    }
}

if (!function_exists('ProducsData')) {
    function ProductsData($Products)
    {
        $data = [];
        $url = "http://127.0.0.1:8000/";
        foreach ($Products as $product) {
            $photo = $product->images->first()->path;
            if ($photo !== Null) {
                $photo = $url . $photo;
            }
            $data[] = [
                'id' => $product->id,
                'title' => $product->title,
                'summary' => $product->summary,
                'description' => $product->description,
                'Category' => $product->category->title,
                'brand' => $product->brand->title,
                'Price' => $product->sizes->first()->pivot->price,
                'discount' => $product->sizes->first()->pivot->discount,
                'condtion' => $product->condition,
                'rate' => $product->getReview->avg('rate'),
                'photo' => $photo,
            ];
        }
        return $data;
    }
    if (!function_exists('productDetails')) {
        function productDetails($product, $sizeId = null, $quantity = null)
        {
            $sizes = [];
            foreach ($product->sizes as $size) {
                $sizes[] = [
                    'id' => $size->id,
                    'name' => $size->name,
                    'abbreviation' => $size->abbreviation,
                    'price' => $size->pivot->price,
                    'discount' => $size->pivot->discount,
                    'quantity' => $size->pivot->quantity,
                ];
            }
            $url = "http://127.0.0.1:8000/";
            $images = [];
            foreach ($product->images as $image) {
                $photo = $image->path;
                if ($photo !== null) {
                    $photo = $url . $photo;
                    $images[] = $photo;
                }
            }
            $reviews = [];
            foreach ($product->getReview as $review) {
                $photo = $review->user_info->photo;
                if ($photo !== null) {
                    $photo = $url . $photo;
                }
                $reviews[] = [
                    'id' => $review->id,
                    'user_name' => $review->user_info->name,
                    'user_photo' => $photo,
                    'rate' => $review->rate,
                    'review' => $review->review,
                ];
            }
            $data = [
                'id' => $product->id,
                'title' => $product->title,
                'summary' => $product->summary,
                'description' => $product->description,
                'Category' => $product->category->title,
                'brand' => $product->brand->title,
                'discount' => $product->discount,
                'condition' => $product->condition,
                'rate' => $product->getReview->avg('rate'),
                'sizes' => $sizes,
                'images' => $images,
                'reviews' => $reviews,
            ];

            // Add cart information
            if ($sizeId !== null && $quantity !== null) {
                $data['cart_info'] = [
                    'size_id' => $sizeId,
                    'quantity' => $quantity,
                ];
            }

            return $data;
        }
    }

}
