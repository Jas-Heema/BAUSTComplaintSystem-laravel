<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintCategory extends Model
{
    protected $fillable = ['name', 'description', 'assigned_role', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

}
