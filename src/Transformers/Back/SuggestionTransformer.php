<?php

namespace InetStudio\Pages\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Pages\Contracts\Models\PageModelContract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\Pages\Contracts\Transformers\Back\SuggestionTransformerContract;

/**
 * Class SuggestionTransformer.
 */
class SuggestionTransformer extends TransformerAbstract implements SuggestionTransformerContract
{
    /**
     * @var string
     */
    private $type;

    /**
     * PagesSiteMapTransformer constructor.
     *
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Подготовка данных для отображения в выпадающих списках.
     *
     * @param PageModelContract $page
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PageModelContract $page): array
    {
        if ($this->type && $this->type == 'autocomplete') {
            $modelClass = get_class($page);

            return [
                'value' => $page->title,
                'data' => [
                    'id' => $page->id,
                    'type' => $modelClass,
                    'title' => $page->title,
                    'path' => parse_url($page->href, PHP_URL_PATH),
                    'href' => $page->href,
                ],
            ];
        } else {
            return [
                'id' => $page->id,
                'name' => $page->title,
            ];
        }
    }

    /**
     * Обработка коллекции страниц.
     *
     * @param $pages
     *
     * @return FractalCollection
     */
    public function transformCollection($pages): FractalCollection
    {
        return new FractalCollection($pages, $this);
    }
}
