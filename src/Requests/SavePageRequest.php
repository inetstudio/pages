<?php

namespace InetStudio\Pages\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SavePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'meta.title.max' => 'Поле «Title» не должно превышать 255 символов',
            'meta.description.max' => 'Поле «Description» не должно превышать 255 символов',
            'meta.keywords.max' => 'Поле «Keywords» не должно превышать 255 символов',

            'meta.og:title.max' => 'Поле «og:title» не должно превышать 255 символов',
            'meta.og:description.max' => 'Поле «og:description» не должно превышать 255 символов',

            'og_image.crop.default.crop_size' => 'Фиксированный размер области — 968x475 пикселей',
            'og_image.crop.default.json' => 'Область отображения должна быть представлена в виде JSON',

            'title.required' => 'Поле «Заголовок» обязательно для заполнения',
            'title.max' => 'Поле «Заголовок» не должно превышать 255 символов',

            'slug.required' => 'Поле «URL» обязательно для заполнения',
            'slug.alpha_dash' => 'Поле «URL» может содержать только латинские символы, цифры, дефисы и подчеркивания',
            'slug.max' => 'Поле «URL» не должно превышать 255 символов',
            'slug.unique' => 'Такое значение поля «URL» уже существует',

            'preview.crop.3_2.json' => 'Область отображения 3x2 должна быть представлена в виде JSON',
            'preview.crop.3_4.json' => 'Область отображения 3x4 должна быть представлена в виде JSON',
            'preview.crop.3_2.crop_size' => 'Минимальный размер области 3x2 — 768x512 пикселей',
            'preview.crop.3_4.crop_size' => 'Минимальный размер области 3x4 — 384x512 пикселей',
            'preview.description.max' => 'Поле «Описание» не должно превышать 255 символов',
            'preview.copyright.max' => 'Поле «Copyright» не должно превышать 255 символов',
            'preview.alt.max' => 'Поле «Alt» не должно превышать 255 символов',

            'tags.array' => 'Поле «Теги» должно содержать значение в виде массива',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'meta.title' => 'max:255',
            'meta.description' => 'max:255',
            'meta.keywords' => 'max:255',
            'meta.og:title' => 'max:255',
            'meta.og:description' => 'max:255',

            'og_image.crop.default' => 'nullable|json|crop_size:968,475,fixed',

            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255|unique:pages,slug,'.$request->get('page_id'),

            'preview.crop.3_2' => 'nullable|json|crop_size:768,512,min',
            'preview.crop.3_4' => 'nullable|json|crop_size:384,512,min',
            'preview.description' => 'max:255',
            'preview.copyright' => 'max:255',
            'preview.alt' => 'max:255',
            'tags' => 'array',
        ];
    }
}
