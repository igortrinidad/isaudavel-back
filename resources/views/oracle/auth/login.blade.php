@extends('landing.companies.index', ['header_with_search' => false])

@section('landing-content')

    <style>

    </style>

    <section>

        <div class="container">
            <div class="row m-t-30">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <div class="block">
                        <div class="">
                            <h2>Oracle</h2>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="block">
                        <div  class="m-t-20">
                            {{--Alert display--}}
                            @include('flash::message')
                        </div>

                        <form class="contact-form" method="POST" action="{{route('oracle.post.login')}}">
                            {!! csrf_field() !!}

                            <div class="entry-field">
                                <label>E-mail</label>
                                <input class="form-control" name="email" placeholder="Seu e-mail" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" class="form-control" name="password" placeholder="Sua senha" required>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>


    </section>

@section('scripts')
    @parent

    <script>


    </script>


@stop
@stop