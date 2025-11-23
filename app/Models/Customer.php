<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'location',
        'phone',
        'description'
    ];

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id', 'id');
    }
}
