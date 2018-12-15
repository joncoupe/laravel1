<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            table {
                width: 100%;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    E3Creative Technical Test
                </div>
                <div>
                    Enter your last birthday in the field below to find out what the exchange rate was in {{ env('FIXER_CURRENCY') }}.
                </div>
                <form method="post" action="{{ route('exrates.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="dob"></label>
                        <input type="date" class="form-control" name="dob" id="dob" value="{{ old('dob') }}">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Go</button>
                </form>
                <div class="links">
                    <table>
                        <thead>
                            <th>Dates</th>
                            <th>Rates in {{ env('FIXER_CURRENCY') }}</th>
                            <th>Occurrences</th>
                        </thead>
                        <tbody>
                        @foreach ($dobRates as $rate)
                            <tr>
                                <td>{{ $rate->view_date }}</td>
                                <td>{{ $rate->rate }}</td>
                                <td>{{ $rate->counts }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        {{ $dobRates->links() }}
                    </table>
                </div>

            </div>
        </div>
    </body>
</html>
