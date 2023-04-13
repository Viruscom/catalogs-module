<?php

namespace Modules\Catalogs\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogTranslation extends Model
{
    protected $table = "catalog_translation";
    protected $fillable = ['catalog_id','locale', 'short_description'];

    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
        ];

        if ($request->has('short_description_' . $language->code)) {
            $data['short_description'] = $request['short_description_' . $language->code];
        }

        return $data;
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'id', 'catalog_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
