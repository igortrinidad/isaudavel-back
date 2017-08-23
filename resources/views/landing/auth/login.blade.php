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
                            <h2>Login profissional</h2>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="block">

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary btn-block">Login</button>
                        </div>
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