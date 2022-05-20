#!/usr/bin/env php
<?php

function check_alive($url, $timeout = 30) {
    $ch = curl_init();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
    $http_respond = curl_exec($ch);
    $http_respond = trim( strip_tags( $http_respond ) );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    print_r($url."--->".$http_code."\n\n");
    if ( ( $http_code == 200 ) || ( $http_code == 302 )  || ( $http_code == 301 ) ) {
        return true;
    } else {
        // you can return $http_code here if necessary or wanted
        return false;
    }
    curl_close( $ch );
  }



$row = 1;
$theArray = array();
$results = array(array("URL","STATUS"));

if (($handle = fopen("./files/Aptum-domains-list.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {



        if (check_alive($data[0])) {
            $results[] = array($data[0],"Good");
        } else {
            $results[] = array($data[0],"Bad");

        }

        //print_r($results);
        
    }
    fclose($handle);

    //print_r($results);

$fp = fopen('./files/Aptum-domains-list-results.csv', 'w');

foreach ($results as $fields) {
    fputcsv($fp, $fields,",");
}

fclose($fp);

}
