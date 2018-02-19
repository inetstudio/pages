<?php

namespace InetStudio\Pages\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use InetStudio\Pages\Models\PageModel;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Pages\Contracts\Http\Controllers\Back\PagesUtilityControllerContract;

/**
 * Class PagesUtilityController.
 */
class PagesUtilityController extends Controller implements PagesUtilityControllerContract
{
    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlug(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $slug = ($name) ? SlugService::createSlug(PageModel::class, 'slug', $name) : '';

        return response()->json($slug);
    }

    /**
     * Возвращаем страницы для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $search = $request->get('q');

        $items = PageModel::select(['id', 'title', 'slug'])->where('title', 'LIKE', '%'.$search.'%')->get();

        if ($request->filled('type') && $request->get('type') == 'autocomplete') {
            $type = get_class(new PageModel());

            $data = $items->mapToGroups(function ($item) use ($type) {
                return [
                    'suggestions' => [
                        'value' => $item->title,
                        'data' => [
                            'id' => $item->id,
                            'type' => $type,
                            'title' => $item->title,
                            'path' => parse_url($item->href, PHP_URL_PATH),
                            'href' => $item->href,
                        ],
                    ],
                ];
            });
        } else {
            $data = $items->mapToGroups(function ($item) {
                return [
                    'items' => [
                        'id' => $item->id,
                        'name' => $item->title,
                    ],
                ];
            });
        }

        return response()->json($data);
    }
}
