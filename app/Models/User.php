<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'master_id');
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'recipient_id');
    }

    public function campaignInvites()
    {
        return $this->hasMany(CampaignInvite::class, 'user_id');
    }

    /**
     * Public URL for the uploaded avatar, or null when the user has none.
     * Resolved on whichever disk is configured (see config/filesystems.php).
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar_path ? Storage::disk(config('filesystems.default'))->url($this->avatar_path) : null;
    }

    /**
     * Up to two uppercase initials derived from the user's name, used as the
     * avatar fallback wherever no photo has been uploaded.
     */
    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
