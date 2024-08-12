<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;


class Employee extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'name', 'phone', 'position', 'image', 'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Divisions::class);
    }
}
