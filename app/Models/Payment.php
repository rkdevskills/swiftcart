<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'provider', 'transaction_id', 'status', 'amount'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class);
    }   

    // ── Helpers ────────────────────────────────────────
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}