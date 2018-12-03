<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    const API_KEYS = [
        'title',
        'year',
        'description',
        'image',
        'is_active',
        'deleted_at',
    ];
    protected $fillable = self::API_KEYS;

    const PAZE_SIZE = 10;
    const PAZE = 1;
    const IS_ACTIVE = '1';
    const IS_IN_ACTIVE = '0';
    const DESC = 'desc';
    const DEFAULT_COLUMN = 'id';
    public static function getMoviesList($params = []) {
        $page     = array_get($params, "page", self::PAZE);
        $pageSize = array_get($params, "page_size", self::PAZE_SIZE);
        $moviesData = self::where('deleted_at', "")
                        //->where("is_active", self::IS_ACTIVE)
                        ->orderBy(self::DEFAULT_COLUMN, self::DESC)
                        ->paginate($pageSize);
        return $moviesData;
    }

    public static function addUpdateMovies($params = [], $id = []) {
        if (empty($params)) {
            return false;
        }
        if (empty($id)) {
            $movies = new self();
        } else {
            $movies = self::find($id);
        }
        $params['deleted_at'] = array_get($params, 'deleted_at', '');
        $params['is_active'] = array_get($params, 'is_active', self::IS_ACTIVE);
        $params = array_intersect_key($params, array_flip(self::API_KEYS));
        $movies->fill($params);
        $movies->save();
        return $movies->refresh();
    }

}
