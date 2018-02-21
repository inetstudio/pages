<?php

namespace InetStudio\Pages\Http\Responses\Back\Utility;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SlugResponseContract;

/**
 * Class SlugResponse.
 */
class SlugResponse implements SlugResponseContract, Responsable
{
    /**
     * @var string
     */
    private $slug;

    /**
     * SlugResponse constructor.
     *
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Возвращаем slug по заголовку объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json($this->slug);
    }
}
