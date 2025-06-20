<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'package_id',
        'description',
        'image',
        'inputs',
    ];

    public function packages()
    {
        return $this->belongsTo(Package::class);
    }
}
