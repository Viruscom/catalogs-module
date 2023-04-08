<?php

namespace Modules\Catalogs\Models;

use App\Helpers\StorageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MainCatalogTranslation extends Model
{
    public const FILES_PATH = "catalogs/main";

    protected $table    = "catalogs_main_translation";
    protected $fillable = ['main_catalog_id', 'locale', 'title', 'filename', 'thumbnail'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MainCatalog::class, 'id', 'main_catalog_id');
    }

    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
        ];

        if ($request->has('filename_' . $language->code)) {
            $data['filename'] = StorageHelper::getCleanFilename($request['filename_' . $language->code]->getClientOriginalName());
        }

        if ($request->has('thumbnail_' . $language->code)) {
            $data['thumbnail'] = StorageHelper::getCleanFilename($request['thumbnail_' . $language->code]->getClientOriginalName());
        }

        return $data;
    }

    public function directoryPath(): string
    {
        return public_path(self::$IMAGES_PATH . '/' . $this->main_catalog_id);
    }

    public function fullImageFilePath(): string
    {
        return public_path(self::$IMAGES_PATH . '/' . $this->main_catalog_id) . '/' . $this->thumbnail;
    }

    public function fullImageFilePathUrl(): string
    {
        if ($this->thumbnail == '' || !file_exists(public_path(self::$IMAGES_PATH . '/' . $this->main_catalog_id . '/' . $this->thumbnail))) {
            return url('admin/assets/system_images/thumbnail_img.png');
        }

        return url(self::$IMAGES_PATH . '/' . $this->main_catalog_id) . '/' . $this->thumbnail;
    }

    public function fullPdfFilePath(): string
    {
        return public_path(self::$IMAGES_PATH . '/' . $this->main_catalog_id) . '/' . $this->filename;
    }

    public function fullPdfFilePathUrl(): string
    {
        return url(self::$IMAGES_PATH . '/' . $this->main_catalog_id) . '/' . $this->filename;
    }

    public function catalogPreviewPath(): string
    {
        return url(self::$IMAGES_PATH . '/' . $this->main_catalog_id) . '/' . $this->filename;
    }

    public static function boot()
    {
        parent::boot();

        self::deleted(function (\Modules\Catalogs\Models\MainCatalogTranslation $el) {
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
        StorageHelper::saveFile($this->getFilesPath(), $image);
    }

    public function getFilepath($filename): string
    {
        return $this->getFilesPath() . $filename;
    }
    public function getFilesPath(): string
    {
        return self::FILES_PATH . '/' . $this->main_catalog_id . '/';
    }
    public function savePdf($pdf)
    {
        StorageHelper::saveFile($this->getFilesPath(), $pdf);
    }
}
