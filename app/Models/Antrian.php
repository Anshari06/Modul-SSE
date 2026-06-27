<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'nama',
        'no_hp',
        'layanan',
        'status',
    ];

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting')->orderBy('id', 'asc');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }
}