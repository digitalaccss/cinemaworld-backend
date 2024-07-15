<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $casts = [
        'schedule_start_date' => 'date:d M Y',
        // 'schedule_start_time' => 'datetime:g:i A',
        'schedule_end_date' => 'date:d M Y',
        // 'schedule_end_time' => 'datetime:g:i A'
    ];

    protected $fillable = ['show_id',
        'schedule_start_date',
        'schedule_end_date',
        'schedule_start_time',
        'schedule_end_time',
        'instalment_id'
    ];

    // each schedule can have 1 show
    public function show(){
        return $this->belongsTo(Show::class);
    }

    // each schedule can have 1 instalment
    public function instalment(){
        return $this->belongsTo(Instalment::class);
    }
}
