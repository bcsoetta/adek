// Load the visualization API and the piechart package.
google.charts.load('current', {'packages': ['corechart']});
// Set a callback to run when the google visualization API is loaded.
google.charts.setOnLoadCallback(pie_chart1);
google.charts.setOnLoadCallback(pie_chart2);
google.charts.setOnLoadCallback(pie_chart3);
google.charts.setOnLoadCallback(bar_chart);

function pie_chart1() {
	var jsonData = $.ajax({
		url: "../iska/ajax/ajax_chart_pie1.php",
		dataType: "json",
		async: false
	}).responseText;

	// Create our data table out of JSON data loaded from server.
	// alert(jsonData); return false
	var data = new google.visualization.DataTable(jsonData);
	var options = {
	    title: 'Persentase Kepatuhan Pengguna Jasa',
	    is3D: true
	};
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('piechart_div1'));

	chart.draw(data, options);
}

function pie_chart2() {
	var jsonData = $.ajax({
		url: "../iska/ajax/ajax_chart_pie2.php",
		dataType: "json",
		async: false
	}).responseText;

	// Create our data table out of JSON data loaded from server.
	// alert(jsonData); return false
	var data = new google.visualization.DataTable(jsonData);
	var options = {
	    title: 'Persentase Pelanggaran Setiap Unit',
	    is3D: true
	};
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('piechart_div2'));

	chart.draw(data, options);
}

function pie_chart3() {
	var jsonData = $.ajax({
		url: "../iska/ajax/ajax_chart_pie3.php",
		dataType: "json",
		async: false
	}).responseText;

	// Create our data table out of JSON data loaded from server.
	// alert(jsonData); return false
	var data = new google.visualization.DataTable(jsonData);
	var options = {
	    title: 'Persentase Pelanggaran Per Jenis Pelanggaran',
	    is3D: true
	};
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('piechart_div3'));

	chart.draw(data, options);
}

function bar_chart() {
	var jsonData = $.ajax({
		url: "../iska/ajax/ajax_chart_bar.php",
		dataType: "json",
		async: false,
		success: function(jsonData) {
			var data = new google.visualization.arrayToDataTable(jsonData);
			var options = {
		        title: 'Perusahaan Yang Melakukan Pelanggaran',
		        colors: ['#dc3912','#0099c6'],
		        chartArea: {width: '50%'},
		        isStacked: true,
		        is3D: true,
		        hAxis: {
		          	title: '',
		          	minValue: 0,
		        },
		        vAxis: {
		          	title: ''
		        }
	      };
			var chart = new google.visualization.BarChart(document.getElementById('bar_chart'));
			chart.draw(data, options);
		}
	}).responseText;
}