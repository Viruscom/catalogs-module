<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.catalogs.main.index') }}" class="text-black">@lang('catalogs::admin.catalogs_main.index')</a>
        </li>
        @if(url()->current() === route('admin.catalogs.main.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.catalogs.main.create') }}" class="text-purple">@lang('admin.pages.create')</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.catalogs.main.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.catalogs.main.edit', ['id' => Request::segment(4)]) }}" class="text-purple">@lang('admin.pages.edit')</a>
            </li>
        @endif
    </ul>
</div>

