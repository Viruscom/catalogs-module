<?php

namespace Modules\Catalogs\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;

class CatalogTranslation extends Model
{
    protected $table = "catalog_translation";
    protected $fillable = ['gallery_id','language_id', 'short_description'];

    public function gallery()
    {
        return $this->belongsTo(Catalog::class, 'id', 'gallery_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public static function getCreateData($language, $request): array
    {
        $data = [
            'language_id'=>$language->id,
        ];

        if ($request->has('short_description_'.$language->code)) {
            $data['short_description'] = $request['short_description_'.$language->code];
        }

        return $data;
    }

    public function getUpdateData($language, $request): array
    {
        $data = [
            'language_id'=>$language->id,
        ];

        if ($request->has('short_description_'.$language->code)) {
            $data['short_description'] = $request['short_description_'.$language->code];
        }

        return $data;
    }
}
