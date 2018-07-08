Stripe system

все работает как часы

в .env добавить

STRIPE_KEY=pk_test_ключ
STRIPE_SECRET=sk_test_ключ



в config/services.php добавить

'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],