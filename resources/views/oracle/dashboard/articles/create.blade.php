@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container m-b-30" id="article-list">

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Criar artigo</strong></h3>

                <form method="POST">

                    <div class="form-group">
                        <label>Título</label>
                        <input class="form-control" name="title">
                    </div>

                    <div class="form-group">
                        <label>Publicado</label><br>
                        <p class="text">
                        <label class="switch">
                            <input type="checkbox" name="is_published">
                            <div class="slider round"></div>
                        </label>
                    </div>

                </form>
            </div>
        </div>
    </div>

@stop