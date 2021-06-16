<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'queues';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ac_id',
        'priority'
    ];

    public function aircraft(){
        return $this->belongsTo('\App\Models\Aircraft', 'ac_id');
    }
}
