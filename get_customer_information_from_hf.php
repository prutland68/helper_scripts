#!/usr/bin/env php
<?

function getInformation($supplier,$limit,$skip) {

    $curl = curl_init();

    $url = "http://admin:oisaiMahl1@172.22.12.12:5984/hf_entities/_design/active/_view/bydatasource?key=%22".$supplier."%22&limit=".$limit."&skip=".$skip;

    print_r($url);

    curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 14,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT=>400,
            CURLOPT_CUSTOMREQUEST => 'GET'
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response,true);

}

$header_array = array("id","partnerId","country","name","address1","town","county","province","postcode","phone","email","addUserId","claimUserId");



$limit =1000;
$skip = 1000;

$i = 0;

$out = fopen('brandify_hf_customers_november.csv', 'w');

fputcsv($out, $header_array);


do {


    $response = getInformation("brandify",$limit,($i*$limit));

    print_r($response);

    if (!is_array($response) || !isset($response['rows']) || !is_array($response['rows']) || !count($response['rows'])) {
      break;
   }
  

    sleep(10);
    foreach($response['rows'] as $cust){
        fputcsv($out, $cust['value']);
    }
    $i++;

    /* process i */

} while (1);

print($i);




?>