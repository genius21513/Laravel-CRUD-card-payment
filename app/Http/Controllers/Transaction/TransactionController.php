<?php

namespace App\Http\Controllers\Transaction;

use App\Const\Banks;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    private Transaction $transactionModel;
    private Card $cardModel;
    private Banks $banks;

    public function __construct(Transaction $transactionModel, Card $cardModel, Banks $banks) {
        $this->transactionModel = $transactionModel;
        $this->cardModel = $cardModel;
        $this->banks = $banks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $cardNumber = $this->cardModel->getCardNumber($request->sender_card_id);
        $accountId = $this->cardModel->getCardAccountId($request->sender_card_id);

        $this->transactionModel->create($request->all() + ['sender_card_number' => $cardNumber, 'account_id' => $accountId,  ]);

        return redirect(route('account.home'));

    }

    /**
     * get bank name of given card nmber.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getBankName(Request $request)
    {
       $bankName = $this->banks->findBankName($request->query->get('cardNumber'));
       return $bankName;
    }
}
