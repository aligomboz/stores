<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Events\OrderCopmleted;
use App\Events\OrderEvent;
use App\Notifications\orderCreatedNotification;
use App\Order;
use App\OrderProducts;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $cart = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('checkout', [
            'cart' => $cart
        ]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user(); //= Auth::user()
        DB::beginTransaction();
        try {
            $order =  $user->orders()->create([
                'status' => 'pending',
            ]);
            /*Order::create([
                'user_id' => Auth::id(),
                'status' =>'pending',
            ]);*/

            $cart = Cart::where('user_id', Auth::id())->get();
            $total = 0;
            foreach ($cart as $item) {
                $order->orderProducts()->create([
                    'product_id' => $item->product_id,
                    'quntity' => $item->quntity,
                    'price' => $item->price,
                ]);
                $total += $item->quntity * $item->price;
                /*
                OrderProducts::create([
                    'order_id' =>$order->id,
                    'product_id' => $item->product_id,
                    'quntity' =>$item->quntity,
                    'price' =>$item->price,
                ]);
                */
            }
            // Cart::where('user_id' ,Auth::id())->delete();

            $user->carts()->delete();
            Cookie::queue(Cookie::make('cart' , '' , -06));
            DB::commit();

            $user->notify(new orderCreatedNotification($order));
            
            event(new OrderEvent($order)); //trigger for eventC

            //return $this->paypal($order, $total);

            return redirect()->route('orders')
                ->with('success', __('Orders #:id Created', ['id' => $order->id]));
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    protected function paypal(Order $order, $total)
    {
        $client = $this->paypalClient();
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $order->id,
                "amount" => [
                    "value" => $total,
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "cancel_url" => url(route('paypal.cancel')),
                "return_url" => url(route('paypal.return'))
            ]
        ];
        try {
            $response = $client->execute($request);
            if ($response->statusCode == '201') {
                session()->put('paypal_order_id', $response->result->id);
                session()->put('order_id', $order->id);

                foreach ($response->result->links as $link) {
                    if ($response->link == 'approve') {
                        return redirect()->away($link->href);
                    }
                }
            }
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
        return 'Unknown Error!' . $response->statusCode;
    }
    protected function paypalClient()
    {
        $config = config('services.paypal');
        $env = new SandboxEnvironment($config['client_id'], $config['client_secret']);
        $client = new PayPalHttpClient($env);
        return $client;
    }

    public function paypalReturn()
    {
        $paypal_order_id = session()->get('paypal_order_id');
        $request = new OrdersCaptureRequest('paypal_order_id');
        $request->prefer('return=representation');
        try {
            $respons = $this->paypalClient()->execute($request);
            if ($respons->statusCode  == '201') {
                if (strtolower($respons->result->status) == 'COMPLETED') {
                    $id = session()->get('order_id');
                    $order = Order::findOrFail($id);
                    $order->status = 'completed';
                    $order->save();
                    event(new OrderCopmleted());
                    session()->forget(['order_id', 'paypal_order_id']);

                    return redirect()->route('orders')
                        ->with('success', __('Orders #:id Created', ['id' => $order->id]));
                }
            }
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
