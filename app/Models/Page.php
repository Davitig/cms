<?php

namespace Models;

use Models\Abstracts\Model;
use Models\Traits\FileableTrait;
use Models\Traits\LanguageTrait;
use Models\Traits\NestableTrait;
use Models\Traits\PositionableTrait;

class Page extends Model
{
    use LanguageTrait, PositionableTrait, FileableTrait, NestableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id', 'slug', 'position', 'visible', 'parent_id', 'type', 'type_id', 'template', 'collapse', 'image'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected $notUpdatable = [];

    /**
     * Related database table name used by the Language model.
     *
     * @var string
     */
    protected $languageTable = 'page_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $languageFillable = [
        'page_id', 'language', 'title', 'short_title', 'description', 'content', 'meta_title', 'meta_desc'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected $languageNotUpdatable = [
        'page_id', 'language'
    ];

    /**
     * Build an admin query.
     *
     * @param  int  $menuId
     * @return \Models\Builder\Builder
     */
    public function forAdmin($menuId = null, $currentLang = true)
    {
        return $this->when(! is_null($menuId), function ($q) use ($menuId) {
            return $q->menuId($menuId);
        })->joinLanguage($currentLang)
            ->joinCollection()
            ->hasFile()
            ->positionAsc();
    }

    /**
     * Build a public query.
     *
     * @param  mixed  $currentLang
     * @return \Models\Builder\Builder
     */
    public function forPublic($currentLang = true)
    {
        return $this->joinLanguage($currentLang)->whereVisible();
    }

    /**
     * Determine if the model has a sub page.
     *
     * @return bool
     */
    public function hasSubPage()
    {
        return $this->parentId($this->getKey())->exists();
    }

    /**
     * Determine if the model has a parent page.
     *
     * @return bool
     */
    public function hasSiblingPage()
    {
        if (! $this->parent_id) {
            return false;
        }

        return $this->parentId($this->parent_id)->exists();
    }

    /**
     * Add a query, which is valid for routing.
     *
     * @param  string  $slug
     * @param  int     $parentId
     * @return \Models\Builder\Builder
     */
    public function bySlugRoute($slug, $parentId)
    {
        return $this->parentId($parentId)->where('slug', $slug)->forPublic();
    }

    /**
     * Add a where "menu_id" clause to the query.
     *
     * @param  int  $id
     * @return \Models\Builder\Builder
     */
    public function menuId($id)
    {
        return $this->where('menu_id', $id);
    }

    /**
     * Add a where "type_id" clause to the query.
     *
     * @param  int     $id
     * @param  string  $operator
     * @return \Models\Builder\Builder
     */
    public function typeId($id, $operator = '=')
    {
        return $this->where('type_id', $operator, $id);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \Models\Builder\Builder
     */
    public function whereVisible($value = 1)
    {
        return $this->where('visible', $value);
    }

    /**
     * Add a "collection" join to the query.
     *
     * @return \Models\Builder\Builder
     */
    public function joinCollection()
    {
        $table = (new Collection)->getTable();

        $columns = [
            $table . '.title as collection_title',
            $table . '.type as collection_type',
        ];

        return $this->leftJoin($table, function ($q) use ($table) {
            return $q->where($this->getTable() . '.type', 'collections')
                ->on('type_id', $table . '.id');
        })->addSelect($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            if (isset($attributes['menu_id'])) {
                $attributes['position'] = $this->menuId($attributes['menu_id'])
                        ->max('position') + 1;
            } else {
                $attributes['position'] = $this->max('position') + 1;
            }
        }

        return parent::create($attributes);
    }
}
