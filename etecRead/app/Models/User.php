<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rm',
        'role',
        'ano_escolar',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // ✅ Evento: quando deletar user, deleta relacionamentos
    protected static function booted()
    {
        static::deleting(function ($user) {
            // Devolve estoque dos empréstimos ativos
            $emprestimosAtivos = $user->loans()->where('status', 'ativo')->get();
            foreach ($emprestimosAtivos as $emprestimo) {
                $emprestimo->book->increaseStock();
            }
            
            // Deleta empréstimos e reservas
            $user->loans()->delete();
            $user->reservations()->delete();
        });
    }
}