<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\NewUserMailable;
use App\Notifications\InviteNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
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

    protected static function booted(): void
    {
        static::created(function (User $user) {
            Mail::to($user->email)->send(new NewUserMailable($user));
        });
    }

    public function syndicates(): BelongsToMany
    {
        return $this->belongsToMany(Syndicate::class);
    }

    public function newUserCheckInvites(): void
    {
        $invites = Invite::where('email', $this->email)->get();
        foreach($invites as $invite)
        {
            $this->notify(new InviteNotification($invite, true));
        }
    }

    public function name(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
