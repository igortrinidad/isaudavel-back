<style>
    footer {
        width: 100%;
        padding: 30px 0;
        color: #F4F5F5;
    }
    footer hr {
        border-color: #F4F5F5;
        border-width: 1px;
        opacity: .3;
    }
    footer img { width: 140px; }
    footer a {
        color: #F4F5F5 !important;
        display: block;
        width: 100%;
        margin-top: 3px;
        font-weight: 300;
        text-align: left;
    }
    footer h4 { color: #F4F5F5; text-align: left; }

    @media (max-width: 768px) {
        footer a,
        footer h4,
        footer .footer-logo { text-align: center; }
    }

    @media (min-width: 768px) {
        .footer-logo{ margin-top: 30px; }
    }
</style>
<footer>

    <div class="container">
        <div class="row">

            <div class="col-sm-3 col-xs-12">
                <div class="footer-logo">
                    <img src="/logos/LOGO-1-04.png" width="200px" alt="Places Weplanner">
                </div>
            </div>

            <div class="col-sm-9 col-xs-12">
                <div class="col-sm-4 col-xs-12 m-b-20">
                    <h4 class="f-400">Sobre</h4>
                    <a href="{{ route('landing.terms') }}">
                        Termos de uso
                    </a>
                    <a  href="{{ route('landing.privacy') }}">
                        Política de Privacidade
                    </a>
                </div>

                <div class="col-xs-12 hidden-lg hidden-md hidden-sm">
                    <hr>
                </div>

                <div class="col-sm-4 col-xs-12 m-b-20">
                    <h4 class="f-400">Acesso</h4>
                    <a href="https://play.google.com/store/apps/details?id=com.isaudavel">Download para android</a>
                    <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8">Download para iphone - Em breve!</a>
                </div>

                <div class="col-xs-12 hidden-lg hidden-md hidden-sm">
                    <hr>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <h4 class="f-400">Social</h4>
                    <a href="https://facebook.com/isaudavel" target="_blank">
                        Facebook
                    </a>
                    <a href="https://instagram.com/isaudavel.app" target="_blank">
                        Instagram
                    </a>
                </div>



            </div>

        </div>

        <div class="col-xs-12 hidden-lg hidden-md hidden-sm m-t-10">
            <hr>
        </div>

        @if(Auth::guard('oracle_web')->guest())
            <div class="center-block">
                <a class=" btn btn- no-decoration" href="{{route('oracle.login')}}" target="_blank" >
                    Administração iSaudavel
                </a>
            </div>
        @endif

    </div>

</footer>
