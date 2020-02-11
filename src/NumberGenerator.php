<?php

namespace NocturnalSm\NumberGenerator;

use DB;

class NumberGenerator {
    
    public static function generate($codeFormat, $params = Array())
    {        
        preg_match_all('/{(.*?)}/', $codeFormat, $matches);
        $patterns = Array();        
        $digits = "";
        $resultCode = $codeFormat;
        foreach ($matches[1] as $string){
            $format = "";
            if (strpos($string,"::") > 0){
                list($format,$str) = explode("::", $string);
            }
            if ($format == "d"){
                $value = $this->generateDate($str);
            }
            else if ($format == "r"){
                $value = $this->generateRandom($str);
            }
            else if ($format == "n"){               
                $digits = $str;                
            }
            else {
                if (isset($params[$string])){                
                    $value = $params[$string];
                }
            }
            if ($format != "n"){
                $resultCode = str_replace("{" .$string ."}", $value, $resultCode);
            }            
        }   
        if ($digits != ""){
            $max = DB::table("numbers")
                    ->where("prefix", $resultCode)
                    ->max("digit");
            $max = str_pad(intval($max)+1, strlen($digits), substr($digits,0,1));
            $resultCode = str_replace("{n::" .$string ."}", $max, $resultCode);
            DB::table("numbers")->insert([
                "prefix" => $resultCode,
                "digit" => $max
            ]);
        }        
        return $resultCode;
    }
    private function generateDate($string)
    {
        return Date($string);
    }
    private function generateRandom($string)
    {
        $length = strlen($string);
        return mt_rand(intval(str_repeat("9", $length - 1)),intval(str_repeat("9", $length)));
    }
}