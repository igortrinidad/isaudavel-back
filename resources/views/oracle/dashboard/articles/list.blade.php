@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container m-b-30" id="article-list">

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Artigos</strong></h3>

                <div class="m-t-20">
                    <a href="{{route('oracle.dashboard.articles.create')}}" class="btn btn-primary">Adicionar novo artigo</a>
                </div>

                <table class="table table-bordered table-hover table-striped m-t-20">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Título</th>
                            <th>Url</th>
                            <th>Visualizações</th>
                            <th>Compartilhamentos</th>
                            <th>Status</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <tr>
                            <td>{{$article->created_at}}</td>
                            <td>{{$article->title}}</td>
                            <td>{{$article->slug}}</td>
                            <td>{{$article->views}}</td>
                            <td>{{$article->shares}}</td>
                            <td>
                                @if($article->is_published)
                                    <span class="label label-success">Publicado</span>
                                @else
                                    <span class="label label-default">Aguardando</span>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('oracle.dashboard.articles.edit', $article->id)}}">Editar</a>
                            </td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="{{route('oracle.dashboard.articles.destroy', $article->id)}}">Excluir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop