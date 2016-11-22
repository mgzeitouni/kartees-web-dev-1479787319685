<section class="content-3" style="padding: 50px;">
    <div>
        <div class="container">
            Account Summary
        </div>
        <div class="col-sm-6">
            <div id="Listings" style="width: 100%; min-height: 400px;"></div>
        </div>
        <div class="col-sm-6">
            Selling
        </div>
    </div>
</section>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Status', 'Number of Listings'],
          ['Listed',     11],
          ['Unlisted',      2],
          ['Sold',  2]
        ]);

        var options = {
          title: 'Listings Summary',
          pieHole: 0.5,
          pieSliceTextStyle: {
            color: 'black',
          },
          legend: 'none'
        };

        var chart = new google.visualization.PieChart(document.getElementById('Listings'));
        chart.draw(data, options);
      }
    </script>