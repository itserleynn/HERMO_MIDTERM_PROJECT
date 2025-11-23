<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'description',
    ];

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
}
