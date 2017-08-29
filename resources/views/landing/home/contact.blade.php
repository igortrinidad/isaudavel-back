<style media="screen">
    a, a:hover{ color: #383939; text-decoration: none; }
</style>

<section id="contact" class="section contact-section">
    <div class="container">
        <div class="text-center">
            <h2>Contato</h2>
            <span class="f-300">Dúvidas? Entre em contato agora mesmo!</span>
        </div>
        <form class="wow fadeInUp" action="index.html" method="post">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control f-300 p-t-25 p-b-25" id="contact-name" placeholder="Nome">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control f-300 p-t-25 p-b-25" id="contact-email" placeholder="email@exemplo.com.br">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control f-300 p-t-25 p-b-25" id="contact-subject" placeholder="Assunto">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group" id="teste">
                        <textarea class="form-control f-300 p-t-25 p-b-25" id="contact-msg" rows="5" placeholder="Sua mensagem"></textarea>
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
            event.preventDefault()

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
                console.log();
                swal({
                    title: "Todos os campos são obrigatórios!",
                    text: "Os campos: " + errors.join(", ") + " são obrigatórios.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#E14A45",
                    confirmButtonText: "Voltar para o formulário",
                    closeOnConfirm: false
                })
            }
            if (!errors.length) {
                $("#contact-name").val("")
                $("#contact-email").val("")
                $("#contact-subject").val("")
                $("#contact-msg").val("")

                swal({
                    title: "Mensagem enviada!",
                    text: "Sua mensagem foi enviada com sucesso, em breve entraremos em contato.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#00A369",
                    confirmButtonText: "Voltar",
                    closeOnConfirm: false
                })
            }

        })
    </script>
@stop
