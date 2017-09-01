<style media="screen">
    a, a:hover{ color: #383939; text-decoration: none; }
</style>

<section id="contact" class="section contact-section p-30 p-r-0 p-l-0">
    <hr>
    <div class="container">
        <div class="text-center">
            <h2>Contato</h2>
            <span class="f-300">Dúvidas? Entre em contato agora mesmo!</span>
        </div>
        <form class="wow fadeInUp" action="{{route('landing.send-contact-form')}}" method="post">
            {!! csrf_field() !!}
            <div class="row">


                <div class="col-sm-12">
                    {{--Alert display--}}
                    @include('flash::message')
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control f-300 p-t-25 p-b-25" id="contact-name" name="name" required placeholder="Nome e sobrenome (obrigatório)">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="email" class="form-control f-300 p-t-25 p-b-25" id="contact-email" name="email" required placeholder="email@exemplo.com.br (obrigatório)">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control f-300 p-t-25 p-b-25" id="contact-subject" name="subject" required placeholder="Assunto (obrigatório)">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group" id="teste">
                        <textarea class="form-control f-300 p-t-25 p-b-25" id="contact-msg" rows="5" name="message" required placeholder="Sua mensagem (obrigatório)"></textarea>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <button id="contact-submit" type="submit" class="btn btn-lg btn-primary f-300" name="button">
                        <i class="ion-ios-paperplane-outline m-r-5"></i>
                        <span style="text-transform: uppercase">Enviar</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

@section("scripts")
    @parent

    <script>
        $("#contact-submit").on("click", function(event){
            var errors = []
            if ($("#contact-name").val() === "") {
                errors.push("Nome")
            }
            if ($("#contact-email").val() === "") {
                errors.push("E-mail")
            }
            if ($("#contact-subject").val() === "") {
                errors.push("Assunto")
            }
            if ($("#contact-msg").val() === "") {
                errors.push("Mensagem")
            }
            if (errors.length) {
                var startMessage = errors.length > 1 ? "Os campos: " : "O campo: "
                var errorFields =  errors.join(", ")
                var endMessage = errors.length > 1 ? " são obrigatórios" : " é obrigatório"


                swal({
                    title: "Todos os campos são obrigatórios!",
                    text: startMessage + errorFields + endMessage,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#E14A45",
                    confirmButtonText: "Voltar para o formulário",
                    closeOnConfirm: false
                })
                event.preventDefault()
            }
        })
    </script>
@stop
