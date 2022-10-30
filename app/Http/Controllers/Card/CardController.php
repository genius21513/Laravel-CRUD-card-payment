<?php

namespace App\Http\Controllers\Card;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\TransactionController;

class CardController extends Controller
{

    private Card $cardModel;

    private TransactionController $transactionController;

    public function __construct(Card $cardModel, TransactionController $transactionController) {
        $this->cardModel = $cardModel;
        $this->transactionController = $transactionController;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $cardNumber = $this->cardModel->genereteNumber();
        $this->cardModel->create($request->all() + ['card_number' => $cardNumber]);

        return redirect(route('account.home'));
    }


    /**
     * call makeOwnTransfer function form model, then redirect to home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function ownTransfer(Request  $request)
    {
        $this->cardModel->makeOwnTransfer($request->card_id, $request->amount);

        return redirect(route('account.home'));
    }

    /**
     * call functions that check if card exist and balance is enough.
     * if validation pass store new transaction and call makeTransaction method form model.
     * then redirect to home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function transfer(Request $request)
    {
        $recipientCardExist = $this->cardModel->checkIfCardExist($request->recipient_card_number);
        $balanceIsEnought = $this->cardModel->checkIsBalanceIsEnought($request->amount, $request->sender_card_id);

        if ($recipientCardExist && $balanceIsEnought) {
            $this->transactionController->store($request);
            $this->cardModel->makeTransaction($request->sender_card_id, $request->recipient_card_number, $request->amount);
        }else{
            return redirect(route('account.home'))->with('message', 'ups coś poszło nie tak :(');
        }

        return redirect(route('account.home'))->with('message', 'przelew wykonany pomyślnie');
    }
}
