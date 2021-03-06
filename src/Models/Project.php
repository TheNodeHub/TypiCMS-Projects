<?php

namespace TypiCMS\Modules\Projects\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;

class Project extends Base
{
    use Historable;
    use Translatable;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Projects\Presenters\ModulePresenter';

    protected $dates = ['date'];

    protected $fillable = [
        'category_id',
        'image',
        'date',
        'website',
        // Translatable columns
        'title',
        'slug',
        'status',
        'summary',
        'body',
    ];

    /**
     * Translatable model configs.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'slug',
        'status',
        'summary',
        'body',
    ];

    protected $appends = ['status', 'title', 'thumb', 'category_name'];

    /**
     * Columns that are file.
     *
     * @var array
     */
    public $attachments = [
        'image',
    ];

    /**
     * Get public uri.
     *
     * @return string|null
     */
    public function uri($locale = null)
    {
        $locale = $locale ?: config('app.locale');
        $page = TypiCMS::getPageLinkedToModule($this->getTable());
        if ($page) {
            return $page->uri($locale).'/'.$this->category->translate($locale)->slug.'/'.$this->translate($locale)->slug;
        }

        return '/';
    }

    /**
     * A project belongs to a category.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('TypiCMS\Modules\Categories\Models\Category');
    }

    /**
     * A project has many galleries.
     *
     * @return MorphToMany
     */
    public function galleries()
    {
        return $this->morphToMany('TypiCMS\Modules\Galleries\Models\Gallery', 'galleryable')
            ->withPivot('position')
            ->orderBy('position')
            ->withTimestamps();
    }

    /**
     * Get name of the category from category table
     * and append it to main model attributes.
     *
     * @return string title
     */
    public function getCategoryNameAttribute()
    {
        if (isset($this->category) and $this->category) {
            return $this->category->title;
        }

        return;
    }
}
