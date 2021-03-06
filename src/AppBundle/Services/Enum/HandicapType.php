<?php

namespace AppBundle\Services\Enum;

class HandicapType
{
    const INITIAL = 'initial';
    const UPDATE = 'update';
    const REASSESS = 'reassess';

    public static $choices = [
        self::INITIAL => 'Initial',
        self::UPDATE => 'Update',
        self::REASSESS => 'Reassessment',
    ];
}
