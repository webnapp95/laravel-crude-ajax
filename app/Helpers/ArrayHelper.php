<?php
    namespace App\Helpers;

    class ArrayHelper {

        public function __construct() {

        }

        public static function diffRecursive($array1, $array2) {
            $difference=array();
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
            return $difference;
        }

        public static function jsonFormate($json, $encode = false, $toAssociate = false) {
            if(!$json) {
                return $json;
            }

            if ($encode) {
                return json_encode($json);
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

        public static function recursiveSearch(array $array, $search, $mode = 'value', $return = false) {
            foreach (new RecursiveIteratorIterator(new recursiveArrayIterator($array)) as $key => $value) {
                if($search === ${${"mode"}}) {
                    return $return ? ${${"mode"}} : true;
                }
            }
        }

    }
?>