<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string first_name
 * @property string last_name
 * @property string name
 * @property Team team
 */
class Player extends Model
{
    protected $guarded = [];

    protected $appends = [
        'name',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
