@extends('layouts.app')
@section('styles')
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    $(".select2").select2({language: "bg"});
@endsection

@section('content')
    {{-- <div class="col-md-12">
        <div class="form form-horizontal form-bordered ">
            <div class="form-group">
                <label class="control-label col-md-3">Страница:</label>
                <div class="col-md-4">
                    <select class="form-control select2 catalog-inner-page-select">
                        @foreach($navigations as $nav)
                        <optgroup label="{{ $nav->translations->where('language_id', 1)->first()->title}}">
                            @foreach($nav->content_pages as $contPage)
                            <option data-parentTypeId="{{$parentTypeContent}}" value="{{$contPage->id}}" {{ ($parentTypeContent==Request::segment(3) && $contPage->id==Request::segment(4)) ? 'selected':''}}> - - {{
                               $contPage->translations->where('language_id', 1)->first()->title}}</option>
                               @endforeach
                           </optgroup>
                           @foreach($nav->product_categories as $prodCateg)
                           <optgroup label=" - - {{$prodCateg->translations->where('language_id', 1)->first()->title}}">
                              @foreach($prodCateg->products as $prod)
                              <option data-parentTypeId="{{$parentTypeProduct}}" value="{{$prod->id}}" {{ ($parentTypeProduct==Request::segment(3) && $prod->id==Request::segment(4)) ? 'selected':''}}> - - - -{{
                               $prod->translations->where('language_id', 1)->first()->title}}</option>
                               @endforeach
                           </optgroup>

                           @endforeach
                           @endforeach
                       </select>
                   </div>
               </div>
           </div>
       </div> --}}
    <div class="col-xs-12 p-0">
        <form class="my-form" action="{{ url('/admin/catalogs/'.$catalog->id.'/update') }}" method="POST" data-form-type="update" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{old('position')}}">
            <input type="hidden" name="parent_type_id" value="{{(old('parent_type_id')) ? old('parent_type_id'): $catalog->parent_type_id }}">
            <input type="hidden" name="parent_id" value="{{(old('parent_id')) ? old('parent_id'): $catalog->parent_id }}">
            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submitaddnew" value="submitaddnew" class="btn btn-lg green saveplusicon margin-bottom-10"></button>
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                @foreach($languages as $language)
                    <li @if($language->code == env('DEF_LANG_CODE')) class="active" @endif}}><a data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span></a></li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($languages as $language)
                    <?php $langShortDescr = 'short_description_' . $language->code;
                    $catalogTranslation = (is_null($catalog->translations->where('language_id', $language->id)->first())) ? null : $catalog->translations->where('language_id', $language->id)->first();
                    ?>
                    <div id="{{$language->code}}" class="tab-pane fade in @if($language->code == env('DEF_LANG_CODE')) active @endif}}">
                        <div class="form-group @if($errors->has($langShortDescr)) has-error @endif">
                            <label class="control-label p-b-10">Описание (<span class="text-uppercase">{{$language->code}}</span>):</label>
                            <input class="form-control" type="text" name="{{$langShortDescr}}" value="{{ (old($langShortDescr)) ? old($langShortDescr) : (!is_null($catalogTranslation) ? $catalogTranslation->short_description : '') }}">
                            @if($errors->has($langShortDescr))
                                <span class="help-block">{{ trans($errors->first($langShortDescr)) }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form form-horizontal">
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-3">Каталог:</label>
                        <div class="col-md-4">
                            <select class="form-control select2" name="main_catalog_id">
                                @foreach($mainCatalogs as $mainCatalog)
                                    @php
                                        $mainCatalogTranslation = $mainCatalog->translations()->where('language_id', 1)->first();
                                    @endphp
                                    <option value="{{$mainCatalog->id}}" {{ ($mainCatalog->id == $catalog->main_catalog_id) ? 'selected': '' }}>{{ $mainCatalogTranslation->short_description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <div class="form-group">
                        <label class="control-label col-md-3">Основна позиция на галерията:</label>
                        <div class="col-md-4">
                            <select class="form-control select2" name="main_position">
                                <option value="0" {{ ($catalog->main_position == 0) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_0') }}</option>
                                <option value="1" {{ ($catalog->main_position == 1) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_1') }}</option>
                                <option value="2" {{ ($catalog->main_position == 2) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_2') }}</option>
                                <option value="3" {{ ($catalog->main_position == 3) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_3') }}</option>
                                <option value="4" {{ ($catalog->main_position == 4) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_4') }}</option>
                                <option value="5" {{ ($catalog->main_position == 5) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_5') }}</option>
                                <option value="6" {{ ($catalog->main_position == 6) ? 'selected': '' }}>{{ trans('administration_messages.additional_gallery_main_position_6') }}</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="control-label col-md-3">Активен (видим) в сайта:</label>
                        <div class="col-md-6">
                            <label class="switch pull-left">
                                <input type="checkbox" name="active" class="success" data-size="small" checked {{(old('active') ? 'checked' : (($catalog->active) ? 'checked': ''))}}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="control-label col-md-3">Позиция в сайта:</label>
                        <div class="col-md-6">
                            <p class="position-label">№ {{ $catalog->position }}</p>
                            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal">Моля, изберете позиция</a>
                            <p class="help-block">(ако не изберете позиция, записът се добавя като последен)</p>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" name="submitaddnew" value="submitaddnew" class="btn green saveplusbtn margin-bottom-10"> запиши и добави нов</button>
                            <button type="submit" name="submit" value="submit" class="btn save-btn margin-bottom-10"><i class="fas fa-save"></i> запиши</button>
                            <a href="{{ url()->previous() }}" role="button" class="btn back-btn margin-bottom-10"><i class="fa fa-reply"></i> назад</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-purple" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Изберете позиция</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-hover table-positions">
                            <tbody>
                            @if(count($catalogs))
                                @foreach($catalogs as $catalog)
                                    <tr class="pickPositionTr" data-position="{{$catalog->position}}">
                                        <td>{{$catalog->position}}</td>
                                        <td>{{$catalog->translations->firstWhere('language_id',$defaultLanguage->id)->short_description}}</td>
                                    </tr>
                                @endforeach
                                <tr class="pickPositionTr" data-position="{{$catalogs->last()->position+1}}">
                                    <td>{{$catalogs->last()->position+1}}</td>
                                    <td>--{{trans('administration_messages.last_position')}}--</td>
                                </tr>
                            @else
                                <tr class="pickPositionTr" data-position="1">
                                    <td>1</td>
                                    <td>--{{trans('administration_messages.last_position')}}--</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="#" class="btn save-btn margin-bottom-10 accept-position-change" data-dismiss="modal"><i class="fas fa-save"></i> потвърди</a>
                                    <a role="button" class="btn back-btn margin-bottom-10 cancel-position-change" current-position="{{ $catalog->position }}" data-dismiss="modal"><i class="fa fa-reply"></i> назад</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
