<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function bill () {
        $stripe = new StripeClient(config('app.stripe_secret'));

        $session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => 100 * 5,
                        'product_data' => [
                            'name' => 'Testa produkts',
                            'description' => "Parasts produkts, ar parastu cenu",
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1/api/success'.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1/api/failed',
        ]);
        dd($session);
    }

    public function successPayment (Request $request) {
        $stripe = new StripeClient(config('app.stripe_secret'));
        // Viss strādā, ja session_id nesakrīt, tad metīs erroru, vajaga novalidēt erroru,
        // Ja sakrīt session_id, tad smuki metode nostrādās bez errora
        $checkoutSession = $stripe->checkout->sessions->retrieve($request->get('session_id'));
    }

    public function failedPayment () {
        dd('failed');
    }
}
