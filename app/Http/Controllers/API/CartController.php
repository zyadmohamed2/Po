<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use HelperApi;

    public function getUserCart()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            $cart = Cart::where("user_id", $user->id)->first();

            if ($cart) {
                return $cart;
            }

            $cart = Cart::create(["user_id" => $user->id]);
            return $cart;
        }

        return $this->onError(500, "Need to Login First");
    }
    public function getCartItems()
    {
        $cart = $this->getUserCart();
        $products = $cart->products;
        $data = [];
        foreach ($products as $product) {
            $data[] = productDetails($product, $product->pivot->size_id, $product->pivot->quantity);
        }
        return $data;
    }
    /*
    public function addProductToCart(Request $request)
    {
        $cart= $request['cart'];



        // Now, we loop over each product and store the details
        foreach ($cart as $product) {
            OrderItem::create([
                'order_id' =>1,

                'product_id' => $product['id'],
                'size_id' => 1,
                'quantity' => $product['quantity'],
        ]);
        }
        return true;
        /*
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->onError(500, 'Validation error', $validator->errors());
        }
        $product = Product::whereId($request->product_id)->with('sizes')->first();
        $size = $product->sizes->where('id', $request->size_id)->first();
        if ($request->quantity <= $size->pivot->quantity) {
            $item = CartProduct::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $request->quantity,
            ]);
            if ($item) {
                return $this->onSuccess(200, "Product added to cart ", $item);
            }
            return $this->onError(500, "An error occurred. Please try again");
        } else {
            return $this->onError(500, 'Not enough stock for the selected size');
        }
    }


    public function addProductToCart(Request $request)
{
    $cart = json_decode($request['cart'], true);
    if (!is_array($cart)) {
        return response()->json(['message' => 'Invalid cart data provided. An array is expected.'], 400);
    }
        return $cart;
    // Validation for each product in the cart
    foreach ($cart as $product) {

        $validator = Validator::make($product, [
            'id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1', // assuming quantity must be at least 1
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Use 422 for validation error
        }
    }

    // If validation passes, proceed to store each product detail
    foreach ($cart as $product) {
        $orderItem = OrderItem::create([
            'order_id' => 1, // You might want to dynamically set this ID
            'product_id' => $product['id'],
            'size_id' => 1, // This should be dynamic as well, based on the size selected by the user
            'quantity' => $product['quantity'],
        ]);

        // Update stock if necessary
        // $this->updateStock($product['id'], 1, $product['quantity']); // Uncomment and implement this method
    }

    // Return a successful response
    return response()->json(['message' => 'Products added to order successfully'], 200);
}
   */
public function addProductToCart(Request $request)
{

    // Decode JSON payload into an array if it's sent as a JSON string
    $cart = json_decode($request['cart'], true);
    // Check if $cart is an array before attempting to loop over it
    if (!is_array($cart)) {
        return response()->json(['message' => 'Invalid cart data provided. An array is expected.'], 400);
    }

    // Now, we loop over each product and store the details
    foreach ($cart as $product) {
        // Validation logic here...
        // Ensure that $product is an array and has 'id' and 'quantity' keys
        if (!is_array($product) || !isset($product['id'], $product['quantity'])) {
            // Handle validation failure...
        }

        // Assuming validation passes, create the OrderItem
        OrderItem::create([
            'order_id' => 1, // This should be set dynamically
            'product_id' => $product['id'],
            'size_id' => 1, // This should be set dynamically
            'quantity' => $product['quantity'],
        ]);
    }

    // If everything goes well
    return response()->json(['message' => 'Products added to order successfully'], 200);
}



        public function updateProductInCart(Request $request, $id)
    {
        $cart = $this->getUserCart();
        $validator = \Validator::make($request->all(), [
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->onError(500, 'Validation error', $validator->errors());
        }
        $existingItem = CartProduct::find($id);

        if ($existingItem) {
            $oldQuantity = $existingItem->quantity;
            $oldSize = $existingItem->size_id;
            $product = Product::whereId($existingItem->product_id)->with('sizes')->first();
            $size = $product->sizes->where('id', $request->size_id)->first();
            if ($request->quantity <= $size->pivot->quantity) {
            $existingItem->update([
                'quantity' => $request->quantity,
                'size_id' => $request->size_id,
            ]);
            return $this->onSuccess(200, "Product quantity and size updated in the cart", $existingItem);
        }else{
            return $this->onError(500, 'Not enough stock for the selected size');
        }
        }
        return $this->onError(404, 'Item not found in the cart');
    }

    public function deleteProductInCart($id)
    {
        $status = CartProduct::where("id", $id)->delete();
        if ($status) {
            return $this->onSuccess(200, "Product deleted from cart ");
        }
        return $this->onError(500, "An error occurred. Please try again");
    }
}
