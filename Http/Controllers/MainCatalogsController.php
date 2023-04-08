<?php

namespace Modules\Catalogs\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use App\Http\Requests\CategoryPageStoreRequest;
use App\Models\Catalogs\MainCatalogTranslation;
use App\Models\CategoryPage\CategoryPage;
use App\Models\CategoryPage\CategoryPageTranslation;
use App\Models\Pages\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Catalogs\Actions\CommonCatalogAction;
use Modules\Catalogs\Http\Requests\MainCatalogRequest;
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
    public function store(MainCatalogRequest $request, CommonControllerAction $action): RedirectResponse
    {
        $mainCatalog = $action->doSimpleCreate(MainCatalog::class, $request);
        $languages = LanguageHelper::getActiveLanguages();

        foreach ($languages as $language) {
            $mainCatalogTranslation = $mainCatalog->translate($language->code);
            $mainCatalogTranslation->savePdf($request['filename_' . $language->code]);
            $mainCatalogTranslation->saveImage($request['thumbnail_' . $language->code]);
        }
        MainCatalog::cacheUpdate();
        $mainCatalog->storeAndAddNew($request);

        return redirect()->route('admin.catalogs.main.index')->with('success-message', trans('admin.common.successful_create'));
    }

    public function edit($id, CommonCatalogAction $action)
    {
        $errors = $action->validateServerRequirements();
        if (count($errors) > 1) {
            return redirect()->back()->withErrors($errors);
        }


    }

    public function update()
    {

    }

    public function delete($id, CommonControllerAction $action): RedirectResponse
    {
        $page = MainCatalog::find($id);
        MainHelper::goBackIfNull($page);

        $action->delete(MainCatalog::class, $page);

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }

    public function active($id, $active): RedirectResponse
    {
        $mainCatalog = MainCatalog::find($id);
        MainHelper::goBackIfNull($mainCatalog);

        $mainCatalog->update(['active' => $active]);
        MainCatalog::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function positionUp($id, CommonControllerAction $action): RedirectResponse
    {
        $mainCatalog = MainCatalog::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($mainCatalog);

        $action->positionUp(MainCatalog::class, $mainCatalog);
        MainCatalog::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function positionDown($id, CommonControllerAction $action): RedirectResponse
    {
        $mainCatalog = MainCatalog::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($mainCatalog);

        $action->positionDown(MainCatalog::class, $mainCatalog);
        MainCatalog::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $action->activeMultiple(MainCatalog::class, $request, $active);
        Page::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function deleteMultiple()
    {

    }
}
