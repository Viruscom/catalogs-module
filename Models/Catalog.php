<?php

namespace Modules\Catalogs\Models;

use App\Interfaces\Models\CommonModelInterface;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Catalog extends Model implements TranslatableContract, CommonModelInterface
{
    use Translatable, Scopes, StorageActions, CommonActions;

    protected $table    = "catalogs";
    protected $fillable = ['parent_type_id', 'parent_id', 'show_in_header', 'show_in_gallery', 'active', 'main_position', 'position', 'creator_user_id', 'filename', 'main_catalog_id'];

    public static $IMAGES_PATH = "images/catalogs";



    public function parent(): BelongsTo
    {
        return $this->belongsTo(MainCatalog::class, 'main_catalog_id');
    }

    public static function generatePosition($parentTypeId, $parentId, $request)
    {
        $galleries = self::where('parent_type_id', $parentTypeId)->where('parent_id', $parentId)->where('main_position', $request->main_position)->orderBy('position', 'desc')->get();
        if (count($galleries) < 1) {
            return 1;
        }
        if (!$request->has('position') || is_null($request['position'])) {
            return $galleries->first()->position + 1;
        }

        if ($request['position'] > $galleries->first()->position) {
            return $galleries->first()->position + 1;
        }

        $galleriesUpdate = self::where('parent_type_id', $parentTypeId)->where('parent_id', $parentId)->where('main_position', $request->main_position)->where('position', '>=', $request['position'])->get();
        self::updateGalleyPosition($galleriesUpdate, true);

        return $request['position'];
    }

    public function updatedPosition($request)
    {
        if (!$request->has('position') || is_null($request->position) || ($request->position == $this->position)) {
            return $this->position;
        }

        $galleries = self::where('parent_type_id', $this->parent_type_id)->where('parent_id', $this->parent_id)->where('main_position', $request->main_position)->orderBy('position', 'desc')->get();

        if ($request['position'] > $galleries->first()->position) {
            $request['position'] = $galleries->first()->position;
        } elseif ($request['position'] < $galleries->last()->position) {
            $request['position'] = $galleries->last()->position;
        }

        if ($request['position'] >= $this->position) {
            $galleriesToUpdate = self::where('parent_type_id', $this->parent_type_id)->where('parent_id', $this->parent_id)->where('main_position', $request->main_position)->where('id', '<>', $this->id)->where('position', '>', $this->position)->where('position', '<=', $request['position'])->get();
            self::updateGalleyPosition($galleriesToUpdate, false);
        } else {
            $galleriesToUpdate = self::where('parent_type_id', $this->parent_type_id)->where('parent_id', $this->parent_id)->where('main_position', $request->main_position)->where('id', '<>', $this->id)->where('position', '<', $this->position)->where('position', '>=', $request['position'])->get();
            self::updateGalleyPosition($galleriesToUpdate, true);
        }

        return $request['position'];
    }

    public static function getCreateData($request)
    {
        $data                    = Catalog::getRequestData($request);
        $data['creator_user_id'] = Auth::user()->id;

        return $data;
    }

    public function getUpdateData($request)
    {
        $data                    = Catalog::getRequestData($request);
        $data['creator_user_id'] = $this->creator_user_id;

        return $data;
    }

    /**
     * Update gallery position
     *
     * @param $galleries / Galleries to update
     * @param bool $increment / Increment (true) or decrement (false)
     *
     * @return void
     */
    private static function updateGalleyPosition($galleries, $increment = true): void
    {
        foreach ($galleries as $galleryUpdate) {
            $position = ($increment) ? $galleryUpdate->position + 1 : $galleryUpdate->position - 1;
            $galleryUpdate->update(['position' => $position]);
        }
    }

    public static function getRequestData($request): array
    {
        $data = [
            'parent_type_id'  => $request->parent_type_id,
            'parent_id'       => $request->parent_id,
            'main_position'   => $request->main_position,
            'position'        => $request->position,
            'main_catalog_id' => $request->main_catalog_id
        ];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        $data['show_in_header'] = false;
        if ($request->has('show_in_header')) {
            $data['show_in_header'] = filter_var($request->show_in_header, FILTER_VALIDATE_BOOLEAN);
        }

        $data['show_in_gallery'] = false;
        if ($request->has('show_in_gallery')) {
            $data['show_in_gallery'] = filter_var($request->show_in_gallery, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('filename')) {
            $data['filename'] = $request->filename;
        }

        return $data;
    }

    public function directoryPath()
    {
        return public_path(self::$IMAGES_PATH . '/' . $this->parent_id);
    }

    public function fullImageFilePath()
    {
        return public_path(self::$IMAGES_PATH . '/' . $this->parent_id) . '/' . $this->filename;
    }

    public function fullImageFilePathUrl()
    {
        return url(self::$IMAGES_PATH . '/' . $this->parent_id) . '/' . $this->filename;
    }

    public function saveImage($image)
    {
        FileHelper::saveFile(public_path(self::$IMAGES_PATH . '/' . $this->parent_id), $image, $image->getClientOriginalName(), pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.png');
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        // TODO: Implement getLangArraysOnStore() method.
    }
}
