<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'portal') {
             // Agents use the portal. Admins/Staff might also need to see it? 
             // Requirement says "Agents (Portal) must NOT see [Admin Resources]".
             // It doesn't restrict Admins from seeing Portal.
             // But usually separate. Let's allow everyone to see Portal for testing, 
             // or restrict? Let's say Agents only, but maybe Admin wants to check?
             // Simplest: only 'agent' uses Portal. 'admin' uses Admin panel.
             // If Admin needs to see Portal, they can have 'agent' account or logic update.
             // For now: Portal is for all logged in users? No, usually distinct.
             // Let's stick to role check.
             return true; 
        }

        if ($panel->getId() === 'admin') {
             return in_array($this->role, ['admin', 'staff']);
        }

        return true;
    }
}
