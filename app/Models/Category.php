<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'authority_role', 'authority_label'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
