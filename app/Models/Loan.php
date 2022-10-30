<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'period',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * decrement amount of loan with given id by given amount.
     *
     * @param int $var.
     * @param int $loanId.
     *
     * @return void
     **/
    public function loanRepayment($amount, $loanId)
    {
        $this->where('id', $loanId)->decrement('amount', $amount);
    }

    /**
     * check if loan is active.
     *
     * @param int $loanId.
     *
     * @return bool
     **/
    public function checkIfLoanIsActive($loanId)
    {
        $loan = $this->where('id', $loanId);
        $period = $loan->value('period');
        $endDate = $loan->value('created_at')->addMonths($period);
        $today = Carbon::today();
        $amount = $loan->value('amount');

        if ($endDate >= $today && $amount > 0) {
            return true;
        }
        else{
            return false;
        }
    }
}
