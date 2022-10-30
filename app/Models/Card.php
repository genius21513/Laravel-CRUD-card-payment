<?php

namespace App\Models;

use App\Const\Banks;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'balnace',
        'type',
        'account_id',
    ];

    public function account()
    {
        return $this->belognsTo(Account::class);
    }

    /**
     * get card number by this card id.
     *
     * @param mixed $cardId card id
     *
     * @return int
     **/
    public function getCardNumber($cardId)
    {
        return $this->where('id', $cardId)->value('card_number');
    }

    /**
     * get card.
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function getCardById($cardId)
    {
        return $this->where('id', $cardId)->get();
    }

    /**
     * get card by card number.
     *
     * @param int $cardNumber
     *
     **/
    public function getCardByNumber($cardNumber)
    {
        return $this->where('card_number', $cardNumber)->get();
    }
    /**
     * get account id that is related with card by card id.
     *
     * @param mixed $cardId card id
     *
     * @return int
     **/
    public function getCardAccountId($cardId)
    {
        return $this->where('id', $cardId)->value('account_id');
    }

    /**
     * generete unique 8 digits card number and check if it exist ind database.
     *
     *
     * @return int
     **/
    public function genereteNumber()
    {
        do{
            $bankIdentyficator = array_rand(Banks::BANKS_IDENTYFICATORS);
            $cardNumber = $bankIdentyficator . rand(1000, 9999);

            if($this->where('card_number', $cardNumber)->exists()){
                $cardNumberExists = true;
            }
            else{
                $cardNumberExists = false;
            }
        }
        while($cardNumberExists);

        return $cardNumber;
    }

    /**
     * check if card with given number exist.
     *
     * @param mixed $cardNumber.
     *
     * @return bool
     **/
    public function checkIfCardExist($cardNumber)
    {
        if ($this->where('card_number', '=', $cardNumber)->exists()) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * check if balance on card is eqaul or greater then given amount.
     *
     * @param int $amount.
     * @param int $cardId.
     *
     * @return bool
     **/
    public function checkIsBalanceIsEnought($amount, $cardId)
    {
        if($this->getCardType($cardId) == 'credit')
        {
            return true;
        }
        else{
            $cardBalance = $this->where('id', '=', $cardId)->value('balance');

            if($cardBalance >= $amount){
                return true;
            }else{
                return false;
            }
        }

    }

    /**
     * make transaction between two cards.
     *
     * take reciptient card number and sender card id, then increment balance on recipient card and decrement balance on sender card.
     *
     * @param int $senderCardId.
     * @param int $recipientCardNumber.
     * @param int $amount.
     * @return void
     **/
    public function makeTransaction($senderCardId, $recipientCardNumber, $amount)
    {
        DB::transaction( function () use($senderCardId, $recipientCardNumber, $amount) {
            $this->cardCharge($amount, $senderCardId);
            $this->where('card_number', $recipientCardNumber)->increment('balance', $amount);
         });
    }

    /**
     * increment blanace in card whtih given card number by given amount.
     *
     * @param int $cardId
     * @param int $amount
     *
     * @return void
     **/
    public function makeOwnTransfer($cardId,$amount)
    {
        $this->where('id', '=', $cardId)->increment('balance', $amount);
    }

    /**
     * return card type of card with given id.
     *
     * @param int $cardId.
     * @return string
     **/
    public function getCardType($cardId)
    {
        return($this->where('id',$cardId)->value('type'));

    }

    /**
     * charging card by given amount.
     *
     * @param int $amount.
     * @param int $cardId.
     *
     * @return void
     **/
    public function cardCharge($amount, $cardId)
    {
        if($this->getCardType($cardId) == 'debit'){
            if($this->checkIsBalanceIsEnought($amount,$cardId)){
                $this->where('id', $cardId)->decrement('balance', $amount);
            }
        } else{
            $this->where('id', $cardId)->decrement('balance', $amount);
        }
    }
}
