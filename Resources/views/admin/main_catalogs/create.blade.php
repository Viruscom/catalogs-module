@extends('layouts.admin.app')

@section('content')
    @include('catalogs::admin.main_catalogs.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.catalogs.main.store') }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            @include('admin.partials.on_create.form_actions_top')
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <ul class="nav nav-tabs">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif>
                            <a data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span></a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($languages as $language)
                            <?php
                            $langCatalog = 'filename_' . $language->code;
                            $langThumbnail = 'thumbnail_' . $language->code;
                            ?>
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_create.form_fields.input_text', ['fieldName' => 'title_' . $language->code, 'label' => trans('admin.title'), 'required' => true])
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group @if($errors->has($langCatalog)) has-error @endif">
                                        <label class="control-label p-b-10"><span class="text-purple">* </span>{{ __('catalogs::admin.catalogs_main.catalog_file') }}(<span class="text-uppercase">{{$language->code}}</span>):</label>
                                        <input type="file" name="{{$langCatalog}}" class="filestyle form-control" data-buttonText="{{trans('admin.browse_file')}}" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                                        <span class="help-block">{!! __('catalogs::admin.catalogs_main.catalog_file_info') !!}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group @if($errors->has($langThumbnail)) has-error @endif">
                                        <label class="control-label p-b-10"><span class="text-purple">* </span>{{ __('catalogs::admin.catalogs_main.catalog_thumbnail_file') }}(<span class="text-uppercase">{{$language->code}}</span>):</label>
                                        <input type="file" name="{{$langThumbnail}}" class="filestyle form-control" data-buttonText="{{trans('admin.browse_file')}}" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                                        <p class="help-block">{!! __('catalogs::admin.catalogs_main.catalog_thumbnail_file_info') !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form form-horizontal">
                    <div class="form-body">
                        @include('admin.partials.on_create.active_checkbox')
                    </div>
                    @include('admin.partials.on_create.form_actions_bottom')
                </div>
            </div>
        </div>
    </form>
@endsection
