<?php

namespace App\filters;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class PostFilter
{
    public function filter(){
        return [
                      'content',
                      'price',
                      'worker.name',
    AllowedFilter::callback('item', function (Builder $query, $value) {
        $query->where('price', 'like', "%{$value}%");
        $query->orwhere('content', 'like', "%{$value}%");
        $query->orWhereHas('worker',function($query) use($value){
        $query->where('name','like',"%{$value}%");
        });
    }),];
    }
}
