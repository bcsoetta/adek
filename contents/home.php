<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<style type="text/css">
	
	#myChartx {
		border: 1px solid #ddd;
		padding: 10px 0 10px 0;
	}
	#pageTitle {
		margin-bottom: 10px;
	}

</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;Home</p>

<canvas id="myChartx" width="940" height="350"></canvas>

<script>
	function autoRefresh() {

		$.ajax({
			url: "../adek/ajax/ajax_dashboard.php",
			method: "GET",
			success: function(data) {
				// console.log(data);

				var today = new Date();

				// get days
				var weekday = new Array(7);
			    weekday[0] = "SUNDAY";
			    weekday[1] = "MONDAY";
			    weekday[2] = "TUESDAY";
			    weekday[3] = "WEDNESDAY";
			    weekday[4] = "THRUSDAY";
			    weekday[5] = "FRIDAY";
			    weekday[6] = "SATURDAY";

			    var day = weekday[today.getDay()];

			    // get month name
			    var monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

			    var month = monthNames[today.getMonth()];

				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();

				if (dd < 10) {
				    dd = '0' + dd
				} 

				if (mm < 10) {
				    mm = '0' + mm
				} 

				today = day + ' ' + month + ' ' + dd + ', ' + yyyy;

				var statuss = [];
				var jum_pib = [];

				for(var i in data) {
					statuss.push(data[i].statuss);
					jum_pib.push(data[i].jum_pib);
				}

				var chartdata = {
					labels: statuss,
					datasets: [
						{
							label: 'Jumlah',
							backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
							data: jum_pib
						}
					]
				}

				// console.log(chartdata);

				var ctx = $("#myChartx");

				var myBarChart  = new Chart(ctx, {
					type: "bar",
					data: chartdata,
					options: {
						legend: {
							display: false
						},
						scales: {
					        yAxes: [{
					            ticks: {
					                beginAtZero: true
					            }
					        }]
					    },
					    title: {
					        display: true,
					        text: 'DISTRIBUTION STATUS ' + today,
					        fontColor: '#008080'
					    },
					    animation:{
				            duration: 0
				        }
					}
				})

				setTimeout(autoRefresh, 20000);

			},

			error: function(data) {
				console.log(data);
				setTimeout(autoRefresh, 20000);
			}
		});
	}

	$(document).ready(function() {
		autoRefresh();
	});

	

</script>