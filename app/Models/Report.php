<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'details',
        'status',
        'user_id',
        'store_id'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function stores(){
        return $this->belongsTo(Store::class);
    }
}
