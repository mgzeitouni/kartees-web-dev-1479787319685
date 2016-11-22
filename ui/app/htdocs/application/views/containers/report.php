<?php
$data = json_decode($data, true)[0];
unset($data['_id']);
unset($data['_rev']);
unset($data['statuschangetime']);

$total_active_listings = 0;
$total_attending_listings = 0;
$total_investment = 0;
$total_sales  = 0;
$total_profit = 0;
$total_games_sold = 0;
$tier = array();
$columns = array();
$bar_chart1 = "['Tier', 'Active', 'Reserved', 'Sold']";

foreach($data as $tier=>$info){
    $total_active_listings += $info['total_num_games'];
    $total_attending_listings += $info['num_attending/attended'];
    $total_investment += $info['total_investment'];
    $total_sales += $info['total_sales'];
    $total_profit += $info['total_profit'];
    $total_games_sold += $info['num_games_sold'];
    $tiers[$tier] = $info['total_num_games'];
    
    $bar_chart1 .= ",['".$tier."',".$info['total_num_games'].",".$info['num_attending/attended'].",".$info['num_games_sold']."]";
    
    $rows[] = $tier;
    foreach($info as $column_name=>$infos){
        if(!in_array($column_name, $columns))
            $columns[] = $column_name;
    }

}

$table = "<table border='1' cellspacing='1' cellpadding='2' width='80%' style='margin:0 auto'><tr><td></td>";
foreach($rows as $row){
    $table .= "<td>$row</td>";
}
$table .= "</tr>";

foreach($columns as $column){
    $table .= "<tr><td>".ucwords(str_replace("_", " ",$column))."</td>";
    foreach($rows as $row){
        $table .= "<td>".$data[$row][$column]."</td>";
    }
    $table .= "</tr>";
}
$table .= "</table>";

$donut_chart1 = "['Tier', 'Games']";
foreach($tiers as $index=>$data){
    $donut_chart1 .= ",['".$index."',".$data."]" ;
}


?>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBarColors);
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          <?= $donut_chart1 ?>
        ]);

        var options = {
          title: 'Listings Summary',
          pieHole: 0.3,
          tooltip: {
            text: "value"
          },
          labels: 'name',
          pieSliceText: 'label',
          legend: 'none',
          sliceVisibilityThreshold:-1
        };

        var chart = new google.visualization.PieChart(document.getElementById('Listings'));
        chart.draw(data, options);
      }
      
      
      //Bar Chart
      
      
    
        
        function drawBarColors() {
              var data = google.visualization.arrayToDataTable([
                <?= $bar_chart1 ?>
              ]);
        
            var options = {
                title: '',
                chartArea: {width: '60%'},
                colors: ['#2c3e50', '#c0392b', "#1abc9c"],
                hAxis: {
                  title: 'Listings',
                  textPosition: 'in',
                },
                vAxis: {
                  title: 'Tiers',
                  minValue: 1,
                  textPosition: 'in'
                },
                axisTitlesPosition: 'none',
                legend: {
                    position: 'bottom'
                },
                trendlines: 'none',
                isStacked: 'percent'
            };
            var chart = new google.visualization.BarChart(document.getElementById('Sold_Chart'));
            chart.draw(data, options);
        }
    </script>
    
    <div id="Listings" style="display: inline-block; width: 38%; min-height: 400px;"></div>
    <div id="Sold_Chart" style="display: inline-block; width: 60%; min-height: 400px;"></div>
<?= $table ?>