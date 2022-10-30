<?php

namespace App\Const;
class Banks
{
    const BANKS_IDENTYFICATORS = [
        '1010' => 'Narodowy Bank Polski',
        '1020' => 'PKO BP',
        '1030' => 'Bank Handlowy',
        '1050' => 'ING Bank Śląski',
        '1090' => 'Santander Bank Polska',
        '1130' => 'BGK',
        '1140' => 'mBank',
        '1160' => 'Bank Millennium',
        '1320' => 'Pekao SA',
        '1320' => 'Bank Pocztowy',
        '1540' => 'Mercedes-Benz Bank Polska',
        '1610' => 'SGB - Bank',
        '1670' => 'RBS Bank',
        '1680' => 'Plus Bank',
        '1840' => 'Societe Generale',
    ];

    /**
     * Check bank name by given card number
     *
     * @param mixed $cardNumber card number
     * @return string
     **/
    public function findBankName($cardNumber)
    {
        $identyficator = substr($cardNumber, 0, 4);
        if(array_key_exists($identyficator, $this::BANKS_IDENTYFICATORS)){
            $bankName = $this::BANKS_IDENTYFICATORS[$identyficator];
        }
        else{
            $bankName = 'brak banku w bazie';
        }
        return  $bankName;
    }
}

