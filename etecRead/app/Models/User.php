<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $fillable = ['rm', 'email', 'name', 'password', 'role', 'status', 'photo'];

    protected $hidden = ['password', 'remember_token'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
