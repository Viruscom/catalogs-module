<?php

    namespace Modules\Catalogs\Http\Controllers;

    use App\Actions\CommonControllerAction;
    use App\Helpers\AdminHelper;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use Illuminate\Contracts\Support\Renderable;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Modules\Catalogs\Http\Requests\CatalogRequest;
    use Modules\Catalogs\Models\Catalog;
    use Modules\Catalogs\Models\CatalogTranslation;
    use Modules\Catalogs\Models\MainCatalog;

    class CatalogsController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Renderable
         */
        public function index()
        {
            $data = AdminHelper::getInternalLinksUrls([]);

            return view('catalogs::admin.index', $data);
        }

        public function getEncryptedPath(Request $request)
        {
            return encrypt($request->moduleName . '-' . $request->modelPath . '-' . $request->modelId);
        }

        public function loadCatalogPage($path)
        {
            $splitPath = explode("-", decrypt($path));

            $modelClass = $splitPath[1];
            if (!class_exists($modelClass)) {
                return view('catalogs::admin.error_show');
            } else {
                $modelInstance = new $modelClass;
                $modelConstant = get_class($modelInstance) . '::ALLOW_CATALOGS';
                if (!defined($modelConstant) || !constant($modelConstant)) {
                    return view('catalogs::admin.error_show');
                }

                $model = $modelClass::where('id', $splitPath[2])->first();
                if (is_null($model)) {
                    return view('catalogs::admin.error_show');
                }
                $languages         = LanguageHelper::getActiveLanguages();
                $model['Catalogs'] = Catalog::getCollections($model);

                return view('catalogs::admin.show', ['moduleName' => $splitPath[0], 'modelPath' => $modelClass, 'model' => $model, 'languages' => $languages]);
            }
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Renderable
         */
        public function create()
        {
            if (is_null(cache()->get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN))) {
                MainCatalog::cacheUpdate();
            }

            return view('catalogs::admin.create', [
                'languages'    => LanguageHelper::getActiveLanguages(),
                'mainCatalogs' => cache()->get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN)
            ]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         *
         * @return RedirectResponse
         */
        public function store(CatalogRequest $request, CommonControllerAction $action)
        {
            $splitPath  = explode("-", decrypt($request->path));
            $modelClass = $splitPath[1];
            if (!class_exists($modelClass)) {
                return redirect()->back()->withErrors(['catalogs::admin.catalogs.warning_class_not_found']);
            }

            $catalog = $action->doSimpleCreate(Catalog::class, $request);
            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.catalogs.manage.load-catalog', ['path' => $request->path])->with('success-message', trans('admin.common.successful_create'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return Renderable
         */
        public function edit($id)
        {
            $catalog = Catalog::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($catalog);

            if (is_null(cache()->get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN))) {
                MainCatalog::cacheUpdate();
            }

            return view('catalogs::admin.edit', [
                'catalog'      => $catalog,
                'languages'    => LanguageHelper::getActiveLanguages(),
                'mainCatalogs' => cache()->get(CacheKeysHelper::$CATALOGS_MAIN_ADMIN)
            ]);
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $ids = array_map('intval', explode(',', $request->ids[0]));
                foreach ($ids as $id) {
                    $model = Catalog::find($id);
                    if (is_null($model)) {
                        continue;
                    }

                    $modelsToUpdate = Catalog::where('module', $model->module)->where('model', $model->model)->where('model_id', $model->model_id)->where('main_position', $model->main_position)->where('position', '>', $model->position)->get();
                    $model->delete();
                    foreach ($modelsToUpdate as $modelToUpdate) {
                        $modelToUpdate->update(['position' => $modelToUpdate->position - 1]);
                    }
                }

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function delete($id)
        {
            $catalog = Catalog::where('id', $id)->first();
            MainHelper::goBackIfNull($catalog);

            $modelsToUpdate = Catalog::where('module', $catalog->module)->where('model', $catalog->model)->where('model_id', $catalog->model_id)->where('main_position', $catalog->main_position)->where('position', '>', $catalog->position)->get();
            $catalog->delete();
            foreach ($modelsToUpdate as $currentModel) {
                $currentModel->update(['position' => $currentModel->position - 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int $id
         *
         * @return Renderable
         */
        public function update($id, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $catalog = Catalog::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($catalog);

            $request['path']         = encrypt($catalog->module . '-' . $catalog->model . '-' . $catalog->model_id);
            $request['mainPosition'] = $catalog->main_position;
            $action->doSimpleUpdate(Catalog::class, CatalogTranslation::class, $catalog, $request);

            return redirect()->route('admin.catalogs.manage.load-catalog', ['path' => $request->path])->with('success-message', 'admin.common.successful_edit');
        }
        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(Catalog::class, $request, $active);

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function active($id, $active): RedirectResponse
        {
            $catalog = Catalog::find($id);
            MainHelper::goBackIfNull($catalog);

            $catalog->update(['active' => $active]);

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionUp($id, CommonControllerAction $action): RedirectResponse
        {
            $catalog = Catalog::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($catalog);

            $previousModel = Catalog::where('module', $catalog->module)->where('model', $catalog->model)->where('model_id', $catalog->model_id)->where('main_position', $catalog->main_position)->where('position', $catalog->position - 1)->first();
            if (!is_null($previousModel)) {
                $previousModel->update(['position' => $previousModel->position + 1]);
                $catalog->update(['position' => $catalog->position - 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionDown($id, CommonControllerAction $action): RedirectResponse
        {
            $catalog = Catalog::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($catalog);

            $nextModel = Catalog::where('module', $catalog->module)->where('model', $catalog->model)->where('model_id', $catalog->model_id)->where('main_position', $catalog->main_position)->where('position', $catalog->position + 1)->first();
            if (!is_null($nextModel)) {
                $nextModel->update(['position' => $nextModel->position - 1]);
                $catalog->update(['position' => $catalog->position + 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
    }
