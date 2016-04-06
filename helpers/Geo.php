<?php

class Geo {

    static function getLatLon($address) {
        //echo utf8_decode($address);
        //$address = urlencode( utf8_encode( $address ) );
        $address = urlencode(( $address));
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Brazil";
        $json = @file_get_contents($url);
        $json = json_decode($json);
        if (isset($json->status) && $json->status == "OK") {
            $lat = $json->results[0]->geometry->location->lat;
            $lon = $json->results[0]->geometry->location->lng;
            return array('lat' => $lat, 'lon' => $lon, 'json' => "{\"lat\":\"$lat\",\"lon\":\"$lon\"}");
        } else {
            return array('lat' => '', 'lon' => '', 'json' => '');
        }
    }

    static function getLatLonCep($zip) {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zip) . "&sensor=false";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return array('lat' => $result3[0]['lat'], 'lon' => $result3[0]['lng']);
    }

}
