<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 26.06.14
 * Time: 10:52
 */

namespace andkon\yii2actions\validators;

use yii\validators\DateValidator;

class TimeValidator extends DateValidator
{
    public $format = 'Y-m-d H:i';

    protected function validateValue($value)
    {
        $validateValue = date('Y-m-d ') . $value;

        return parent::validateValue($validateValue);
    }
}
