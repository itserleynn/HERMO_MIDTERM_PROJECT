<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'location',
        'transport_id',
        'phone',
        'description',
        'photo',
    ];

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id', 'id');
    }
}
