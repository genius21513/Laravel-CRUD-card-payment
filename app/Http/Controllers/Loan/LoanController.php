<?php

namespace App\Http\Controllers\Loan;

use App\Models\Card;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LoanController extends Controller
{
    private Loan $loanModel;
    private Card $cardModel;

    public function __construct(Loan $loanModel, Card $cardModel) {
        $this->loanModel = $loanModel;
        $this->cardModel = $cardModel;
    }

    /**
     * make transactin in which loan repayment is done.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function repayment(Request $request)
    {
        $succes = false;
        DB::transaction( function () use($request, &$succes) {

            if($this->cardModel->checkIsBalanceIsEnought($request->amount, $request->card_id) && $this->loanModel->checkIfLoanIsActive($request->loan_id)){
                $this->cardModel->cardCharge($request->amount, $request->card_id);
                $this->loanModel->loanRepayment($request->amount, $request->loan_id);
                $succes = true;
            }
         });

         if(!$succes){
            return redirect(route('account.home'))->with('message', 'wystąpił błąd');
         }
         return redirect(route('account.home'))->with('message', 'spłata wykonała się pomyślnie');
    }

    /**
     * store new crated loans
     *
     * @param Request $request
     * 
     * @return type
     **/
    public function store(Request $request)
    {
        $this->loanModel->create($request->all());

        return redirect(route('account.home'));
    }
}
