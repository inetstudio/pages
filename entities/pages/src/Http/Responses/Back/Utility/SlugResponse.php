<?php

namespace InetStudio\PagesPackage\Pages\Http\Responses\Back\Utility;

use Illuminate\Http\Request;
use InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract;

/**
 * Class SlugResponse.
 */
class SlugResponse implements SlugResponseContract
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * SlugResponse constructor.
     *
     * @param  string  $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Возвращаем slug по заголовку объекта.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return response()->json($this->slug);
    }
}
