<?php

class stubhub{
    function getEventData($request, $response, $args){
        $event = $args['event'];
        $section = $args['section'];
        
        $curl = curl_init();
        $url = "https://www.stubhub.com/shape/search/inventory/v2/?eventId=".$event."&sectionStats=true&zoneStats=true&start=0&allSectionZoneStats=true&eventLevelStats=true&sectionIdList=".$section."&sort=quality%20desc%2ClistingPrice%20asc&priceType=listingPrice&valuePercentage=true&tld=1&rows=20";
        $url = "https://www.stubhub.com/new-york-yankees-tickets-yankees-vs-rangers-6-29-2016/event/9443351/?mbox=1&rS=6&abbyo=true&sliderpos=true&qtyq=false&sType=DEFAULT&sid=171474";
        $url = "http://www.stubhubsandbox.com";
        echo $url;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
                          //https://www.stubhub.com/shape/search/inventory/v2/?eventId=9443351&sectionStats=true&zoneStats=true&start=0&allSectionZoneStats=true&eventLevelStats=true&sectionIdList=171473&rows=20&sort=quality+desc,listingPrice+asc&priceType=listingPrice&valuePercentage=true&tld=1
          CURLOPT_RETURNTRANSFER => true,
          //CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          //CURLOPT_CUSTOMREQUEST => "GET",
          
          //CURLOPT_POSTFIELDS => "action=login&username=abelabo30%40aol.com&password=nyjets",
          CURLOPT_HTTPHEADER => array(
            "Referer"=>"https://www.stubhub.com/new-york-yankees-tickets-yankees-vs-rangers-6-29-2016/event/9443351/?mbox=1&rS=6&abbyo=true&sliderpos=true&qtyq=false&sType=DEFAULT&sid=171474",
            "Accept"=>"application/json, text/javascript, */*; q=0.01",
            "User-Agent"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/601.5.17 (KHTML, like Gecko) Version/9.1 Safari/601.5.17",
            "Accept-Language"=>"en-us",
            "X-Distil-Ajax"=>"dsvrbwderzxycwuycecq",
            "X-Requested-With"=>"XMLHttpRequest"
            ),
        ));
        curl_setopt($curl,CURLOPT_COOKIEFILE,'cookies.txt');
curl_setopt($curl,CURLOPT_COOKIEJAR,'cookies.txt');
        $stresponse = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $newResponse = $response->withStatus(400);
          $newResponse->write( "cURL Error #:" . $err );
        } else {
            $newResponse = $response->withStatus(200);
          $newResponse->write( $stresponse );
        }
        
        return $newResponse;
    }
}