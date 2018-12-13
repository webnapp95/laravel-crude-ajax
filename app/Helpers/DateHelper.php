<?php
    namespace App\Helpers;

    class DateHelper {

        public function __construct() {

        }

        public static function dateFormat($date, $format = "Y-m-d H:i:s") {
            $date = date_create($date);
            return date_format($date, $format);
        }

        public static function dateDiff($date1, $date2) {
            $date1 = self::dateFormat($date1, "Y-m-d");
            $date2 = self::dateFormat($date2, "Y-m-d");
            return date_diff($date1,$date2);
        }

        public static checkValidateDate($day, $month, $year) {
            $day = $day > 31 ? 01 : $day;
            $month = $month > 12 ? 01 : $month;
            $year = $day < 1970 ? 01 : $year;
            return checkdate($month, $day, $year);
        }

    }
?>