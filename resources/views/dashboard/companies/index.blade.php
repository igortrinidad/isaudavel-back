@extends('dashboard.layout.index')

@section('content')
    <div class="container">
        <div class="row m-t-20">
            <h3>Minhas empresas</h3>

            <div class="m-t-20">
                @include('flash::message')
            </div>

            <div class="row m-t-20">
                @foreach($companies as $company)
                    <div class="col-sm-4 col-xs-12">
                        <div class="card">

                            <div class="card-header ch-alt text-center">
                                <div class="picture-circle" :style="`background-image:url('${ company.avatar }')`"></div>
                                <h3 class="f-300 m-b-0">{{$company->name}}</h3>
                            </div>

                            <div class="card-body card-padding text-center">
                                <div>
                                    @foreach($company->categories as $category)
                                        <span class="label label-success f-300 f-14 m-t-5 m-r-5">{{ $category->name }}</span>
                                    @endforeach

                                    @if($company->current_rating > 0)
                                        <span class="d-block f-300 f-14 m-t-10" v-if="">Avaliação:</span>
                                            <div class="wp-rating-div">
                                                @php
                                                    $rating_to_loop = $company->current_rating
                                                @endphp
                                                @include('components.rating', ['size' => '22'])
                                            </div>
                                    @endif

                                    @if($company->current_rating <= 0)
                                        <span class="d-block f-300 f-14 m-t-10">Ainda não possui avaliações</span>
                                    @endif
                                    <a class="btn btn-xs btn-primary m-t-10 btn-block" href="{{route('professional.dashboard.company.edit', ['id'=> $company->id])}}">
                                        Editar
                                    </a>

                                    <a class="btn btn-xs btn-success m-t-10 btn-block" href="{{route('professional.dashboard.company.show', ['id'=> $company->id])}}">
                                        Gerenciar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
