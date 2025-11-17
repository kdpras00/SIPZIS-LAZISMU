<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'muzakki_id',
        'bank_name',
        'account_number',
        'account_holder',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }
}

