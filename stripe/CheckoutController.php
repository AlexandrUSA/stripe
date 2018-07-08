<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;


class CheckoutController extends Controller
{
    public function makePayment(Request $request)
    {
        try {
            $charge = Stripe::charges()->create([
                'amount' => 50,
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'Оплата за товар в моем интернет магазине',
                'receipt_email' => $request->email,
                'metadata' => [
                    'Товар' => 'ботинки деревенские',
                    'Размер' => '52',
                    'Цвет' => 'нежно упитанный',
                ],
            ]);
            return back()->with('success_message', 'Платеж совершен. Спасибо за покупку!');
        } catch (CardErrorException $e) {
            return back()->withErrors('Ошибка! ' . $e->getMessage());
        }
    }
}
