<?php
namespace DirectCall;

/**
 * Class Helper
 * @package DirectCall
 * @author Renato Neto
 */
class Helper
{

    public static function validateNumber($number)
    {
        return (bool)preg_match('@^[0-9]{12,13}$@', $number);
    }

    public static function checkFormat($date, $format = 'd-m-Y-H-i-s')
    {
        $date = \DateTime::createFromFormat($format, $date);
        return ($date) ? $date->format($format) : false;
    }

}