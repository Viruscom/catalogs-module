<?php

namespace Modules\Catalogs\Http\Controllers;

use App\Helpers\AdminHelper;
use App\Helpers\LanguageHelper;
use App\Models\Gallery\Gallery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CatalogsController extends Controller
{
    /**
     * Display a listing of the resource.
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
            $model         = $modelClass::with(self::getRelationships($modelInstance))->where('id', $splitPath[2])->first();
            if (is_null($model)) {
                return view('catalogs::admin.error_show');
            }
            $languages = LanguageHelper::getActiveLanguages();

            return view('catalogs::admin.show', ['moduleName' => $splitPath[0], 'modelPath' => $modelClass, 'model' => $model, 'languages' => $languages]);
        }
    }

    private static function getModelInstance($modelInstance, $galleryImage)
    {
        return $modelInstance::with(self::getRelationships($modelInstance))->where('id', $galleryImage->model_id)->first();
    }
    private static function getRelationships($modelInstance)
    {
        $data          = [];
        $relationships = Gallery::galleryTypesArray();
        foreach ($relationships as $relationship) {
            if (method_exists($modelInstance, $relationship)) {
                $data[] = $relationship;
            }
        }

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('catalogs::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('catalogs::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('catalogs::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
