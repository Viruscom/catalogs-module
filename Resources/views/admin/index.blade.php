@extends('layouts.app')
@section('styles')
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet"/>
@endsection
@section('scripts')
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});
    </script>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="alert alert-warning col-md-12"><strong>Внимание!</strong> Преди да асоциирате или управлявате каталог е необходимо да добавите каталозите, с които ще работите.</div>
    </div>
    <div class="col-md-12">
        <div class="form form-horizontal form-bordered ">
            <div class="form-group">
                <label class="control-label col-md-3">Страница:</label>
                <div class="col-md-4">
                    <select class="form-control select2 catalog-select" name="navigation">
                        <option value="">--- Моля, изберете ---</option>
                        @foreach($navigations as $nav)
                            <optgroup label="{{$nav->translations->where('language_id', 1)->first()->title}}">
                                @php
                                    $contentPages = $nav->content_pages()->orderBy('position', 'asc')->get();
                                @endphp
                                @foreach($contentPages as $contPage)
                                    <option data-parentTypeId="{{$parentTypeContent}}" value="{{$contPage->id}}"> - - {{
							$contPage->translations->where('language_id', 1)->first()->title}}</option>
                                @endforeach
                            </optgroup>
                            @php
                                $productCategories = $nav->product_categories()->orderBy('position', 'asc')->get();
                            @endphp
                            @foreach($productCategories as $prodCateg)
                                <optgroup label=" - - {{$prodCateg->translations->where('language_id', 1)->first()->title}}">
                                    @php
                                        $products = $prodCateg->products()->orderBy('position', 'asc')->get();
                                    @endphp
                                    @foreach($products as $prod)
                                        <option data-parentTypeId="{{$parentTypeProduct}}" value="{{$prod->id}}"> - - - -{{
								$prod->translations->where('language_id', 1)->first()->title}}</option>
                                    @endforeach
                                </optgroup>

                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection
