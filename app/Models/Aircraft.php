<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'acs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'size'
    ];
}
