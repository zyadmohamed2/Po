<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPSTORM_META\elementType;

class OrderController extends Controller
{
    use HelperApi;

    public function createOrder(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
return $user;
            $validator = \Validator::make($request->all(), [
                'sub_total' => 'required|numeric',
                'shipping_id' => 'required|exists:shippings,id',
                'coupon' => 'nullable|numeric',
                'total_amount' => 'required|numeric',
                'payment_method' => 'required|in:cod,paypal',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'country' => 'required|string',
                'post_code' => 'nullable|string',
                'address1' => 'required|string',
                'address2' => 'nullable|string',
                'cart'=>'required'
            ]);

            if ($validator->fails()) {
                return $this->onError(500, 'Validation error', $validator->errors());
            }
            $subTotal = $request->sub_total;
            $shipping_id = $request->input('shipping_id');
            $paymentMethod = $request->input('payment_method');
            $couponCode = $request->input('coupon_code');
            $shipping = Shipping::find($shipping_id);
            $deliveryCharge = $shipping->price;
            $totalAmount = $subTotal + $deliveryCharge;
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => uniqid('order', true),
                'sub_total' => $subTotal,
                'delivery_charge' => $deliveryCharge,
                'total_amount' => $totalAmount,
                'first_name' => $user->name,
                'country' => $request->country,
                'post_code' => $request->post_code,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'phone' => $request->phone,
                'email' => $user->email,
                'payment_method' => $paymentMethod,
                'payment_status' => $request->paymentstatus,
                'shipping_id' => $shipping_id,
                'coupon' => $couponCode,
            ]);
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
                $product_all = Product::whereId($product['id'])->with('sizes')->first();
                $size = $product_all->sizes->where('size_id', $request['size_id'])->first();
                if ($product['quantity'] <= $size->pivot->quantity) {
                    // Assuming validation passes, create the OrderItem
                    $orderItem =  OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product['id'],
                        'size_id' => $product['size_id'], // This should be set dynamically
                        'quantity' => $product['quantity'],
                    ]);
                    $this->updateStock($product['id'], $product['size_id'], $product['quantity']);
                } else {
                    return $this->onError(500, 'Not enough stock for the selected size');
                }
            }

            $user->cart->products()->detach();
            DB::commit();

            return $this->onSuccess(200, 'Order placed successfully', $order);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->onError(500, 'An error occurred. Please try again', $e->getMessage());
        }
    }


    private function calculateSubTotal($cartItems)
    {
        $subTotal = 0;
        foreach ($cartItems as $cartItem) {
            $subTotal += $cartItem->price * $cartItem->pivot->quantity;
        }

        return $subTotal;
    }

    private function updateStock($productId, $sizeId, $quantity)
    {
        $productSize = ProductSize::where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->first();

        if ($productSize) {
            $productSize->quantity -= $quantity;
            $productSize->save();
        }
    }
}
