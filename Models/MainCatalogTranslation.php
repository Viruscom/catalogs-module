<?php

namespace Modules\Catalogs\Models;

use App\Helpers\StorageHelper;
use App\Traits\StorageActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MainCatalogTranslation extends Model
{
    use StorageActions;

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
        return public_path($this->getFilesPath());
    }

    public function fullImageFilePath(): string
    {
        return public_path($this->getFilesPath() . $this->thumbnail);
    }

    public function fullImageFilePathUrl(): string
    {
        if (!is_null($this->thumbnail) && $this->existsFile($this->thumbnail)) {
            return Storage::disk('public')->url($this->getFilepath($this->thumbnail));
        }

        return url($this->getSystemImage());
    }

    public function fullPdfFilePath(): string
    {
        return public_path($this->getFilesPath() . $this->filename);
    }

    public function fullPdfFilePathUrl(): string
    {
        if (!is_null($this->filename) && $this->existsFile($this->filename)) {
            return Storage::disk('public')->url($this->getFilepath($this->filename));
        }

        return url('/');
    }

    public function getSystemImage()
    {
        return url('admin/assets/system_images/thumbnail_img.png');
    }
    public function catalogPreviewPath(): string
    {
        return url($this->getFilesPath() . $this->filename);
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
                $this->deleteDirectory($el->getFilesPath());
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
        return self::FILES_PATH . '/' . $this->main_catalog_id . '/' . $this->locale . '/';
    }
    public function savePdf($pdf)
    {
        StorageHelper::saveFile($this->getFilesPath(), $pdf);
    }
}
