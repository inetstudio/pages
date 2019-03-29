<?php

namespace InetStudio\Pages\Http\Responses\Back\Utility;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class SuggestionsResponse.
 */
class SuggestionsResponse implements SuggestionsResponseContract, Responsable
{
    /**
     * @var array
     */
    protected $suggestions;

    /**
     * SuggestionsResponse constructor.
     *
     * @param array $suggestions
     */
    public function __construct(array $suggestions)
    {
        $this->suggestions = $suggestions;
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
        return response()->json($this->suggestions);
    }
}
