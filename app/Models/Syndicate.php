<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Syndicate extends Model
{
    protected $guarded = [];
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(LotteryTicket::class);
    }

    public function drawnTickets(): HasMany
    {
        return $this->hasMany(LotteryTicket::class)->where('drawn', true);
    }

    public function undrawnTickets(): HasMany
    {
        return $this->hasMany(LotteryTicket::class)->where('drawn', false);
    }

    public function isOwner(): bool
    {
        return $this->owner_id == auth()->user()->id;
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function canEdit(int $userId): bool
    {
        return $this->owner_id == $userId;
    }

    public function notificationUsers(): array
    {
        return array($this->owner);
    }

    public function settings(): HasOne
    {
        $settings = $this->hasOne(SyndicateSetting::class);

        if(!$settings->exists())
        {
            $newSettings = SyndicateSetting::create([
                'syndicate_id' => $this->id
            ]);

            return $this->hasOne(SyndicateSetting::class);
        }

        return $settings;
    }

}
