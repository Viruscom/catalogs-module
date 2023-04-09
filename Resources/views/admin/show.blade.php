@php use App\Models\Gallery\Gallery; @endphp@extends('layouts.admin.app')

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
    @include('admin.gallery.breadcrumbs')
    @include('admin.notify')

    @if(!$model->headerGallery && !$model->mainGallery && !$model->additionalGalleryOne && !$model->additionalGalleryTwo && !$model->additionalGalleryThree && !$model->additionalGalleryFour && !$model->additionalGalleryFive && !$model->additionalGallerySix)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">{!! __('admin.gallery.warning_no_gallery_methods_in_eloquent_model') !!}</div>
            </div>
        </div>
    @else
        @if ($model->headerGallery)
            <div class="row">
                <div class="col-xs-12">
                    <h3>{{ __('admin.gallery.header_gallery') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'headerAddModalId', 'formId' => 'headerForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'headerAddModalId', 'formId' => 'headerForm', 'galleryType' => Gallery::HEADER_GALLERY])
                    <div class="table-responsive">
                        <table class="table table-headerForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->headerGallery])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->mainGallery)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.main_gallery') }} {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'mainAddModalId', 'formId' => 'mainForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'mainAddModalId', 'formId' => 'mainForm', 'galleryType' => Gallery::MAIN_GALLERY])
                    <div class="table-responsive">
                        <table class="table table-mainForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->mainGallery])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGalleryOne)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 1 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalOneAddModalId', 'formId' => 'additionalOneForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalOneAddModalId', 'formId' => 'additionalOneForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_ONE])
                    <div class="table-responsive">
                        <table class="table table-additionalOneForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGalleryOne])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGalleryTwo)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 2 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalTwoAddModalId', 'formId' => 'additionalTwoForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalTwoAddModalId', 'formId' => 'additionalTwoForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_TWO])
                    <div class="table-responsive">
                        <table class="table table-additionalTwoForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGalleryTwo])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGalleryThree)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 3 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalThreeAddModalId', 'formId' => 'additionalThreeForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalThreeAddModalId', 'formId' => 'additionalThreeForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_THREE])
                    <div class="table-responsive">
                        <table class="table table-additionalThreeForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGalleryThree])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGalleryFour)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 4 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalFourAddModalId', 'formId' => 'additionalFourForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalFourAddModalId', 'formId' => 'additionalFourForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_FOUR])
                    <div class="table-responsive">
                        <table class="table table-additionalFourForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGalleryFour])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGalleryFive)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 5 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalFiveAddModalId', 'formId' => 'additionalFiveForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalFiveAddModalId', 'formId' => 'additionalFiveForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_FIVE])
                    <div class="table-responsive">
                        <table class="table table-additionalFiveForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGalleryFive])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($model->additionalGallerySix)
            <div class="row">
                <div class="col-xs-12">
                    <hr style="border: 2px solid #c82864;margin-top: 60px;">
                    <h3>{{ __('admin.gallery.additional_gallery') }} 6 {{ __('admin.gallery.for') }} {{ $model->title }}</h3>
                    @include('admin.gallery.top_buttons', ['addModalId' => 'additionalSixAddModalId', 'formId' => 'additionalSixForm'])
                    @include('admin.gallery.submit_modal', ['addModalId' => 'additionalSixAddModalId', 'formId' => 'additionalSixForm', 'galleryType' => Gallery::ADDITIONAL_GALLERY_SIX])
                    <div class="table-responsive">
                        <table class="table table-additionalSixForm">
                            <thead>
                            <th class="width-2-percent"></th>
                            <th class="width-2-percent">{{ __('admin.number') }}</th>
                            <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
                            <th>{{ __('admin.title') }}</th>
                            <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                            </thead>
                            <tbody>
                            @include('admin.gallery.return_view', ['images' => $model->additionalGallerySix])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
    @include('admin.partials.modals.delete_confirm')
@endsection
