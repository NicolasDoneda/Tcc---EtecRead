<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'category_id',
        'year',
        'total_quantity',
        'available_quantity',
        'cover_image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function hasAvailableStock()
    {
        return $this->available_quantity > 0;
    }

    public function decreaseStock()
    {
        $this->decrement('available_quantity');
    }

    public function increaseStock()
    {
        $this->increment('available_quantity');
    }
}