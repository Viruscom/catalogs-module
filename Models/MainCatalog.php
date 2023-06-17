<?php

namespace Modules\Catalogs\Models;

use App\Helpers\CacheKeysHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class MainCatalog extends Model implements TranslatableContract, CommonModelInterface
{
    use Translatable, Scopes, CommonActions;

    public array $translatedAttributes = ['title', 'filename', 'thumbnail'];
    protected    $table                = "catalogs_main";
    protected    $fillable             = ['active', 'position'];

    public static function getRequestData($request)
    {
        $data = [];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
        }

        return $data;
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = MainCatalogTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
    public static function cacheUpdate()
    {
        cache()->forget(CacheKeysHelper::$CATALOGS_MAIN_ADMIN);
        cache()->rememberForever(CacheKeysHelper::$CATALOGS_MAIN_ADMIN, function () {
            return self::with('translations')->withTranslation()->orderBy('position')->get();
        });

        cache()->forget(CacheKeysHelper::$CATALOGS_MAIN_FRONT);
        cache()->rememberForever(CacheKeysHelper::$CATALOGS_MAIN_FRONT, function () {
            return self::where('active', true)->with('translations')->withTranslation()->orderBy('position')->get();
        });
    }


}
