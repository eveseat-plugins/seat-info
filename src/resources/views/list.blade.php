@extends('web::layouts.grids.12')

@section('title', trans('info::info.module_title'))
@section('page_header', trans('info::info.module_title'))

@section('full')

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                @if (session()->has('message'))
                    @if(session()->get('message')["type"]=="success")
                        <div class="alert alert-success">
                            <p class="card-text">{{ session()->get('message')['message'] }}</p>
                        </div>
                    @elseif(session()->get('message')["type"]=="warning")
                        <div class="alert alert-warning">
                            <p class="card-text">{{ session()->get('message')['message'] }}</p>
                        </div>
                    @elseif(session()->get('message')["type"]=="error")
                        <div class="alert alert-danger">
                            <p class="card-text">{{ session()->get('message')['message'] }}</p>
                        </div>
                    @endif
                @endif

                <div class="card">
                    <div class="card-header">
                        Articles
                        <div class="btn-group float-right" role="group">
                            @can("info.create_article")
                                <a href="{{ route("info.create") }}" class="float-right btn btn-secondary">{{ trans("info::info.list_article_new") }}</a>
                            @endcan
                            @can("info.manage_article")
                                <a href="{{ route("info.manage") }}" class="float-right btn btn-secondary">{{ trans("info::info.list_article_manage") }}</a>
                            @endcan
                            <a class="btn btn-primary" href="{{ url()->previous() }}">{{ trans("info::info.view_back_button") }}</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="list-group">
                            @foreach ($articles as $article)
                                @canany(["info.article.view","info.article.edit"],$article->id)
                                    @if($article->public || auth()->user()->can("info.article.edit", $article->id))
                                        <div class="list-group-item list-group-item-action d-flex flex-row align-items-baseline">
                                            <a href="{{ route("info.view", $article->id) }}" class="mr-auto">{{ $article->name }}</a>

                                            <div class="mx-3">
                                                @if($article->pinned)
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-map-pin"></i>
                                                        {{ trans('info::info.list_pinned_article') }}
                                                    </span>
                                                @endif

                                                @if(!$article->public)
                                                    <span class="badge badge-secondary">{{ trans('info::info.list_private_article') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endcan
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop