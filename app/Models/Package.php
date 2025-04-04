<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image'
    ];


    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function stacks()
    {
        return $this->hasMany(Stack::class);
    }
}
