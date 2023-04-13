@extends('layouts.admin.app')

@section('content')
    @include('catalogs::admin.breadcrumbs')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">{!! __('catalogs::admin.catalogs.warning_no_catalogs_methods_in_eloquent_model') !!}</div>
        </div>
    </div>
@endsection
