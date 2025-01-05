@extends('web::layouts.grids.12')

@section('title', trans('info::info.module_title'))
@section('page_header', trans('info::info.module_title'))

@section('full')

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                <div class="card">
                    <div class="card-header">
                        {{ $resource->name }}
                    </div>
                    <div class="card-body">

                        <form action="{{route("info.configure_resource_save", $resource->id)}}" method="POST" enctype="multipart/form-data" class="acl-editor-submit-form">
                            @csrf

                            <div class="alert alert-warning my-2">
                                If your resource files aren't showing up correctly after upgrading to seat 5, ask an administrator to follow the <a href="https://github.com/recursivetree/seat-info/tree/5.0.x#upgrading">seat-info upgrade guide</a>.
                            </div>

                            <div class="form-group">
                                <label for="resource-name-input">{{ trans("info::info.configure_resource_resource_name") }}</label>
                                <input class="form-control" id="resource-name-input" type="text" name="name" value="{{ $resource->name }}" placeholder="{{ trans("info::info.configure_resource_resource_name_placeholder") }}">
                            </div>

                            @include("info::components.acl-editor",["roles"=>$roles,"acl_roles"=>$acl_roles])

                            <div class="form-group">
                                <label for="resourceFileUpload"> {{ trans("info::info.configure_resources_reupload_label",['max'=>ini_get("upload_max_filesize")]) }}</label>
                                <div class="custom-file mb-2">
                                    <input type="file" name="file" class="custom-file-input"
                                           id="resourceFileUpload">
                                    <label class="custom-file-label"
                                           for="resourceFileUpload">{{ trans("info::info.configure_resources_upload_choose") }}</label>
                                </div>

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="mime-src"
                                           name="mime_src_client">
                                    <label class="form-check-label"
                                           for="mime-src">{{ trans("info::info.manage_resources_mime_client_label") }}</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ trans('info::info.configure_resource_resource_save') }}</button>

                            <a href="{{ route("info.manage") }}" class="btn btn-secondary">{{ trans('info::info.configure_resource_personal_article_link') }}</a>

                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>

@stop

@push('javascript')
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function () {
            let fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush