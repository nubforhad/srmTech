<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpAccessRule extends Model
{
    protected $fillable = [
        'name',
        'title',
        'subtitle',
        'paragraph',
        'details',
        'ip_address',
        'category_ids',
        'service_ids',
        'remark',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'category_ids' => 'array',
        'service_ids' => 'array',
        'status' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        if (!$this->status) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }
}

