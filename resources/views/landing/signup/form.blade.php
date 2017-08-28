<style media="screen">
    .contact-signup {
        background-image: url('https://weplaces.com.br/build/landing/weplaces/road_california.jpg');
        min-height: 100vh;
        padding: 0;
    }

    .entry-field label{
        color: #fff;
        font-weight: 400;
        display: block;
        margin-top: 10px;
    }
    .entry-field input{
        color: #757575;
        font-weight: 400;
    }
   .contact-signup {
        position: relative;
        background-position: center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
   }
   .contact.gradient-overlay-black .overlay-inner {
        min-height: 100vh;
        background: -webkit-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
        background: -o-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
        background: -moz-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
        background: linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
   }

   .contact-form input, .contact-form textarea,
   .contact-form { width: 100%; }

   .block-center { margin: 0 auto !important; float: none; }

   .contact-form input, .contact-form textarea{ background-color: #fff; }
   .form-control{ height: 46px; }
   option{ padding: 0px 2px 1px 10px; }

</style>
<section id="contact" class="contact contact-signup section gradient-overlay-black">
  <div class="overlay-inner">
    <div class="container">
      <!--|Section Header|-->
      <header class="section-header text-center wow flipInX" data-wow-delay=".15s">
        <div class="row">
          <div class="col-6 block-center">
            <h1 class="section-title" style="padding-top: 100px; ">Você esta quase lá!</h1>
            <h4 class="f-300 m-t-30" style="color: #fff; margin-bottom: 40px;">Falta pouco para você oferecer uma experiência única para seus clientes e vender mais.</h4>
          </div>
        </div>
      </header> <!--|End Section Header|-->

      <div class="row">
        <div class="col-md-8 block-center">
          <!--|Contact Form|-->
          <form class="contact-form" method="POST" action="/sendSignupForm">
          {!! csrf_field() !!}
            <!--|Action Message|-->
            <div class="entry-field">
               <label>Nome completo</label>
               <input class="form-control" name="name" placeholder="Nome completo" required type="text">
            </div>
            <div class="entry-field">
               <label>CPF</label>
               <input class="form-control" name="cpf" placeholder="CPF" required type="text">
            </div>
            <div class="entry-field">
               <label>Endereço completo</label>
               <input class="form-control" name="address" placeholder="Endereço completo" required type="text">
            </div>
            <div class="entry-field">
               <label>Telefone</label>
               <input class="form-control" name="phone" placeholder="Telefone com ddd" required type="text">
            </div>
            <div class="entry-field">
               <label>Email</label>
               <input class="form-control" name="email" placeholder="email@exemplo.com" required type="email">
            </div>
            <div class="entry-field">
               <label>Empresa</label>
               <input class="form-control" name="company_name" placeholder="Nome da empresa" required type="text">
            </div>
            <div class="entry-field">
               <label>Plano</label>
               <select class="form-control" name="plan_selected">
                    <option>Anual R$39,00 / mês - R$468,00 por 12 meses</option>
                    <option>Semestral R$49,00 / mês - R$294,00 por 6 meses</option>
                    <option>Mensal R$79,00 / mês - R$79,00 por mês</option>
               </select>
            </div>

            <div class="text-center m-t-30">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Cadastrar</button>
            </div>
          </form> <!--|End Contact Form|-->
        </div>
      </div>
    </div>
  </div>
</section>
