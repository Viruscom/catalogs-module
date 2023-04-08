<?php

namespace Modules\Catalogs\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Http\Requests\CategoryPageStoreRequest;
use App\Models\CategoryPage\CategoryPage;
use App\Models\CategoryPage\CategoryPageTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Catalogs\Actions\CommonCatalogAction;
use Modules\Catalogs\Http\Requests\MainCatalogStoreRequest;
use Modules\Catalogs\Models\MainCatalog;

class MainCatalogsController extends Controller {

    public function index()
    {
        if (is_null(Cache::get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN))) {
            MainCatalog::cacheUpdate();
        }
        return view('catalogs::admin.main_catalogs.index', ['mainCatalogs' => Cache::get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN)]);
    }

    public function create(CommonCatalogAction $action)
    {
        $errors = $action->validateServerRequirements();
        if (count($errors) > 1) {
            return redirect()->back()->withErrors($errors);
        }

        return view('catalogs::admin.main_catalogs.create', ['languages' => LanguageHelper::getActiveLanguages()]);
    }
    public function store(MainCatalogStoreRequest $request, CommonControllerAction $action): RedirectResponse
    {
        if ($request->has('image')) {
            $request->validate(['image' => FileDimensionHelper::getRules('CategoryPage', 1)], FileDimensionHelper::messages('CategoryPage', 1));
        }
        $categoryPage = $action->doSimpleCreate(CategoryPage::class, $request);
        $action->updateUrlCache($categoryPage, CategoryPageTranslation::class);
        CategoryPage::cacheUpdate();

        $categoryPage->storeAndAddNew($request);

        return redirect()->route('admin.category-page.index')->with('success-message', trans('admin.common.successful_create'));
    }

    public function edit($id, CommonCatalogAction $action)
    {
        $errors = $action->validateServerRequirements();
        if (count($errors) > 1) {
            return redirect()->back()->withErrors($errors);
        }


    }
}
