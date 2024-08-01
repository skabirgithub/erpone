<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'branch_id',
    ];

    public function branch(){
        return $this->hasOne('App\Models\Branch','id','branch_id');
    }
}
