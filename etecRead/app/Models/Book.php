<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'isbn',
        'year',
        'total_quantity',      // ✅ seu campo
        'available_quantity',  // ✅ seu campo
        'description',
        'cover_image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // Helper: verifica se tem estoque disponível
    public function hasAvailableStock(): bool
    {
        return $this->available_quantity > 0;
    }

    // Helper: diminui estoque
    public function decreaseStock(): void
    {
        if ($this->available_quantity > 0) {
            $this->decrement('available_quantity');
        }
    }

    // Helper: aumenta estoque
    public function increaseStock(): void
    {
        if ($this->available_quantity < $this->total_quantity) {
            $this->increment('available_quantity');
        }
    }
}