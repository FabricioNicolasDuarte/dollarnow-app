<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // Ya no necesitamos 'avatar' aquí
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function queries(): HasMany
    {
        return $this->hasMany(UserQuery::class);
    }

    /**
     * Devuelve SIEMPRE la URL del avatar por defecto.
     */
    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('images/default-avatar.png') // Asumimos que la imagen está en public/images
        );
    }
}

