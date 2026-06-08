<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'line1', 'line2', 'city',
     'postcode', 'country', 'is_default'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ── Helpers ────────────────────────────────────────
    public function fullAddress(): string
    {
        return collect([$this->line1, $this->line2, $this->city, $this->postcode, $this->country])
        ->filter()
        ->implode(', ');
    }
}
