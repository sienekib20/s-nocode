<?php

namespace core\support;

class Presets
{
    public static function generateTimestamp($zone)
    {
        date_default_timezone_set($zone);

        //$today = date("Y-m-d H:i:s");
        //var_dump(getdate());

        $get_date = getdate();

        $date_create_update = date("Y");
        $date_create_update .= "-";
        $date_create_update .= date("m");
        $date_create_update .= "-";
        $date_create_update .= date("d");
        $date_create_update .= " ";
        $date_create_update .= $get_date['hours'];
        $date_create_update .= ":";
        $date_create_update .= $get_date['minutes'];
        $date_create_update .= ":";
        $date_create_update .= $get_date['seconds'];

        return $date_create_update;
    }
    public static function formatNumber($valor, $decimals, $separator = '.', $thousands = ' ')
    {
        return number_format(
            $valor,
            $decimals,
            $separator,
            $thousands
        );
    }

    public static function breakArray($array, $init, $end)
    {
        return array_slice($array, $init, $end);
    }
}
