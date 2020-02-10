<?php

namespace NocturnalSm\NumberGenerator;

use DB;

class NumberGenerator {
    
    public static function generate($class, $params = Array())
    {
        $code = DB::table("number_codes")
                    ->where("model_type", $class)
                    ->value("format");
        preg_match_all('/{(.*?)}/', $code, $matches);
        $patterns = Array();        
        $digits = "";
        foreach ($matches[1] as $string){
            $format = "";
            if (strpos($string,":") > 0){
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
                $code = str_replace("{" .$string ."}", $value, $code);
            }            
        }   
        if ($digits != ""){
            $max = DB::table("numbers")
                    ->where("prefix", $code)
                    ->max("digit");
            $max = str_pad(intval($max)+1, strlen($digits), substr($digits,0,1));
            $code = str_replace("{n:" .$string ."}", $max, $code);
            DB::table("numbers")->insert([
                "prefix" => $code,
                "digit" => $max
            ]);
        }        
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