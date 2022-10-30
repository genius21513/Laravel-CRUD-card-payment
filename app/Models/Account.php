<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * get accounts by related user id.
     *
     * @param mixed $userId.
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function getAccounts($userId)
    {
        return $this->where('user_id', '=', $userId)->get();
    }

    /**
     * event listner that delete all ralated model when account is deleting.
     *
     * @return void
     **/
    public static function boot() {
        parent::boot();
        self::deleting(function($account) {
             $account->loans()->each(function($loan) {
                $loan->delete();
             });
             $account->cards()->each(function($card) {
                $card->delete();
             });
             $account->transactions()->each(function($transactions) {
                $transactions->delete();
             });
        });
    }
}
