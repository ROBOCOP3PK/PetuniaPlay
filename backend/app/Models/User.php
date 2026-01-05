<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'document',
        'role',
        'birth_date',
        'avatar',
        'is_active',
        'email_notifications',
        'email_notifications_enabled',
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
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'email_notifications' => 'boolean',
            'email_notifications_enabled' => 'boolean',
        ];
    }

    // Relaciones

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function loyaltyRedemptions()
    {
        return $this->hasMany(LoyaltyRedemption::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Helpers

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function hasManagerAccess()
    {
        return in_array($this->role, ['manager', 'admin']);
    }
}
