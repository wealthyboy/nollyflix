<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Helper;
use App\Traits\FormatPrice;
use Carbon\Carbon;
use App\Traits\ColumnFillable;
use Stevebauman\Location\Location;




class Video extends Model
{

    use FormatPrice, ColumnFillable; //,SoftDeletes,CascadeSoftDeletes;

    public $appends = [
        'url',
        'currency',
        'converted_buy_price',
        'converted_rent_price',
        'iso_code',
        'year_release',
        'region_blocked',
        'region_available'
    ];

    protected $dates = [
        'release_date',
    ];

    protected $casts = [
        'blocked_continents' => 'array',
    ];

    public function setBlockedContinentsAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        $value = array_values(array_filter((array) $value));
        $this->attributes['blocked_continents'] = $value ? json_encode($value) : null;
    }

    /**
     * The casts that belong to the user.
     */
    public function sections()
    {
        return $this->belongsToMany('App\Section', 'section_video');
    }

    /**
     * The categories that belong to the user.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * The video in Cart.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * The video in Cart.
     */
    public function isVideoRentExpired()
    {
        return optional($this->cart)->purchase_type == 'rent' &&
            optional($this->cart)->created_at <= Carbon::now()->addDays(2) ? true : false;
    }


    /**
     * The sold that belong to the user.
     */
    public function solds()
    {
        return $this->hasMAny('App\Cart')->where([
            'purchase_type' => 'buy',
            'status' => 'Complete',
            'content_owner_id' => optional(auth()->user())->id
        ]);
    }


    /**
     * The sold that belong to the user.
     */
    public function rents()
    {
        return $this->hasMAny('App\Cart')->where([
            'purchase_type' => 'rent',
            'status' => 'Complete',
            'content_owner_id' => optional(auth()->user())->id
        ]);
    }


    /**
     * The filmers that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_video');
    }


    /**
     * The filmers that belong to the user.
     */
    public function filmers()
    {
        return $this->belongsToMany('App\User', 'filmer_video', 'video_id', 'user_id');
    }


    /**
     * The casts that belong to the user.
     */
    public function casts()
    {
        return $this->belongsToMany('App\User', 'cast_video', 'video_id', 'user_id');
    }

    /**
     * The filmers that belong to the user.
     */
    public function default_banner()
    {
        return $this->hasOne(DefaultBanner::class);
    }

    /**
     * The filmers that belong to the user.
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }


    public function getUrlAttribute()
    {
        $link  = '/watch/';
        $link .= $this->slug;
        return $link;
    }

    /**
     * The videos bought that belong to the video.
     */
    public function movies()
    {
        return $this->hasMany('App\OrderedMovie');
    }


    /**
     * The videos bought that belong to the video.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }


    /**
     * The video's views that belong to the video.
     */
    public function views()
    {
        return $this->hasMany('App\View');
    }


    /**
     * The video's views that belong to the video.
     */
    public function view()
    {
        return $this->hasOne('App\View');
    }

    public function episodes()
    {
        return $this->hasMany('App\VideoEpisode')
            ->orderBy('season_number')
            ->orderBy('episode_number')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function isSeries()
    {
        return $this->content_type === 'series';
    }

    public function related_videos()
    {
        return $this->hasMany(RelatedVideo::class);
    }

    public function getReversedDate()
    {
        return Helper::getFormatBack($this->release_date);
    }


    public function allGenres()
    {
        $genres = '';
        $x = 1;
        foreach ($this->genres as $index =>  $genre) {
            $genres .= "<a href='/browse/genre/$genre->slug'>$genre->name</a>";
            if ($x < count($this->genres)) {
                $genres .= ' | ';
                $x++;
            }
        }
        return $genres;
    }


    public function watchType()
    {

        if ($this->is_free) {
            return '<a href=""></a>';
        }

        if ($this->is_for_rent_and_buy) {
            return '<a href=""></a>';
        }

        if ($this->is_only_for_rent) {
            return '<a href=""></a>';
        }

        if ($this->is_only_for_buy) {
            return '<a href=""></a>';
        }

        return $genres;
    }


    public function allActors()
    {
        $casts = '';
        $x = 1;
        foreach ($this->casts as $index =>  $cast) {
            $casts .= "<a href='/$cast->username'>$cast->name</a>";
            if ($x < count($this->casts)) {
                $casts .= ' | ';
                $x++;
            }
        }
        return $this->casts;
    }



    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function getYearReleaseAttribute()
    {
        return null !== $this->release_date ?  $this->release_date->format('Y') : null;
    }


    public static function excludes()
    {
        return $map = [
            'AF' => 'Africa',
            'EU' => 'Europe',
            'AS' => 'Asia',
            'NA' => 'North America',
            'SA' => 'South America',
            'OC' => 'Oceania',
            'AN' => 'Antarctica',
        ];
    }

    public static function countryToContinent()
    {
        $map = config('continents.country_to_continent', []);
        $legacyPath = storage_path('app/continents.json');

        if (file_exists($legacyPath)) {
            $legacyMap = json_decode(file_get_contents($legacyPath), true);

            if (is_array($legacyMap)) {
                $map = array_merge($map, $legacyMap);
            }
        }

        return $map;
    }

    public static function detectContinentCode()
    {
        $request = request();

        if ($request->attributes->has('continent_code')) {
            return $request->attributes->get('continent_code');
        }

        $continentCode = null;

        try {
            $position = (new Location())->get($request->ip());
            $countryCode = strtoupper((string) optional($position)->countryCode);

            if ($countryCode) {
                $continentCode = static::countryToContinent()[$countryCode] ?? null;
            }
        } catch (\Throwable $exception) {
            // If geo lookup is unavailable, keep the catalogue available instead
            // of accidentally blocking every title.
        }

        $request->attributes->set('continent_code', $continentCode);

        return $continentCode;
    }

    public function scopeVisibleInCurrentRegion(Builder $query, $continentCode = null)
    {
        $continentCode = $continentCode ?: static::detectContinentCode();

        if (!$continentCode) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($continentCode) {
            $query->whereNull('videos.blocked_continents')
                ->orWhereJsonDoesntContain('videos.blocked_continents', $continentCode);
        });
    }

    public function isBlockedInCurrentRegion($continentCode = null)
    {
        $continentCode = $continentCode ?: static::detectContinentCode();

        if (!$continentCode) {
            return false;
        }

        return in_array($continentCode, $this->blocked_continents ?: [], true);
    }

    public function getRegionBlockedAttribute()
    {
        return $this->isBlockedInCurrentRegion();
    }

    public function getRegionAvailableAttribute()
    {
        return !$this->isBlockedInCurrentRegion();
    }

    /**
     * Preserve Vimeo's signed progressive URL exactly as supplied.
     *
     * The signature can cover the complete path, including an encoded
     * download filename such as `file.mp4%20%281080p%29.mp4`. Rewriting that
     * path invalidates otherwise playable links.
     */
    public static function normalizePreviewLink($url)
    {
        return trim((string) $url);
    }

    public function playablePreviewLink()
    {
        return static::normalizePreviewLink($this->preview_link);
    }
}
