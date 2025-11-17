<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringDonation extends Model
{
    protected $fillable = [
        'muzakki_id',
        'program_id',
        'amount',
        'frequency',
        'start_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

