@if(!is_null($catalogs) && $catalogs->isNotEmpty())
    <div class="list-thumbnails">
        <ul>
            @foreach($catalogs as $catalog)
                <li data-aos="fade-up">
                    <div class="thumbnail-image">
                        <a href="{{ route('front.catalogs.index', ['languageSlug' => $languageSlug, 'catalogId' => $catalog->main_catalog_id]) }}" target="_blank">
                            <img src="{{ $catalog->parent->translate($languageSlug)->fullImageFilePathUrl() }}" alt="{{ $catalog->short_description }}">
                        </a>
                    </div>
                    <a href="{{ route('front.catalogs.index', ['languageSlug' => $languageSlug, 'catalogId' => $catalog->main_catalog_id]) }}" target="_blank">{{ $catalog->short_description }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
