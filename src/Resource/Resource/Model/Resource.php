<?php

    namespace Resource\Model;

    use \Illuminate\Database\Eloquent\Model;
    
class Resource extends Model
{
    protected $table = 'object';
    protected $fillable = [
        'ordinal_position_long',
        'ordinal_position_short',
    ];
}
