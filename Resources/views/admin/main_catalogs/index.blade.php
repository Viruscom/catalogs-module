@extends('layouts.admin.app')

@section('content')
    @include('catalogs::admin.main_catalogs.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => 'catalogs.main'])

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <th class="width-2-percent"></th>
                    <th class="width-2-percent">{{ __('admin.number') }}</th>
                    <th>{{ __('catalogs::admin.catalog_image') }}</th>
                    <th>{{ __('admin.title') }}</th>
                    <th class="width-220">{{ __('admin.actions') }}</th>
                    </thead>
                    <tbody>
                    @if(count($mainCatalogs))
                        <?php $i = 1;?>
                        @foreach($mainCatalogs as $mainCatalog)
                            <tr class="t-row row-{{$mainCatalog->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$mainCatalog->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td></td>
                                <td>{{ $mainCatalog->title}}</td>
                                <td class="pull-right">
                                    @include('admin.partials.index.action_buttons', ['mainRoute' => 'catalogs.main', 'models' => $mainCatalogs, 'model' => $mainCatalog, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$mainCatalog->id}}-details hidden">
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <table class="table-details">
                                        <tbody>
                                        <tr>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="width-220">
                                    <img class="thumbnail img-responsive" src="{{ $mainCatalog->translate(config('default.app.language.code'))->fullImageFilePathUrl() }}"/>
                                </td>
                            </tr>
                            <?php $i++;?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="5" class="no-table-rows">{{ trans('admin.no-records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="no-table-rows">{{ trans('admin.no-records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
