<?php

namespace NocturnalSm\Generator;

use DB;

class CodeNumber {
  
    public function generate()
    {        
        preg_match_all('/{(.*?)}/', $this->codeFormat, $matches);
        $patterns = Array();        
        $increments = "";
        $resultCode = $codeFormat;        
        foreach ($matches[1] as $string){
            $format = "";
            if (strpos($string,":") > 0){
                list($format, $str) = explode(":", $string);
            }
            if ($format == "d"){ // date format
                $value = $this->generateDate($str);
            }
            else if ($format == "r"){ // random format
                $value = $this->generateRandom($str);
            }
            else if ($format == "i"){ // increments
                $increments = $str;                
            }
            else {
                if (isset($params[$string])){                
                    $value = $params[$string];
                }
            }                             
            if ($format != "" && $format != "i"){                
                $resultCode = str_replace("{" .$string ."}", $value, $resultCode);
            }            
        }           
        if ($increments != ""){
            $max = DB::table("numbers")
                    ->where("prefix", $resultCode)
                    ->max("increments");
            if (!$max){
                $max = 0;
            }            
            $max = str_pad(intval($max)+1, strlen($increments), substr($increments,0,1), STR_PAD_LEFT);                        
            DB::table("numbers")->insert([
                "prefix" => $resultCode,
                "increments" => $max
            ]);            
            $resultCode = str_replace("{i:" .$increments ."}", $max, $resultCode);                                    
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