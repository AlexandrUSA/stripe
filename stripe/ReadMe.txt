Stripe system

��� �������� ��� ����

� .env ��������

STRIPE_KEY=pk_test_����
STRIPE_SECRET=sk_test_����



� config/services.php ��������

'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],