<?php

namespace Modules\Catalogs\Actions;

use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Http\Requests\Pages\PageUpdateRequest;
use App\Models\CategoryPage\CategoryPage;
use App\Models\Files\File;
use App\Models\Pages\Page;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\AdBoxes\Models\AdBox;
use Modules\AdBoxes\Models\AdBoxTranslation;

class CommonCatalogAction
{
    public function validateServerRequirements(): array
    {
        $errors              = [
            0 => trans('catalogs::admin.common.invalid_server_requirements')
        ];
        $upload_max_filesize = ini_get('upload_max_filesize');
        $post_max_size       = ini_get('post_max_size');

        if (preg_match('/^(\d+)([KMG]?)$/', $upload_max_filesize, $matches)) {
            $size = $matches[1];
            switch (strtoupper($matches[2])) {
                case 'G':
                    $size *= 1024;
                case 'M':
                    $size *= 1024;
                case 'K':
                    $size *= 1024;
            }
            if ($size < 200 * 1024 * 1024) {
                $errors[] = trans('catalogs::admin.common.upload_max_filesize');
            }
        }

        if (preg_match('/^(\d+)([KMG]?)$/', $post_max_size, $matches)) {
            $size = $matches[1];
            switch (strtoupper($matches[2])) {
                case 'G':
                    $size *= 1024;
                case 'M':
                    $size *= 1024;
                case 'K':
                    $size *= 1024;
            }
            if ($size < 200 * 1024 * 1024) {
                $errors[] = trans('catalogs::admin.common.post_max_size');
            }
        }

        return $errors;
    }
}
