<?php
    namespace App\Helpers;

    class ArrayHelper {

        public function __construct() {

        }

        public static function diffRecursive($array1, $array2) {
            $difference = [];
            foreach($array1 as $key => $value) {
                if(is_array($value) && isset($array2[$key])){ // it's an array and both have the key
                    $new_diff = self::diffRecursive($value, $array2[$key]);
                    if( !empty($new_diff) )
                        $difference[$key] = $new_diff;
                } else if(is_string($value) && !in_array($value, $array2)) { // the value is a string and it's not in array B
                    $difference[$key] = $value . " is missing from the second array";
                } else if(!is_numeric($key) && !array_key_exists($key, $array2)) { // the key is not numberic and is missing from array B
                    $difference[$key] = "Missing from the second array";
                }
            }

            foreach($array2 as $key => $value) {
                if(is_array($value) && isset($array1[$key])){ // it's an array and both have the key
                    $new_diff = self::diffRecursive($value, $array1[$key]);
                    if( !empty($new_diff) )
                        $difference[$key] = $new_diff;
                } else if(is_string($value) && !in_array($value, $array1)) { // the value is a string and it's not in array B
                    $difference[$key] = $value . " is missing from the first array";
                } else if(!is_numeric($key) && !array_key_exists($key, $array1)) { // the key is not numberic and is missing from array B
                    $difference[$key] = "Missing from the first array";
                }
            }

            return $difference;
        }

        public static function jsonFormate($json, $decode = false, $toAssociate = false) {
            if(!$json) {
                return $json;
            }

            if (!$decode) {
                return json_encode($json);
            }
            if(!is_array($json)) {
                return $json;
            }

            return json_decode($json, $toAssociate);
        }

        public static function toArray(object $obj) {
            return $obj->toArray();
        }

        public static function ArrayToStr(array $params, $separator) {
            return implode($separator, $params);
        }

        public static function StrToArray($inputString, $separator) {
            return explode($separator, $inputString);
        }

    /**
     * Search value by key.
     *
     * @params $array [0 => ["id"=>1,"name"=>"cat 1"],
                            1 => ["id"=>2,"name"=>"cat 2"],
                            2 => ["id"=>3,"name"=>"cat 1"]]
     * @params $key i.e. "name"
     * @params $value i.e. "cat 1"
     * @author Chigs Patel <info@webnappdev.in>
     * @Date Dec 17 2018
     */
        public static function search($array, $key, $value) {
            $results = [];
            if (is_array($array))
            {
                if (isset($array[$key]) && $array[$key] == $value)
                    $results[] = $array;

                foreach ($array as $subarray)
                    $results = array_merge($results, search($subarray, $key, $value));
            }

            return $results;
        }

    }
?>