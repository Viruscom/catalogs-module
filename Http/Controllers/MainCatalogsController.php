<?php

namespace Modules\Catalogs\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use App\Models\Pages\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Catalogs\Actions\CommonCatalogAction;
use Modules\Catalogs\Http\Requests\MainCatalogRequest;
use Modules\Catalogs\Models\MainCatalog;
use Modules\Catalogs\Models\MainCatalogTranslation;

class MainCatalogsController extends Controller
{

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
        $languages   = LanguageHelper::getActiveLanguages();

        foreach ($languages as $language) {
            $mainCatalogTranslation = $mainCatalog->translate($language->code);
            $mainCatalogTranslation->savePdf($request['filename_' . $language->code]);
            $mainCatalogTranslation->saveImage($request['thumbnail_' . $language->code]);
        }
        MainCatalog::cacheUpdate();
        if ($request->has('submitaddnew')) {
            return redirect()->back()->with('success-message', trans('admin.common.successful_create'));
        }

        return redirect()->route('admin.catalogs.main.index')->with('success-message', trans('admin.common.successful_create'));
    }

    public function edit($id, CommonCatalogAction $action)
    {
        $errors = $action->validateServerRequirements();
        if (count($errors) > 1) {
            return redirect()->back()->withErrors($errors);
        }
        $mainCatalog = MainCatalog::find($id);
        MainHelper::goBackIfNull($mainCatalog);

        $languages = LanguageHelper::getActiveLanguages();

        return view('catalogs::admin.main_catalogs.edit', ['languages' => $languages, 'mainCatalog' => $mainCatalog]);
    }

    public function update($id, MainCatalogRequest $request, CommonControllerAction $action): RedirectResponse
    {
        $mainCatalog = MainCatalog::find($id);
        MainHelper::goBackIfNull($mainCatalog);

        $languages = LanguageHelper::getActiveLanguages();

        foreach ($languages as $language) {
            $mainCatalogTranslation = $mainCatalog->translate($language->code);
            if ($request->has('filename_' . $language->code)) {
                $mainCatalogTranslation->deleteFile($mainCatalogTranslation->filename);
                $mainCatalogTranslation->savePdf($request['filename_' . $language->code]);
            }
            if ($request->has('thumbnail_' . $language->code)) {
                $mainCatalogTranslation->deleteFile($mainCatalogTranslation->thumbnail);
                $mainCatalogTranslation->saveImage($request['thumbnail_' . $language->code]);
            }
        }
        $action->doSimpleUpdate(MainCatalog::class, MainCatalogTranslation::class, $mainCatalog, $request);
        MainCatalog::cacheUpdate();

        return redirect()->route('admin.catalogs.main.index')->with('success-message', trans('admin.common.successful_edit'));
    }

    public function delete($id, CommonControllerAction $action): RedirectResponse
    {
        $mainCatalog = MainCatalog::find($id);
        MainHelper::goBackIfNull($mainCatalog);

        $path = MainCatalogTranslation::FILES_PATH.'/'.$mainCatalog->id.'/';
        if (Storage::disk('public')->exists($path)) {
            File::deleteDirectory(Storage::disk('public')->path($path));
        }
        $action->delete(MainCatalog::class, $mainCatalog);
        MainCatalog::cacheUpdate();

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
        MainCatalog::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function deleteMultiple(Request $request): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $ids = array_map('intval', explode(',', $request->ids[0]));
            foreach ($ids as $id) {
                $model = MainCatalog::find($id);
                if (is_null($model)) {
                    continue;
                }

                $modelsToUpdate = MainCatalog::where('position', '>', $model->position)->get();
                $path = MainCatalogTranslation::FILES_PATH.'/'.$model->id.'/';
                if (Storage::disk('public')->exists($path)) {
                    File::deleteDirectory(Storage::disk('public')->path($path));
                }
                $model->delete();
                foreach ($modelsToUpdate as $modelToUpdate) {
                    $modelToUpdate->update(['position' => $modelToUpdate->position - 1]);
                }
            }

            MainCatalog::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }
}
