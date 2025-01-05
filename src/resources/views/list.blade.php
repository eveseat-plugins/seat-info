@extends('web::layouts.grids.12')

@section('title', trans('info::info.module_title'))
@section('page_header', trans('info::info.module_title'))

@push('head')
    <style>
        .p-0-75{
            padding: 0.75rem;
        }
    </style>
@endpush

@section('full')
    @include("treelib::giveaway")

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                <div class="card">
                    <div class="card-header">
                        Articles
                        <div class="btn-group float-right" role="group">
                            @can("info.create_article")
                                <a href="{{ route("info.create") }}" class="float-right btn btn-secondary">{{ trans("info::info.list_article_new") }}</a>
                                <a href="{{ route("info.manage") }}" class="float-right btn btn-secondary">{{ trans("info::info.list_article_manage") }}</a>
                            @endcan
                            <a class="btn btn-primary" href="{{ url()->previous() }}">{{ trans("info::info.view_back_button") }}</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="list-group">
                            @foreach ($articles as $article)
                                <div class="list-group-item list-group-item-action d-flex flex-row align-items-baseline p-0-75" data-is-pinned="{{$article->pinned}}" data-article-id="{{ $article->id }}">
                                    <a href="{{ route("info.view", $article->id) }}" class="mr-auto">{{ $article->name }}</a>

                                    <div class="ml-3">
                                        @if($article->pinned)
                                            <span class="badge badge-primary">
                                                <i class="fas fa-map-pin"></i>
                                                {{ trans('info::info.list_pinned_article') }}
                                            </span>
                                        @endif

                                        @if(!$article->public)
                                            <span class="badge badge-secondary">{{ trans('info::info.list_private_article') }}</span>
                                        @endif

                                        @can("info.change_order")
                                            <button class="btn btn-article-up">
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                            <button class="btn btn-article-down">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@can("info.change_order")
    @push("javascript")
        <script>
            // primitive attempt at keeping swap in order
            let lastRequestPromise = null
            async function swapOrderServerSide(id1, id2){
                if(lastRequestPromise) {
                    try {
                        await lastRequestPromise
                    } catch (e) {

                    }
                }

                lastRequestPromise = fetch("{{ route("info.swapOrder") }}",{
                    method:"POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{@csrf_token()}}'
                    },
                    body: JSON.stringify({
                        "article_1": id1,
                        "article_2": id2
                    })
                })

                try {
                    const response = await lastRequestPromise
                    if(!response.ok) throw new Error("failed to update article order")
                } catch (e) {
                    alert("Failed to change order, please reload!")
                }
            }

            function swap(node1, node2) {
                const afterNode2 = node2.nextElementSibling;
                const parent = node2.parentNode;
                if (node1 === afterNode2) {
                    parent.insertBefore(node1, node2);
                } else {
                    node1.replaceWith(node2);
                    parent.insertBefore(node1, afterNode2);
                }
            }

            function installArticleOrderButtons(buttons, siblingSelector){
                for (const button of buttons) {
                    button.addEventListener("click",function (){
                        const articleListEntry = button.closest("[data-article-id]")

                        const otherListEntry = siblingSelector(articleListEntry)

                        if(otherListEntry) {
                            // pinned articles stay on top
                            if(articleListEntry.dataset.isPinned !== otherListEntry.dataset.isPinned) return

                            swap(articleListEntry, otherListEntry)

                            swapOrderServerSide(articleListEntry.dataset.articleId, otherListEntry.dataset.articleId)
                        }
                    })
                }
            }

            const upButtons = document.querySelectorAll(".btn-article-up")
            installArticleOrderButtons(upButtons, (e)=>e.previousElementSibling)

            const downButtons = document.querySelectorAll(".btn-article-down")
            installArticleOrderButtons(downButtons, (e)=>e.nextElementSibling)
        </script>
    @endpush
@endcan