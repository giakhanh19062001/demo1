<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    public function stripe(Request $request)
    {
        // validator
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity'  => 'required|numeric|min:1',
            'price'  => 'required|integer|min:1',
        ]);

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

        try {
            $response = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $request->product_name,
                            ],
                            'unit_amount' => $request->price * 100,
                        ],
                        'quantity' => $request->quantity,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);
            if (isset($response->id) && $response->id != '') {
                session()->put('product_name', $request->product_name);
                session()->put('quantity', $request->quantity);
                session()->put('price', $request->price);

                return redirect($response->url);
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('cancel')->with('error', $e->getMessage());
        }
        return redirect()->route('cancel');
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        try {
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            $payment = new Payment();
            $payment->payment_id = $response->id;
            $payment->product_name = session()->get('product_name');
            $payment->quantity = session()->get('quantity');
            $payment->amount = session()->get('price');
            $payment->currency = $response->currency;
            $payment->payer_name = $response->customer_details->name;
            $payment->payment_email = $response->customer_details->email;
            $payment->payment_status = $response->status;
            $payment->payment_method = "Stripe";
            $payment->save();

            session()->forget('product_name');
            session()->forget('quantity');
            session()->forget('price');

            return response("Payment is successfully", 200);
        } catch (ApiErrorException $e) {
            return redirect()->route('cancel')->with('error', $e->getMessage());
        }
    }


    public function cancel()
    {
        return response("Cancel", 200);
    }
}
