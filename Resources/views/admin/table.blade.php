<div class="table-responsive">
    <table class="table {{ $tableClass }}">
        <thead>
        <th class="width-2-percent"></th>
        <th class="width-2-percent">{{ __('admin.number') }}</th>
        <th style="width: 55px">{{ __('admin.gallery.image') }}</th>
        <th>{{ __('admin.title') }}</th>
        <th class="width-220 text-right">{{ __('admin.actions') }}</th>
        </thead>
        <tbody>
        @php
            $i = 1;
        @endphp
        @forelse($catalogs as $catalog)
            @php
            $mainCatalog = $catalog->parent->translate(config('default.app.language.code'));
                $fileUrl = (!is_null($mainCatalog->thumbnail) && $mainCatalog->existsFile($mainCatalog->thumbnail)) ? \Storage::disk('public')->url($mainCatalog->getFilepath($mainCatalog->thumbnail)): url($catalog->getSystemImage());
            @endphp
            <tr class="t-row" data-toggle="popover" data-content=''>
                <td class="width-2-percent">
                    <div class="pretty p-default p-square">
                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
                        <div class="state p-primary">
                            <label></label>
                        </div>
                    </div>
                </td>
                <td class="width-2-percent">{{$i}}</td>
                <td><img class="img-responsive" width="50" src="{{ $fileUrl }}" alt="{{ $catalog->short_description }}"/></td>
                <td>{{ $catalog->short_description }}</td>
                <td class="text-right">
                    <a href="{{ route('admin.catalogs.manage.edit', ['id' => $catalog->id]) }}" class="btn green tooltips edit-image-btn" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.edit') }}"><i class="fas fa-pencil-alt"></i></a>
                    @if(!$catalog->active)
                        <a href="{{ route('admin.catalogs.manage.changeStatus', ['id'=> $catalog->id, 'active'=>1]) }}" role="button" class="btn light-grey-eye visibility-activate tooltips active-image-btn" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.show') }}"><i class="far fa-eye-slash"></i></a>
                    @else
                        <a href="{{ route('admin.catalogs.manage.changeStatus', ['id'=> $catalog->id, 'active'=>0]) }}" role="button" class="btn grey-eye visibility-unactive tooltips not-active-image-btn" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.hide') }}"><i class="far fa-eye"></i></a>
                    @endif
                    @if($i !== 1)
                        <a href="{{ route('admin.catalogs.manage.position-up', ['id' => $catalog->id]) }}" role="button" class="move-up btn yellow tooltips position-up-image-btn" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.move_up') }}"><i class="fas fa-angle-up"></i></a>
                    @endif
                    @if($i !== count($catalogs))
                        <a href="{{ route('admin.catalogs.manage.position-down', ['id' => $catalog->id]) }}" role="button" class="move-down btn yellow tooltips position-down-image-btn" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.move_down') }}"><i class="fas fa-angle-down"></i></a>
                    @endif
                    <a href="{{ route('admin.catalogs.manage.delete', ['id' => $catalog->id]) }}" class="btn red btn-delete-confirm tooltips delete-image-btn" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.delete') }}"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            @php
                $i++;
            @endphp
        @empty
            <tr>
                <td colspan="5" class="no-table-rows">{{ trans('admin.no-records') }}</td>
            </tr>
        @endforelse
        <tr style="display: none;">
            <td colspan="5" class="no-table-rows">{{ trans('admin.no-records') }}</td>
        </tr>

        </tbody>
    </table>
</div>
