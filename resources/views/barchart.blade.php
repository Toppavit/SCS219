<!DOCTYPE html>
<html>
<head>
    <title>Product Price Bar Chart</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // Fetch product data from your Laravel API endpoint (Slide 61)
        fetch('/api/product')
          .then(response => response.json())
          .then(products => {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Product Name');
            data.addColumn('number', 'Price');

            // Format numbers explicitly as float (Slide 66)
            products.forEach(product => {
              data.addRow([product.name, parseFloat(product.price)]);
            });

            var options = {
              title: 'Product Prices',
              hAxis: {title: 'Products'},
              vAxis: {title: 'Price ($)'},
              legend: 'none'
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
          });
      }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 900px; height: 500px; margin: 0 auto;"></div>
</body>
</html>
