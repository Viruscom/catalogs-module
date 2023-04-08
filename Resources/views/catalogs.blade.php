@if(!is_null($catalogs))
    <ul class="list-thumbnails">
        @foreach($catalogs as $catalog)
                <?php
                $catalogTranslation = $catalog->translations()->where('language_id', $language->id)->first();
                $mainCatalogTranslation = $catalog->mainCatalog->translations()->where('language_id', $language->id)->first();
                $description = (is_null($catalogTranslation->short_description) || $catalogTranslation->short_description == "") ? $mainCatalogTranslation->short_description : $catalogTranslation->short_description;
                if (is_null($catalogTranslation)) {
                    continue;
                }
                ?>
            <li data-aos="fade-up">
                <div class="thumbnail-image">
                    <a href="{{ url($languageSlug.'/catalog/'.$catalog->main_catalog_id.'/preview') }}" target="_blank">
                        <img src="{{ $mainCatalogTranslation->fullImageFilePathUrl() }}" alt="{{ $description }}">
                    </a>
                </div>

                <a href="{{ url($languageSlug.'/catalog/'.$catalog->main_catalog_id.'/preview') }}" target="_blank">{{ $description }}</a>
            </li>
        @endforeach
    </ul>
@endif
