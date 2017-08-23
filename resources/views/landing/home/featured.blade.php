<style>


</style>

 <section id="contact" class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="block">
                    <div class="heading wow fadeInUp">
                        <h2>Em destaque</h2>
                        <p>Veja algumas empresas de destaque iSaudavel</p>
                    </div>
                </div>
            </div>
            @foreach($companies as $company)
                <div class="col-xs-12 col-sm-12 col-md-3 wow fadeInUp text-center">

                    <div class="block">
                        {{$company->name}}
                    </div>

                </div>
            @endforeach
        </div>

        <div class="row m-t-30">
            <div class="col-md-12 col-xs-12 text-center">
                Procure mais profissionais para te ajudar a atingir seus objetivos em saúde e estética
                <br>
                <button class="btn btn-primary m-t-10">Procurar profissionais</button>
            </div>
        </div>
    </div>
</section>