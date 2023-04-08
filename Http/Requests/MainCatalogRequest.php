<?php

namespace Modules\Catalogs\Http\Requests;

use App\Models\Language;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class MainCatalogRequest extends FormRequest
{
    protected $LANGUAGES;

    public function __construct()
    {
        $this->LANGUAGES = Language::where('active', true)->get();
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function trimInput(): void
    {
        $trim_if_string = function ($var) {
            return is_string($var) ? trim($var) : $var;
        };
        $this->merge(array_map($trim_if_string, $this->all()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->trimInput();
        $array = [
            // 'code' => 'required'
        ];

        foreach ($this->LANGUAGES  as $language) {
            $array['title_'.$language->code] = 'required';
            if ($this->segment(4) == 'store') {
                $array['filename_'.$language->code] = 'required|mimes:pdf|max:200000';
                $array['thumbnail_'.$language->code] = 'required|mimes:png|max:3000';
            }

            if ($this->segment(5) == 'update') {
                $array['filename_'.$language->code] = 'mimes:pdf|max:200000';
                $array['thumbnail_'.$language->code] = 'mimes:png|max:3000';
            }
        }

        return $array;
    }

    public function messages()
    {
        $messages = [
            // 'code.required' => 'Полето за код на населено място е задължително'
        ];

        foreach ($this->LANGUAGES  as $language) {
            $messages['title_'.$language->code.'.required'] = 'Заглавието за езикова версия '.$language->code.' е задължително';
            $messages['filename_'.$language->code.'.required'] = 'Каталогът за езикова версия '.$language->code.' е задължителен';
            $messages['filename_'.$language->code.'.mimes'] = 'Каталогът за езикова версия '.$language->code.' трябва да е .pdf файл';
            $messages['filename_'.$language->code.'.max'] = 'Каталогът за езикова версия '.$language->code.' трябва да е максимум 200 МВ';
            $messages['thumbnail_'.$language->code.'.required'] = 'Thumbnail за езикова версия '.$language->code.' е задължителен';
            $messages['thumbnail_'.$language->code.'.mimes'] = 'Thumbnail за езикова версия '.$language->code.' трябва да е .png файл';
            $messages['thumbnail_'.$language->code.'.max'] = 'Thumbnail за езикова версия '.$language->code.' трябва да е максимум 3 МВ';
        }

        return $messages;
    }
}
