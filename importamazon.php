<?php
date_default_timezone_set('America/New_York');
    require("database.php");
    $csv = array_map('str_getcsv', file("report-9.csv"));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv); # remove column header
    echo "<pre>";
    //print_r($csv);
    
    $i = 0;
    $fees = array();
    $sku = $csv[0]['Order ID'];
    foreach($csv as $row){
          if($sku != $row['Order ID'])
            $i++;
            
          $sku = $row['Order ID'];
          if($row['Payment Type'] == "Product charges"){
              $array[$i]['Date'] = $row['Date'];
              $array[$i]['Order ID'] = $row['Order ID'];
              $array[$i]['SKU'] = $row['SKU'];
              $array[$i]['Transaction type'] = $row['Transaction type'];
              $array[$i]['Payment Type'] = $row['Payment Type'];
              $array[$i]['Payment Detail'] = $row['Payment Detail'];
              $array[$i]['Amount'] = $row['Amount'];
              $array[$i]['Quantity'] = $row['Quantity'];
              $array[$i]['Product Title'] = $row['Product Title'];
          } else {
              $array[$i][$row['Payment Type']." - ".$row['Payment Detail']] = str_replace("(", "-", str_replace(")", "", $row['Amount']));
              if(!in_array($row['Payment Type']." - ".$row['Payment Detail'], $fees)){
                $fees[] = $row['Payment Type']." - ".$row['Payment Detail'];
              }
          }
          

    }
    
    //print_r($array);
    //print_r($fees);
    
    
    function array_keys_recursive($myArray, $MAXDEPTH = INF, $depth = 0, $arrayKeys = array()){
       foreach($myArray as $array){
        foreach($array as $key => $narray){
          if(!in_array($key, $arrayKeys)){
                $arrayKeys[] = $key;
          }
       }
       }

        return $arrayKeys;
    }
    
    
    
    $file = fopen("report-91.csv","w");
    
  $keys = array_keys_recursive($array);
  print_r(print_r($keys));
      fputcsv($file, $keys);  
    
  
$a = 0;
foreach ($array as  $line)
  {
    if($a == 0){
      //$keys = array_keys_recursive($array);
      //fputcsv($file, $keys);
      $a++;
    }
    
    foreach($keys as $key){
      $crow[] = $line[$key];
    }
    fputcsv($file, $crow);
    unset($crow);
  }

fclose($file);
?> 