@php
    use Modules\Catalogs\Models\Catalog;
@endphp@extends('layouts.admin.app')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.myadmin-alert .closed').click(function (e) {
                e.preventDefault();
                $(this).parent().addClass('hidden');
            });

            $('[data-toggle="popover"]').popover({
                placement: 'auto',
                trigger: 'hover',
                html: true
            });
        });
    </script>
@endsection
@section('content')
    @include('catalogs::admin.breadcrumbs')
    @include('admin.notify')

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_main_description') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'headerForm', 'mainPosition' => Catalog::CATALOGS_AFTER_DESCRIPTION])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_DESCRIPTION], 'tableClass' => 'table-headerForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_1') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextOneForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_1])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_1], 'tableClass' => 'table-additionalTextOneForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_2') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextTwoForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_2])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_2], 'tableClass' => 'table-additionalTextTwoForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_3') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextThreeForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_3])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_3], 'tableClass' => 'table-additionalTextThreeForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_4') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextFourForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_4])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_4], 'tableClass' => 'table-additionalTextFourForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_5') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextFiveForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_5])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_5], 'tableClass' => 'table-additionalTextFiveForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('catalogs::admin.catalogs.after_additional_description_6') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
            @include('catalogs::admin.top_buttons', ['formId' => 'additionalTextSixForm', 'mainPosition' => Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_6])
            @include('catalogs::admin.table', ['catalogs' => $model['Catalogs'][Catalog::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_6], 'tableClass' => 'table-additionalTextSixForm'])
        </div>
    </div>

    @include('admin.partials.modals.delete_confirm')
@endsection
