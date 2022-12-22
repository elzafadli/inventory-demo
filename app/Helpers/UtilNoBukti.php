<?php

namespace App\Helpers;

use App\Models\Setting\Nota;
use Illuminate\Support\Facades\DB;
use Session;

class UtilNoBukti
{

    public static function generate($prefix)
    {
        $month = date('n');
        $year = date('y');
        $number = 1;

        // get lastest nota counter
        $nota = Nota::where([
            'year' => $year,
            'month' => $month,
            'prefix' => $prefix
        ])->first();

        if ($nota) {
            $nota->prefix = $prefix;
            $nota->month = $month;
            $nota->year = $year;
            $nota->counter += 1;

            $nota->save();

            $number = $nota->counter;
        } else {
            $nota = new Nota();
            $nota->prefix = $prefix;
            $nota->month = $month;
            $nota->year = $year;
            $nota->counter = 1;

            $nota->save();
        }

        $autonumber = '';
        if (strlen($number) <= 4) {
            for ($i = 0; $i < (4 - strlen($number)); $i++) {
                $autonumber .= '0';
            }
        }

        $number = $autonumber . $number;


        return $prefix . '/' . date('my') . '/' . $number;
    }
}
