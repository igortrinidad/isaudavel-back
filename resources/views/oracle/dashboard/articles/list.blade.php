@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container m-b-30" id="article-list">

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Artigos</strong></h3>

                <table class="table table-bordered table-hover table-striped m-t-20">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Título</th>
                            <th>Visualizações</th>
                            <th>Compartilhamentos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <tr>
                            <td>{{$article->created_at}}</td>
                            <td>{{$article->title}}</td>
                            <td>{{$article->views}}</td>
                            <td>{{$article->shares}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop