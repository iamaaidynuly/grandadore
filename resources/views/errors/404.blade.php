<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 Custom Error Page Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

</head>

<body>

<style>
        .home-back {
            border-radius: 0.25rem;
            border: 1px solid #4F4F4F;
            font-size: 24px;
            font-family: "Roboto";
            color: #676767;
            font-weight: 400;
            background-color: #fff;
            padding: 0;
            padding: 8px;
            box-sizing: border-box;
            min-width: 120px;
            text-decoration: none;
            transition: 0.4s all;
            -webkit-transition: 0.4s all;
            -moz-transition: 0.4s all;
            text-decoration: none;
            margin-top: 40px;
        }

        .home-back:hover {
            text-decoration: none;
            background-color: #676767;
            color: #fff;
        }

        span {
            font-size: 24px;
            font-family: "Roboto";
            color: #676767;
            font-weight: 400;
            margin-bottom: 40px;
        }

        svg {
            width: 100%;
            height: auto;
        }

        @media (max-width: 991px) {
            span {
                margin-bottom: 20px;
                font-size: 20px;
            }

            .home-back {
                margin-top: 20px;
                font-size: 20px;
            }
        }

        @media (max-width: 575px) {
            span {
                margin-bottom: 18px;
                font-size: 14px;
            }

            .home-back {
                margin-top: 18px;
                font-size: 14px;
            }
        }
</style>

<div class="container mt-5 pt-5 d-flex justify-content-center align-items-center">
    <div class="page404 d-flex flex-column align-items-center">
        <span>Страница не существует</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 624 254">
            <path id="page404" d="M118.218,65.682V7.352H6V-20.794L108.016-179.841H156.3V-24.864H184.87V7.352H155.964v58.33Zm.34-90.544V-143.216L42.386-24.864ZM316.469,69.751q-25.164,0-41.486-9.156a66.121,66.121,0,0,1-25.67-25.774Q239.957,18.2,236.387-5.194a345.511,345.511,0,0,1-3.571-51.885,303.127,303.127,0,0,1,4.08-51.885q4.08-23.4,14.112-40.186a69.555,69.555,0,0,1,26.868-25.943q16.832-9.156,42-9.156t41.147,9.156a64.753,64.753,0,0,1,24.994,25.6q9.011,16.447,12.412,39.168a337.922,337.922,0,0,1,3.4,49.851,338.156,338.156,0,0,1-3.91,53.92Q394,17.522,384.31,34.479A67.5,67.5,0,0,1,358.126,60.6Q341.633,69.751,316.469,69.751Zm1.02-31.2q13.6,0,22.1-6.444t13.092-18.652q4.595-12.208,6.293-30.352t1.7-41.543q0-22.382-1.36-39.677t-5.781-29.334q-4.421-12.039-12.752-18.143t-21.933-6.1q-13.6,0-22.274,6.275T282.8-127.108q-5.1,12.039-6.971,29.843A395.448,395.448,0,0,0,273.963-56.4q0,22.382,1.7,40.017t6.293,29.843q4.595,12.208,13.262,18.652t22.274,6.444Zm245.856,27.13V7.352H451.13V-20.794L553.146-179.841h48.287V-24.864H630V7.352H601.094v58.33Zm.34-90.544V-143.216L487.517-24.864Z" transform="translate(-6 184.249)" fill="#000000"/>
        </svg>
        <a class="home-back" href="{{ url('/') }}">Вернутся на главную страницу</a>
    </div>
</div>
</body>

</html>
