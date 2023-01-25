<?php

namespace App\Utils;

class QS
{
    public static function pagination(array | null $qs, array $defaults = ['page' => 1, 'amount' => 50])
    {
        if (!$qs) {
            return $defaults;
        }

        return [
            'page' => $qs['page'] ?? $defaults['page'],
            'amount' => $qs['amount'] ?? $defaults['amount'],
        ];
    }
}
