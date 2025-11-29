<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'isbn',
        'year',
        'total_quantity',
        'available_quantity',
        'description',
        'cover_image',
    ];

    /**
     * Relacionamento com Category (N:1)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacionamento com Authors (N:N)
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book')
            ->withTimestamps();
    }

    /**
     * Relacionamento com Loans (1:N)
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relacionamento com Reservations (1:N)
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // ==================== HELPERS ====================

    /**
     * Verifica se tem estoque disponível
     */
    public function hasAvailableStock(): bool
    {
        return $this->available_quantity > 0;
    }

    /**
     * Diminui o estoque em 1 unidade
     */
    public function decreaseStock(): void
    {
        if ($this->available_quantity > 0) {
            $this->decrement('available_quantity');
        }
    }

    /**
     * Aumenta o estoque em 1 unidade
     */
    public function increaseStock(): void
    {
        if ($this->available_quantity < $this->total_quantity) {
            $this->increment('available_quantity');
        }
    }

    /**
     * Retorna URL da imagem de capa
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
    }

    /**
     * Retorna nomes dos autores em string
     */
    public function getAuthorsNamesAttribute(): string
    {
        return $this->authors->pluck('name')->join(', ') ?: 'Sem autor';
    }
}