@extends('layouts.app')
@section('styles')
	<link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('scripts')
	<script src="{{ asset('admin/js/select2.min.js') }}"></script>
	<script src="{{ asset('admin/js/bootstrap-confirmation.js') }}"></script>
	<script>
		$('[data-toggle=confirmation]').confirmation({
			rootSelector: '[data-toggle=confirmation]',
			container: 'body',
		});
		$(".select2").select2({language: "bg"});

		$(document).ready(function(){
			$('[data-toggle="popover"]').popover({
				placement : 'auto',
				trigger : 'hover',
				html : true
			});
		});
	</script>
@endsection
@section('content')
	<div class="col-md-12">
		<div class="form form-horizontal form-bordered ">
			<div class="form-group">
				<label class="control-label col-md-3">Страница:</label>
				<div class="col-md-4">
					<select class="form-control select2 catalog-select" name="navigation">
						@foreach($navigations as $nav)
							<optgroup label="{{$nav->translations->where('language_id', 1)->first()->title}}">
								@php
									$contentPages = $nav->content_pages()->orderBy('position', 'asc')->get();
								@endphp
								@foreach($contentPages as $contPage)
									<option data-parentTypeId="{{$parentTypeContent}}" value="{{$contPage->id}}" {{ ($parentTypeContent==Request::segment(4) && $contPage->id==Request::segment(5)) ? 'selected':''}}> - - {{
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
										<option data-parentTypeId="{{$parentTypeProduct}}" value="{{$prod->id}}"{{ ($parentTypeProduct==Request::segment(4) && $prod->id==Request::segment(5)) ? 'selected':''}}> - - - -{{
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
	<div class="col-xs-12 p-0">
		<div class="bg-grey top-search-bar">
			<div class="checkbox-all pull-left p-10 p-l-0">
				<div class="pretty p-default p-square">
					<input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Маркира/Демаркира всички елементи" data-trigger="hover"/>
					<div class="state p-primary">
						<label></label>
					</div>
				</div>
			</div>
			<div class="collapse-buttons pull-left p-7">
				<a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Разпъва всички маркирани елементи"></i></a>
				<a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Прибира всички маркирани елементи"></i></a>
			</div>
			<div class="search pull-left hidden-xs">
				<div class="input-group">
					<input type="text" name="search" class="form-control input-sm search-text" placeholder="Търси">
					<span class="input-group-btn">
							<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
						</span>
				</div>
			</div>

			<div class="action-mass-buttons pull-right">
				<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/create') }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Създай нов">
					<i class="fas fa-plus"></i>
				</a>
				<a href="{{ url('/admin/catalogs/active/multiple/0/') }}" class="btn btn-lg tooltips light-grey-eye mass-unvisible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като НЕ активни/видими">
					<i class="far fa-eye-slash"></i>
				</a>
				<a href="{{ url('/admin/catalogs/active/multiple/1/') }}" class="btn btn-lg tooltips grey-eye mass-visible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като активни/видими">
					<i class="far fa-eye"></i>
				</a>
				<a href="#" class="btn btn-lg tooltips red mass-delete">
					<i class="fas fa-trash-alt"></i>
				</a>
				<div class="hidden" id="mass-delete-url">{{ url('/admin/catalogs/delete/multiple/') }}</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs))
						<?php $i = 1;?>
						@foreach($catalogs as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 1</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs1))
						<?php $i = 1;?>
						@foreach($catalogs1 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 2</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs2))
						<?php $i = 1;?>
						@foreach($catalogs2 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 3</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs3))
						<?php $i = 1;?>
						@foreach($catalogs3 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 4</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs4))
						<?php $i = 1;?>
						@foreach($catalogs4 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 5</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs5))
						<?php $i = 1;?>
						@foreach($catalogs5 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h3>Основна позиция: След допълнителни полета 6</h3>
			<div class="table-responsive">
				<table class="table">
					<thead>
					<th class="width-2-percent"></th>
					<th class="width-2-percent">Ред</th>
					<th>Заглавие</th>
					<th>Основна позиция</th>
					<th class="width-220">Действия</th>
					</thead>
					<tbody>
					@if(count($catalogs6))
						<?php $i = 1;?>
						@foreach($catalogs6 as $catalog)
							<?php
							$navDefaultTtanslation = $catalog->translations()->where('language_id', 1)->first();
							$mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', 1)->first();
							?>
							<tr class="t-row row-{{$catalog->id}}" data-toggle="popover" data-content='
								@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
									<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
								@else
									<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
								@endif
									'>
								<td class="width-2-percent">
									<div class="pretty p-default p-square">
										<input type="checkbox" class="checkbox-row" name="check[]" value="{{$catalog->id}}"/>
										<div class="state p-primary">
											<label></label>
										</div>
									</div>
								</td>
								<td class="width-2-percent">{{$i}}</td>
								<td>
									<span class="text-uppercase">{{ $navDefaultTtanslation->language->code }}: </span>
									{{ $navDefaultTtanslation->short_description }}
								</td>
								<td>{{ trans('administration_messages.additional_gallery_main_position_'.$catalog->main_position) }}</td>
								<td class="pull-right">
									<a href="{{ url('/admin/catalogs/'.Request::segment(4).'/'.Request::segment(5).'/'.$catalog->id.'/edit') }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
									@if(!$catalog->active)
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
									@else
										<a href="{{ url('/admin/catalogs/active/'.$catalog->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
									@endif
									@if($i !== 1)
										<a href="{{ url('/admin/catalogs/move/up/'.$catalog->id) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
									@endif
									@if($i != count($catalogs))
										<a href="{{ url('/admin/catalogs/move/down/'.$catalog->id) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
									@endif
									<a href="{{ url('/admin/catalogs/'.$catalog->id.'/delete') }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
								</td>
							</tr>
							<tr class="t-row-details row-{{$catalog->id}}-details hidden">
								<td colspan="2"></td>
								<td colspan="2">
								</td>
								<td class="width-220">
									@if (file_exists($mainCatalogTranslation->fullImageFilePath()))
										<img class="thumbnail img-responsive" src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}"/>
									@else
										<img class="thumbnail img-responsive" src="{{ asset('/admin/assets/system_images/thumbnail_img.png') }}"/>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
						<tr style="display: none;">
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@else
						<tr>
							<td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection