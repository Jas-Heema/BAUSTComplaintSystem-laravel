<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'reference_no', 'student_id', 'category_id', 'title', 'description',
        'incident_date', 'status', 'assigned_to', 'last_updated_at',
    ];

    protected function casts(): array
    {
        return ['incident_date' => 'date', 'last_updated_at' => 'datetime'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function history(): HasMany
    {
        return $this->hasMany(ComplaintStatusHistory::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'In Progress' => 'progress',
            'Resolved' => 'resolved',
            default => 'pending',
        };
    }
}
