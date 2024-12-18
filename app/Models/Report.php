<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'beer_spot_id',
        'reason',
        'description',
        'status',
        'admin_notes',
        'moderated_at',
        'moderated_by',
	'ip_address',
	'user_agent'
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_REJECTED = 'rejected';

    // Reason constants
const REASON_INAPPROPRIATE = 'inappropriate';
const REASON_SPAM = 'spam';
const REASON_OUTDATED = 'outdated';
const REASON_WRONG_LOCATION = 'wrong_location';
const REASON_INCORRECT_INFO = 'incorrect_info';
const REASON_CLOSED = 'closed';
const REASON_DUPLICATE = 'duplicate';
const REASON_OTHER = 'other';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function beerSpot(): BelongsTo
    {
        return $this->belongsTo(BeerSpot::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Accessors & Mutators
    public function getIsModeratedAttribute(): bool
    {
        return !is_null($this->moderated_at);
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getIsResolvedAttribute(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}