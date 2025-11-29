<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio', 'birth_date', 'death_date'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
