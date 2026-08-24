<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'region_id',
        'username',
        'phone',
        'address',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['profile_picture_url'];

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/'.$this->profile_picture);
        }

        $name = urlencode($this->name ?? 'User');

        return "https://api.dicebear.com/7.x/initials/svg?seed={$name}&backgroundColor=17692e";
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaints::class, 'user_id');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a client/customer.
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Scope for admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for client/customer users.
     */
    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}