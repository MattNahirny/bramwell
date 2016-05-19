<?php

class Util {


    /**
     * Format date
     *
     * @param date $date
     *
     * @return String (eg: January, 1, 2016)
     */
    public static function formatDate($date) {

       // echo $date;exit;
        $date = strtotime($date);

        return date("F j, Y", $date); 
    }


    public static function formatZIP($zip) {

        return implode(" ", str_split($zip, 3))." ";
    }
    
    
    public static function getYear($date) {

        $parts = explode('-', $date);

        return $parts[0];
    }
    

}