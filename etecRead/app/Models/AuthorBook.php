<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'bio', 
        'photo', 
        'birth_date', 
        'death_date'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    // Helper pra pegar URL completa da foto
    public function getPhotoUrlAttribute()
    {
        return $this->photo 
            ? asset('storage/' . $this->photo)
            : asset('images/default-author.png'); // foto padrão se não tiver
    }
}