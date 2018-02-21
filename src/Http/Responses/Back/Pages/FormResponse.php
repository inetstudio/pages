<?php

namespace InetStudio\Pages\Http\Responses\Back\Pages;

use Illuminate\View\View;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Pages\Contracts\Http\Responses\Back\Pages\FormResponseContract;

/**
 * Class FormResponse.
 */
class FormResponse implements FormResponseContract, Responsable
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * FormResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при открытии формы страницы.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return View
     */
    public function toResponse($request): View
    {
        return view('admin.module.pages::back.pages.form', $this->data);
    }
}
