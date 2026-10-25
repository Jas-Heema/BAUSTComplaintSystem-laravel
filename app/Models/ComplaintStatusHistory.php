<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusHistory extends Model
{
    protected $fillable = ['complaint_id', 'previous_status', 'new_status', 'administrator_id', 'remark'];

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administrator_id');
    }
}
