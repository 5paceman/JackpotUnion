<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Invite extends Model
{
    protected $guarded = [];
    private const CODE_LENGTH = 6;

    public function syndicate(): BelongsTo
    {
        return $this->belongsTo(Syndicate::class, 'syndicate_id');
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public static function generateInviteCode(): string
    {
        $code = Str::random(Invite::CODE_LENGTH);
        $invite = Invite::where('invite_code', $code)->first();
        while($invite)
        {
            $code = Str::random(Invite::CODE_LENGTH);
            $invite = Invite::where('invite_code', $code)->first();
        }

        return $code;
    }
}
