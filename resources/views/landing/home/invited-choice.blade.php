<style media="screen">
    a, a:hover{ color: #383939; text-decoration: none; }
    .gradient-bg {
        background: linear-gradient(to bottom, #6EC058 0%, #88C657 100%);
        color: #fff !important
    }

    .gradient-bg .btn {
        background-color: #fff;
        color: #88C657;
    }
</style>

<section id="contact" class="section gradient-bg contact-section">
    <div class="container">
        <div class="text-center">
            <h2>Recebi um convite.</h2>
            <span class="f-300">Recebeu um convite de um usuario nosso? escolha uma opção cliente ou profissional e começe agora mesmo!</span>
        </div>

        <div class="text-center m-t-30">
            <a href="#" class="btn btn-success m-r-5" title="Convite cliente">Convite de cliente</a>
            <a href="#" class="btn btn-success " title="Convite profissional">Convite de profissional</a>
        </div>

    </div>
</section>

@section("scripts")
    @parent

@stop
