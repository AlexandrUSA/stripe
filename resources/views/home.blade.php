<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <script src="https://js.stripe.com/v3/"></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            .spacer {
                margin-bottom: 24px;
            }

            .StripeElement {
              background-color: white;
              padding: 10px 12px;
              border-radius: 4px;
              border: 1px solid #ccd0d2;
              box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
              -webkit-transition: box-shadow 150ms ease;
              transition: box-shadow 150ms ease;
            }

            .StripeElement--focus {
              box-shadow: 0 1px 3px 0 #cfd7df;
            }

            .StripeElement--invalid {
              border-color: #fa755a;
            }

            .StripeElement--webkit-autofill {
              background-color: #fefde5 !important;
            }

            #card-errors {
                color: #fa755a;
            }
        </style>

    </head>
    <body>
        <div class="container">
            <div class="col-md-6 col-md-offset-3">
                <h1>Payment Form</h1>
                <div class="spacer"></div>

                @if (session()->has('success_message'))
                    <div class="alert alert-success">
                        {{ session()->get('success_message') }}
                    </div>
                @endif

                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('checkout') }}" method="POST" id="payment-form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <div class="form-group">
                        <label for="name_on_card">Имя владельца карты</label>
                        <input type="text" class="form-control" id="name_on_card" name="name_on_card">
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="postalcode">Почтовый индекс</label>
                                <input type="text" class="form-control" id="postalcode" name="postalcode">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Страна</label>
                                <input type="text" class="form-control" id="country" name="country">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="card-element">Кредитная карта</label>
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>

                    <div class="spacer"></div>

                    <button type="submit" class="btn btn-success btn-block">Оплатить 50$</button>
                </form>
            </div>
        </div>

        <script>
            (function(){

                const stripe = Stripe('{{ config('services.stripe.key') }}');
                const elements = stripe.elements();
                const style = {
                  base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Raleway", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                      color: '#aab7c4'
                    }
                  },
                  invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                  }
                };
                const card = elements.create('card', {
                    style: style,
                    hidePostalCode: true
                });
                const form = document.getElementById('payment-form');
                card.mount('#card-element');
                card.addEventListener('change', function(event) {
                  const displayError = document.getElementById('card-errors');
                  if (event.error) {
                    displayError.textContent = event.error.message;
                  } else {
                    displayError.textContent = '';
                  }
                });

                form.addEventListener('submit', function(event) {
                  event.preventDefault();

                  const options = {
                    name: document.getElementById('name_on_card').value,
                  }

                  stripe.createToken(card, options).then(function(result) {
                    if (result.error) {
                      const errorElement = document.getElementById('card-errors');
                      errorElement.textContent = result.error.message;
                    } else {
                      stripeTokenHandler(result.token);
                    }
                  });
                });

                function stripeTokenHandler(token) {
                  const hiddenInput = document.createElement('input');
                  hiddenInput.setAttribute('type', 'hidden');
                  hiddenInput.setAttribute('name', 'stripeToken');
                  hiddenInput.setAttribute('value', token.id);
                  form.appendChild(hiddenInput);
                  form.submit();
                }
            })();
        </script>
    </body>
</html>
