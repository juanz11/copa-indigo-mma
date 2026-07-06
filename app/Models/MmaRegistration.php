<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmaRegistration extends Model
{
    use HasFactory;

    protected $table = 'mma_registrations';

    protected $fillable = [
        'full_name',
        'id_number',
        'phone',
        'email',
        'social_media',
        'ticket_type',
        'quantity',
        'total_amount',
        'payment_method',
        'payment_reference',
        'payment_proof',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
