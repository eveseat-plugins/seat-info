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

                @isset($article)
                    <div class="card">
                        <div class="card-header">
                            <b>{{$article->name}}</b>
                            <div class="btn-group float-right" role="group">
                                @if($can_edit)
                                    <a href="{{ route("info.edit_article", $article->id) }}" class="float-right btn btn-secondary">{{ trans("info::info.view_article_edit") }}</a>
                                    <a href="{{ route("info.manage") }}" class="float-right btn btn-secondary">{{ trans("info::info.view_article_manage") }}</a>
                                @endif
                                <a class="btn btn-primary" href="{{ url()->previous() }}">{{ trans("info::info.view_back_button") }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger" style="display: none;" id="rendering-error">
                                <span>{{ trans("info::info.view_render_errors_message") }}</span>
                            </div>
                            <p class="card-text" id="info-content-target">Loading...</p>
                        </div>
                    </div>
                @endisset

            </div>
        </div>
    </div>

@stop

@isset($article)
    @push('javascript')

        <script src="@infoVersionedAsset('info/js/parser.js')"></script>
        <script src="@infoVersionedAsset('info/js/render_article.js')"></script>
        <script src="@infoVersionedAsset('info/js/markup_tags.js')"></script>

        <script>
            window.addEventListener('load', (event) => {
                const target = document.getElementById("info-content-target")
                target.textContent = null
                render_article({!! json_encode( $article->text) !!}, target, function (e) {
                    if (e.error || e.warnings.length > 0) {
                        console.log(e)
                        document.getElementById("rendering-error").style.display = "block"
                    }
                });
            });
        </script>
    @endpush
@endisset