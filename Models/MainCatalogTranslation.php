<?php

namespace Modules\Catalogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MainCatalogTranslation extends Model
{
    protected $table    = "catalogs_main_translation";
    protected $fillable = ['main_catalog_id', 'locale', 'title', 'filename', 'thumbnail'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MainCatalog::class, 'id', 'main_catalog_id');
    }

    public static function getCreateData($language, $request)
    {
        $data = [
            'language_id'=>$language->id,
            'filename'=>$request->filename,
            'thumbnail'=>$request->thumbnail,
        ];

        if ($request->has('title_'.$language->code)) {
            $data['title'] = $request['title_'.$language->code];
        }

        return $data;
    }

    public function getUpdateData($language, $request)
    {
        $data = [
            'language_id'=>$language->id,
        ];

        if ($request->has('title_'.$language->code)) {
            $data['title'] = $request['title_'.$language->code];
        }
        if ($request->has('filename')) {
            $data['filename'] = $request['filename'];
        }
        if ($request->has('thumbnail')) {
            $data['thumbnail'] = $request['thumbnail'];
        }

        return $data;
    }

    public function directoryPath(): string
    {
        return public_path(self::$IMAGES_PATH.'/'.$this->main_catalog_id);
    }

    public function fullImageFilePath(): string
    {
        return public_path(self::$IMAGES_PATH.'/'.$this->main_catalog_id).'/'.$this->thumbnail;
    }

    public function fullImageFilePathUrl(): string
    {
        if ($this->thumbnail == '' || !file_exists(public_path(self::$IMAGES_PATH . '/' . $this->main_catalog_id . '/' . $this->thumbnail))) {
            return url('admin/assets/system_images/thumbnail_img.png');
        }

        return url(self::$IMAGES_PATH.'/'.$this->main_catalog_id).'/'.$this->thumbnail;
    }

    public function fullPdfFilePath(): string
    {
        return public_path(self::$IMAGES_PATH.'/'.$this->main_catalog_id).'/'.$this->filename;
    }

    public function fullPdfFilePathUrl(): string
    {
        return url(self::$IMAGES_PATH.'/'.$this->main_catalog_id).'/'.$this->filename;
    }

    public function catalogPreviewPath(): string
    {
        return url(self::$IMAGES_PATH.'/'.$this->main_catalog_id).'/'.$this->filename;
    }

    public static function boot()
    {
        parent::boot();

        self::deleted(function (\App\Models\Catalogs\MainCatalogTranslation $el) {
            if (file_exists($el->fullImageFilePath())) {
                unlink($el->fullImageFilePath());
            }

            if (file_exists($el->fullPdfFilePath())) {
                unlink($el->fullPdfFilePath());
            }

            if (count(FileHelper::getFilesFromDirectory($el->directoryPath())) == 0) {
                File::deleteDirectory($el->directoryPath(), false);
            }
        });
    }

    public function saveImage($image): void
    {
        FileHelper::saveFile(public_path(self::$IMAGES_PATH.'/'.$this->main_catalog_id), $image, $image->getClientOriginalName(), pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.png');
    }

    public function savePdf($pdf)
    {
        FileHelper::saveFile(public_path(self::$IMAGES_PATH.'/'.$this->main_catalog_id), $pdf, $pdf->getClientOriginalName(), pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME).'.pdf');
    }
}
