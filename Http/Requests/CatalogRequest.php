<?php

namespace Modules\Catalogs\Http\Requests;

use App\Helpers\LanguageHelper;
use App\Models\Language;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CatalogRequest extends FormRequest
{
    protected $LANGUAGES;

    public function __construct()
    {
        $this->LANGUAGES = LanguageHelper::getActiveLanguages();
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
             'main_catalog_id' => 'required|integer'
        ];

        foreach ($this->LANGUAGES  as $language) {
            $array['short_description_'.$language->code] = 'required';
        }

        return $array;
    }

    public function messages()
    {
        $messages = [
             'main_catalog_id.required' => 'Полето за избор на каталог е задължително',
             'main_catalog_id.integer' => 'Полето за избор на каталог трябва да е цяло число',
        ];

        foreach ($this->LANGUAGES  as $language) {
            $messages['short_description_'.$language->code.'.required'] = 'Описанието за езикова версия '.$language->code.' е задължително';
        }

        return $messages;
    }
}
