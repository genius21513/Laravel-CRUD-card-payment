<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const CREATED_AT = 'transaction_date';

    protected $fillable = [
        'amount',
        'sender_card_number',
        'recipient_card_number',
        'transaction_date',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
