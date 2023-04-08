<?php

namespace Modules\Catalogs\Models;

use App\Helpers\CacheKeysHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Auth;
use Illuminate\Database\Eloquent\Model;

class MainCatalog extends Model implements TranslatableContract, CommonModelInterface
{
    use Translatable, Scopes, StorageActions, CommonActions;

    public const FILES_PATH = "images/catalogs/main";

    public array         $translatedAttributes        = ['title', 'filename', 'thumbnail'];
    protected            $table                       = "catalogs_main";
    protected $fillable = ['active', 'position'];

    public static function getRequestData($request)
    {
        // TODO: Implement getRequestData() method.
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        // TODO: Implement getLangArraysOnStore() method.
    }
    public static function cacheUpdate()
    {
        cache()->forget(CacheKeysHelper::$CATALOGS_MAIN_ADMIN);
        cache()->remember(CacheKeysHelper::$CATALOGS_MAIN_ADMIN, config('default.app.cache.ttl_seconds'), function () {
            return self::with('translations')->withTranslation()->orderBy('position')->get();
        });
    }
}
