<?php

namespace App\Models;

use Database\Factories\RecipeTipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeTip extends Model
{
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    protected $fillable = [
        'tip',
    ];

    /** @use HasFactory<RecipeTipFactory> */
    use HasFactory;
}
