<?php

namespace Modules\Catalogs\Models;

use App\Helpers\AdminHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Catalog extends Model implements TranslatableContract, CommonModelInterface
{
    use Translatable, Scopes, StorageActions, CommonActions;

    const CATALOGS_AFTER_DESCRIPTION              = "catalogsAfterDescription";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_1 = "catalogsAfterAdditionalDescription_1";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_2 = "catalogsAfterAdditionalDescription_2";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_3 = "catalogsAfterAdditionalDescription_3";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_4 = "catalogsAfterAdditionalDescription_4";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_5 = "catalogsAfterAdditionalDescription_5";
    const CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_6 = "catalogsAfterAdditionalDescription_6";

    public static string $CATALOG_SYSTEM_IMAGE  = 'catalogs_1_image.png';
    public static string $CATALOG_RATIO         = '1/1';
    public static string $CATALOG_MIMES         = 'jpg,jpeg,png,gif';
    public static string $CATALOG_MAX_FILE_SIZE = '3000';
    public static string $IMAGES_PATH = "images/catalogs";
    public array $translatedAttributes = ['short_description'];
    protected    $table                = "catalogs";
    protected    $fillable             = ['main_catalog_id', 'module', 'model', 'model_id', 'active', 'main_position', 'position', 'creator_user_id', 'filename'];
    public static function getCollections($parentModel): array
    {
        return [
            self::CATALOGS_AFTER_DESCRIPTION              => self::getCatalogs($parentModel, self::CATALOGS_AFTER_DESCRIPTION),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_1 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_1),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_2 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_2),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_3 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_3),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_4 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_4),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_5 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_5),
            self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_6 => self::getCatalogs($parentModel, self::CATALOGS_AFTER_ADDITIONAL_DESCRIPTION_6),
        ];
    }

    public static function getCatalogs($parentModel, $mainPosition)
    {
        return Catalog::where('model', get_class($parentModel))
            ->where('model_id', $parentModel->id)
            ->where('main_position', $mainPosition)->with('translations', 'parent', 'parent.translations')->orderBy('position')->get();
    }
    public static function generatePosition($request)
    {
        $splitPath = explode("-", decrypt($request->path));
        $query     = self::where('module', $splitPath[0])->where('model', $splitPath[1])->where('model_id', $splitPath[2])->where('main_position', $request->mainPosition);

        $galleries = $query->orderBy('position', 'desc')->get();
        if (count($galleries) < 1) {
            return 1;
        }
        if (!$request->has('position') || is_null($request['position'])) {
            return $galleries->first()->position + 1;
        }

        if ($request['position'] > $galleries->first()->position) {
            return $galleries->first()->position + 1;
        }

        $galleriesUpdate = $query->where('position', '>=', $request['position'])->get();
        self::updateModelsPosition($galleriesUpdate, true);

        return $request['position'];
    }
    private static function updateModelsPosition($models, $increment = true): void
    {
        foreach ($models as $model) {
            $position = ($increment) ? $model->position + 1 : $model->position - 1;
            $model->update(['position' => $position]);
        }
    }
    public static function getRequestData($request): array
    {
        $splitPath = explode("-", decrypt($request->path));
        $data      = [
            'main_catalog_id' => $request->main_catalog_id,
            'main_position'   => $request->mainPosition,
            'module'          => $splitPath[0],
            'model'           => $splitPath[1],
            'model_id'        => $splitPath[2],
        ];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = CatalogTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MainCatalog::class, 'main_catalog_id');
    }
    public function updatedPosition($request)
    {
        if (!$request->has('position') || is_null($request->position) || ($request->position == $this->position)) {
            return $this->position;
        }
        $splitPath = explode("-", decrypt($request->path));
        $query     = self::where('module', $splitPath[0])->where('model', $splitPath[1])->where('model_id', $splitPath[2])->where('main_position', $request->mainPosition);

        $galleries = $query->orderBy('position', 'desc')->get();

        if ($request['position'] > $galleries->first()->position) {
            $request['position'] = $galleries->first()->position;
        } elseif ($request['position'] < $galleries->last()->position) {
            $request['position'] = $galleries->last()->position;
        }

        if ($request['position'] >= $this->position) {
            $galleriesToUpdate = $query->where('id', '<>', $this->id)->where('position', '>', $this->position)->where('position', '<=', $request['position'])->get();
            self::updateModelsPosition($galleriesToUpdate, false);
        } else {
            $galleriesToUpdate = $query->where('id', '<>', $this->id)->where('position', '<', $this->position)->where('position', '>=', $request['position'])->get();
            self::updateModelsPosition($galleriesToUpdate, true);
        }

        return $request['position'];
    }
    public function getSystemImage(): string
    {
        return AdminHelper::getSystemImage(self::$CATALOG_SYSTEM_IMAGE);
    }
    public function getUrl()
    {
        if (!is_null($this->url)) {
            if ($this->external_url) {
                return $this->url;
            }

            return url($this->url);
        }

        return '';
    }
    public function getFilepath($filename): string
    {
        return $this->getFilesPath() . $filename;
    }
    public function getFilesPath(): string
    {
        return self::FILES_PATH . '/' . $this->id . '/';
    }

}
