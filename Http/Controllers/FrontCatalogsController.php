<?php

namespace Modules\Catalogs\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Helpers\WebsiteHelper;
use Illuminate\Routing\Controller;
use Modules\Catalogs\Models\MainCatalog;

class FrontCatalogsController extends Controller
{
    public function previewCatalog($languageSlug, $catalogId)
    {
        $mainCatalog = MainCatalog::find($catalogId);
        WebsiteHelper::abortIfNull($mainCatalog);

        $language               = LanguageHelper::getCurrentLanguage();
        $mainCatalogTranslation = $mainCatalog->translate($languageSlug);

        return view('catalogs::catalog_preview', compact('language', 'mainCatalog', 'mainCatalogTranslation'));
    }
}
