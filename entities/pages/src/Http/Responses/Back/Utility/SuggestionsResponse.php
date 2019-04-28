<?php

namespace InetStudio\PagesPackage\Pages\Http\Responses\Back\Utility;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PagesPackage\Pages\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class SuggestionsResponse.
 */
class SuggestionsResponse implements SuggestionsResponseContract
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var string
     */
    protected $type;

    /**
     * SuggestionsResponse constructor.
     *
     * @param  Collection  $items
     * @param  string  $type
     */
    public function __construct(Collection $items, string $type = '')
    {
        $this->items = $items;
        $this->type = $type;
    }

    /**
     * Возвращаем подсказки для поля.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     * @throws BindingResolutionException
     */
    public function toResponse($request)
    {
        $transformer = app()->make(
            'InetStudio\PagesPackage\Pages\Contracts\Transformers\Back\Utility\SuggestionTransformerContract',
            [
                'type' => $this->type,
            ]
        );

        $resource = $transformer->transformCollection($this->items);

        $serializer = app()->make('InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract');

        $manager = new Manager();
        $manager->setSerializer($serializer);

        $transformation = $manager->createData($resource)->toArray();

        $data = [
            'suggestions' => [],
            'items' => [],
        ];

        if ($this->type == 'autocomplete') {
            $data['suggestions'] = $transformation;
        } else {
            $data['items'] = $transformation;
        }

        return response()->json($data);
    }
}
