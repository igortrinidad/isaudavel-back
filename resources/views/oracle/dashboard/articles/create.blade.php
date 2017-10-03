@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container m-b-30" id="article-list">

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Criar artigo</strong></h3>

                <form method="POST" action="{{route('oracle.dashboard.articles.store')}}" enctype='multipart/form-data'>
                    {{csrf_field()}}

                    <div class="form-group" >
                        <label>Título</label>
                        <input class="form-control" name="title">
                    </div>

                    <div class="form-group">
                        <label>Conteúdo</label>
                        <textarea class="form-control" name="content" id="editor"></textarea>
                    </div>

                    <div class="form-group">
                        <label>URL</label>
                        <input class="form-control" name="slug">
                    </div>

                    <div class="form-group">
                        <label>Foto de capa</label>
                        <input type="file" class="form-control" name="file">
                    </div>

                    <div class="form-group">
                        <label>Publicado</label><br>
                        <label class="switch">
                            <input type="checkbox" name="is_published" value="0">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Salvar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    @parent
        <script>

            $('#editor').summernote({
                placeholder: 'Digite algo',
                height: 150,
                toolbar: [
                    ['color', ['color']],
                    ['fontsize', ['fontsize']],
                    ['style', ['bold', 'italic', 'underline', 'clear', 'paragraph']],
                    ['undo', ['undo']],
                    ['redo', ['redo']],
                    ['hr', ['hr']],
                    ['link', ['link']],
                    ['picture', ['picture']],
                    ['codeview', ['codeview']]
                ]
            })
        </script>
    @stop

@stop