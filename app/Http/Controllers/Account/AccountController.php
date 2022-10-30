<?php

namespace App\Http\Controllers\Account;

use App\Models\Card;
use App\Models\Loan;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    private Account $accountModel;
    private Card $cardModel;
    private Loan $loanModel;
    private Transaction $transactionModel;

    public function __construct(Account $accountModel, Card $cardModel, Loan $loanModel, Transaction $transactionModel) {
        $this->accountModel = $accountModel;
        $this->cardModel = $cardModel;
        $this->loanModel = $loanModel;
        $this->transactionModel = $transactionModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $accounts = $this->accountModel->getAccounts(Auth::id());

        foreach ($accounts as $account){
            $account['loans'] = $this->loanModel->where('account_id', '=', $account->id)->get();
            $account['cards'] = $this->cardModel->where('account_id', '=', $account->id)->get();
            $account['transactions'] = $this->transactionModel->where('account_id', '=', $account->id)->get();
        }

        return view('account.home', [
            'accounts' => $accounts,
            'userId' => Auth::id()
        ]);
    }

    /**
     * return view for store new .
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('account.create', [
            'userId' => Auth::id()
        ]);
    }

    /**
     * store account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->accountModel->create($request->all());

        return redirect(route('account.home'));
    }

    /**
     * delete account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $this->accountModel->where('id',$request->id)->first()->delete();

        return redirect(route('account.home'));
    }
}
