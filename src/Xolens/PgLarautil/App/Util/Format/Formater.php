<?php

namespace Xolens\PgLarautil\App\Util\Format;

class Formater 
{
    public static function formatPhone($phone){
        $fPhone = $phone;
        if($phone!=null){
            $fPhone = preg_replace('/\s+/', '', $fPhone);
            preg_match('/(?<indic>(\(\+\w+\))?)(?<start>\w?\w?)(?<phone>(\w{3})+)$/', $fPhone, $parts);
            if(count($parts)>0){
                $fPhone = $parts['indic'].' '.$parts['start'].' '.chunk_split($parts['phone'], 3, ' ');
                $fPhone = preg_replace('/\s{2}/', ' ', $fPhone);
                $fPhone = preg_replace('/(^\s)|(\s$)/', '', $fPhone);
            }
        }
        return $fPhone;
    }
}
