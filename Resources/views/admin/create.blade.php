@extends('layouts.admin.app')

@section('content')
    @include('catalogs::admin.main_catalogs.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.catalogs.manage.store', ['path' => Request::segment(5), 'mainPosition' => Request::segment(6)]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
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
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_create.form_fields.input_text', ['fieldName' => 'short_description_' . $language->code, 'label' => trans('admin.description'), 'required' => false])
                        </div>
                    @endforeach
                </div>
                <div class="form form-horizontal">
                    <div class="form-body">
                       <div class="row">
                           <div class="col-md-12 col-xs-12">
                               @include('admin.partials.on_create.form_fields.select', ['fieldName' => 'main_catalog_id',  'label' => trans('catalogs::admin.catalogs.select_main_catalog'), 'models' => $mainCatalogs, 'required' => true])
                           </div>
                           <div class="col-md-12 col-xs-12">
                               @include('admin.partials.on_create.active_checkbox')
                           </div>
                       </div>

                    </div>
                    @include('admin.partials.on_create.form_actions_bottom')
                </div>
            </div>
        </div>
    </form>
@endsection
