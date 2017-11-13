    
    <!--Free -->
    <div class="col-sm-4 col-sm-offset-2">
        <div class="card">
            <div class="card-header ch-alt text-center">
                <h1 class="m-0 m-b-10">FREE</h1>
                <p class="f-300 m-0"><small>Isso mesmo, é de graça!</small></p>

                <span class="f-20 payment">
                    <strong class="payment-currency">R$</strong>
                    <span class="payment-value">
                        <strong class="m-r-0">0</strong>
                        <small style="margin-left: -3px;">,00</small>
                    </span>

                    {{-- Isso aqui é só um exemplo --}}
                    <span class="payment-duration">
                        para sempre!
                    </span>

                </span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group m-0 text-center">
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Divulgar perfil da empresa
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Notificações push
                        <p class="f-12">Receba notificações quando seus clientes remarcarem ou cancelarem uma aula, quando você receber avaliações e mais</p>
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Destaque sua empresa na pesquisa
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Agendamento online
                        <p class="f-12">Seus clientes podem remarcar as aulas por conta própria</p>
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Controle avaliações físicas de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Controle fichas de treinamento de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Controle dietas de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-close-empty m-r-5 c-red plan-check-close-icon"></i>
                        Gerencie seus clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Adicionar profissionais
                        <p class="f-12">incluso 1 usuário profissional (R$19,90 / profissional extra)</p>
                    </li>
                </ul>

                @if(!\Auth::guard('professional_web')->guest())
                    <a href="{{route('landing.signup.company', ['plan' => 'free'])}}"class="btn btn-xs btn-block btn-success p-10 f-16 m-t-10" title="Quero este!">Cadastrar empresa</a>
                @else
                    <p class="text-center m-t-20 m-b-10">Faça login para cadastrar sua empresa</p>
                    <a class="btn btn-xs btn-block btn-primary p-10 f-16" @click.prevent="goToLogin()">Fazer login</a>
                @endif

            </div>
        </div>
    </div>
    <!--Free -->

    <!--PREMIUM -->
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header ch-alt text-center">
                <h1 class="m-0 m-b-10">PREMIUM</h1>
                <p class="f-300 m-0"><small>Apenas</small></p>

                <span class="f-20 payment">
                    <strong class="payment-currency">R$</strong>
                    <span class="payment-value">
                        <strong class="m-r-0">57</strong>
                        <small style="margin-left: -3px;">,90 / mês</small>
                    </span>

                </span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group m-0 text-center">
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Divulgar perfil da empresa
                    </li>

                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Notificações push
                        <p class="f-12">Receba notificações quando seus clientes remarcarem ou cancelarem uma aula, quando você receber avaliações e mais</p>
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Destaque sua empresa na pesquisa
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Agendamento online
                        <p class="f-12">Seus clientes podem remarcar as aulas por conta própria</p>
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Controle avaliações físicas de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Controle fichas de treinamento de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Controle dietas de clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Gerencie seus clientes
                    </li>
                    <li class="list-group-item">
                        <i class="ion-ios-checkmark-empty m-r-5 c-green plan-check-close-icon"></i>
                        Adicionar profissionais
                        <p class="f-12">incluso 1 usuário profissional (R$19,90 / profissional extra)</p>
                    </li>
                </ul>

                @if(!\Auth::guard('professional_web')->guest())
                    <a href="{{route('landing.signup.company', ['plan' => 'plus'])}}" class="btn btn-xs btn-block btn-success p-10 f-16 m-t-10" title="Quero este!">Cadastrar empresa</a>
                @else
                    <p class="text-center m-t-20 m-b-10">Faça login para cadastrar sua empresa</p>
                    <a class="btn btn-xs btn-block btn-primary p-10 f-16" href="{{route('landing.professionals.login', ['redirect' =>  Request::path()])}}">Fazer login</a>
                @endif

            </div>
        </div>
    </div>
    <!--PREMIUM -->