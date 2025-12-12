<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\BelongsTo;
use App\Models\Country;

class SubscribePartner extends Model
{
    use HasFactory;

    protected $table = 'subscribepartners';

    public function country()
    {
        //return $this->belongsTo(blogType::class);
        return $this->belongsTo(Country::class);
    }
}
