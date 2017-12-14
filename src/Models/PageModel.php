<?php

namespace InetStudio\Pages\Models;

use Spatie\Tags\HasTags;
use Cocur\Slugify\Slugify;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\Media;
use Phoenix\EloquentMeta\MetaTrait;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use InetStudio\Categories\Models\Traits\HasCategories;
use InetStudio\SimpleCounters\Traits\HasSimpleCountersTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * InetStudio\Pages\Models\PageModel.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Kalnoy\Nestedset\Collection|\InetStudio\Categories\Models\CategoryModel[] $categories
 * @property-read \Illuminate\Contracts\Routing\UrlGenerator|string $href
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\Phoenix\EloquentMeta\Meta[] $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property \Illuminate\Database\Eloquent\Collection|\InetStudio\Tags\Models\TagModel[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel findSimilarSlugs(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Pages\Models\PageModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withAllCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withAnyCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Pages\Models\PageModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withoutAnyCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Pages\Models\PageModel withoutCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Pages\Models\PageModel withoutTrashed()
 * @mixin \Eloquent
 */
class PageModel extends Model implements HasMediaConversions
{
    use HasTags;
    use MetaTrait;
    use Sluggable;
    use Searchable;
    use SoftDeletes;
    use HasCategories;
    use HasMediaTrait;
    use RevisionableTrait;
    use SluggableScopeHelpers;
    use HasSimpleCountersTrait;

    const HREF = '/page/';

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'content',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $revisionCreationsEnabled = true;

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['id', 'title', 'description', 'content']);

        $arr['categories'] = $this->categories->map(function ($item) {
            return array_only($item->toSearchableArray(), ['id', 'title']);
        })->toArray();

        $arr['tags'] = $this->tags->map(function ($item) {
            return array_only($item->toSearchableArray(), ['id', 'name']);
        })->toArray();

        return $arr;
    }

    /**
     * Возвращаем конфиг для генерации slug модели.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
            ],
        ];
    }

    /**
     * Правила для транслита.
     *
     * @param Slugify $engine
     * @return Slugify
     */
    public function customizeSlugEngine(Slugify $engine)
    {
        $rules = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'je', 'ю' => 'ju', 'я' => 'ja',
        ];

        $engine->addRules($rules);

        return $engine;
    }

    /**
     * Ссылка на страницу.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getHrefAttribute()
    {
        return url(self::HREF.(! empty($this->slug) ? $this->slug : $this->id));
    }

    /**
     * Возвращаем класс модели тега.
     *
     * @return string
     */
    public static function getTagClassName()
    {
        return TagModel::class;
    }

    /**
     * Регистрируем преобразования изображений.
     *
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $quality = (config('pages.images.quality')) ? config('pages.images.quality') : 75;

        if (config('pages.images.conversions')) {
            foreach (config('pages.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $imageConversion = $this->addMediaConversion($conversion['name']);

                        if (isset($conversion['size']['width'])) {
                            $imageConversion->width($conversion['size']['width']);
                        }

                        if (isset($conversion['size']['height'])) {
                            $imageConversion->height($conversion['size']['height']);
                        }

                        if (isset($conversion['fit']['width']) && isset($conversion['fit']['height'])) {
                            $imageConversion->fit('max', $conversion['fit']['width'], $conversion['fit']['height']);
                        }

                        if (isset($conversion['quality'])) {
                            $imageConversion->quality($conversion['quality']);
                            $imageConversion->optimize();
                        } else {
                            $imageConversion->quality($quality);
                        }

                        $imageConversion->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
