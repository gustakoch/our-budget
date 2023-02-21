<?php

namespace App\Helpers;

class Helper
{
    public static function getElapsedTime($date)
    {
        $dateRegisted = strtotime($date);
        $dateNow = strtotime(date('Y-m-d h:i:sa'));

        $diff = abs($dateNow - $dateRegisted);

        $years = floor($diff / (365*60*60*24));

        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

        $days = floor(($diff - $years * 365*60*60*24 -
                    $months*30*60*60*24)/ (60*60*24));

        $hours = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24)
                                            / (60*60));

        $minutes = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                                    - $hours*60*60)/ 60);

        $seconds = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                        - $hours*60*60 - $minutes*60));

        $periodMeter = '';
        $value = 0;

        if ($years > 0) {
            $value = $years;

            if ($years == 1) {
                $periodMeter = 'ano';
            } else {
                $periodMeter = 'anos';
            }
        } elseif ($months > 0) {
            $value = $months;

            if ($months == 1) {
                $periodMeter = 'mês';
            } else {
                $periodMeter = 'meses';
            }
        } elseif ($days > 0) {
            $value = $days;

            if ($days == 1) {
                $periodMeter = 'dia';
            } else {
                $periodMeter = 'dias';
            }
        } elseif ($minutes > 0) {
            $value = $minutes;

            if ($minutes == 1) {
                $periodMeter = 'minuto';
            } else {
                $periodMeter = 'minutos';
            }
        } else {
            $value = $seconds;

            if ($seconds == 1) {
                $periodMeter = 'segundo';
            } else {
                $periodMeter = 'segundos';
            }
        }

        return "Há {$value} {$periodMeter}";
    }
}
