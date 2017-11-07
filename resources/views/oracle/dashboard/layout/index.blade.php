<!DOCTYPE html>
<html class="no-js">
<head>

    @include('components.seo-opengraph')

    <title>Oracle - iSaudavel</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
    <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>
    <meta name="google-site-verification" content="Ed4IGd5eqro1sXT8-Cz5eYyKFThT078JpfWLnQP3-VQ" />

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

    <style media="screen">
        html, body {
            overflow-x: hidden !important;
        }

        @media (max-width: 768px) {
            .navbar-default.navbar-fixed-top.animated .navbar-toggle,
            .navbar-default .navbar-toggle:hover,
            .navbar-default .navbar-toggle:focus { background-color: #71c158 !important; }

            .navbar-default .navbar-toggle { border-color: #fff; }
            .navbar-default .navbar-toggle .icon-bar { background-color: #fff; }
            .navbar-default .navbar-collapse { border-color: #71c158; }
            .navbar-default { background-color: #71c158 !important; }
            .navbar-default.navbar-fixed-top.animated { background: #fff !important; }
        }

        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table-vmiddle td {
            vertical-align: middle !important;
        }

        .page-title h2, .page-title h3 {
            display: inline-block;
        }

        /*
            Loader
        */
        .loading-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100000;
            background-color: rgba(255, 255, 255, .9);
        }

        .loading-spinner {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6EC058
        }

        .loader{
            width: 159px;
            text-align: center;
        }

        .iziToast>.iziToast-cover {
            background-color: transparent;
            margin-left: 20px;
        }

        .btn-notification{
            background-color: #6EC058 !important;
            color: #fff !important;
            font-size: 12px;

        }

        .iziToast>.iziToast-body>.iziToast-buttons {
            display: table-cell;
        }

        .iziToast>.iziToast-body>.iziToast-buttons>button {
            font-size: 14px;

        }

        [v-cloak] {
            display:none;
        }

    </style>
    @section('styles')

    @show

    <!-- Hotjar Tracking Code for https://isaudavel.com -->
   @include('components.hotjar')

</head>

<body id="body">

@php
$show_footer = isset($show_footer)? $show_footer : true;
$show_header = isset($show_header)? $show_header : true;
@endphp

@if(isset($show_header) && $show_header)
    @include('oracle.dashboard.layout.navbar')
@endif



@section('content')
@show

@if(isset($show_footer) && $show_footer)
    @include('landing.home.footer')
@endif


<!-- Js -->
<script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>


<!-- Firebase notification -->
<script src="https://www.gstatic.com/firebasejs/3.7.2/firebase.js"></script>

<script>

    var onSalesDashboard = "{{\Route::currentRouteName()}}"  == 'oracle.dashboard.sales.dashboard' ?true : false

    Vue.prototype.$eventBus = new Vue(); // Global event bus

    const vueOracle = new Vue()

    var config = {
        messagingSenderId: "823793769083"
    };

    firebase.initializeApp(config);

    const messaging = firebase.messaging();

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/firebase-messaging-sw.js').then((registration) => {
                // Successfully registers service worker
                //console.log('ServiceWorker registration successful');
                messaging.useServiceWorker(registration);
            })
                .then(() => {
                    // Requests user browser permission
                    return messaging.requestPermission();
                })
                .then(() => {
                    // Gets token
                    return messaging.getToken();
                })
                .then((token) => {
                    //console.log('token_armazenado: ' + token)
                    storeFcmToken(token)

                }).then(() => {

                return messaging.onMessage(function (payload) {
                    notificationHandler(payload.data)
                    vueOracle.$eventBus.$emit('increment-counter', 1)
                });
            })
                .catch((err) => {
                    console.log('ServiceWorker registration failed: ', err);
                });

        });
    }

    function storeFcmToken(token){

        $.ajax({
            type:'POST',
            url:'/api/oracle/fcm_token',
            data:{token : token, _token: "{{csrf_token()}}", is_mobile: false, user_id: "{{\Auth::user()->id}}"},
            success:function(data){
                //console.log('armazenado no db')
            }
        });
    }

    function notificationHandler(payload) {

        console.log(payload)

        if(!_.isEmpty(payload.type) && onSalesDashboard){
            vueOracle.$eventBus.$emit(payload.type)
            return false;
        }


        //Notification with button
        if (payload.button_label && payload.button_action) {
            iziToast.show({
                icon: 'icon-contacts',
                title: payload.title ? payload.title : '',
                message: payload.content,
                position: 'topCenter',
                image: payload.icon,
                imageWidth: 70,
                color: '#FFF',
                timeout: 0,
                layout: 2,
                buttons: [
                    [`<button>Ok</button>`, function (instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                        }, toast, 'close', 'btn2');
                    }, false],
                    [`<button class="btn-notification">${payload.button_label}</button>`, function (instance, toast) {
                        window.location.href= payload.button_action;
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                        }, toast, 'close', 'btn2');
                    }, true] // true to focus
                ],
                drag: false
            });
        }

        //Simple notification
        if (!payload.button_label || !payload.button_action) {
            iziToast.show({
                icon: 'icon-contacts',
                title: payload.title ? payload.title : '',
                message: payload.content,
                position: 'topCenter',
                image: payload.icon,
                imageWidth: 70,
                color: '#FFF',
                timeout: 0,
                layout: 2,
                buttons: [
                    [`<button>Ok</button>`, function (instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                        }, toast, 'close', 'btn2');
                    }, true]
                ],
                drag: false,
            });
        }
    }

</script>
@section('navbar-scripts')
@show

@section('scripts')
@show

</body>
</html>
