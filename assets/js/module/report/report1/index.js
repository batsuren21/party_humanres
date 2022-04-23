"use strict";
var AppReport = function() {
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var init = function() {
		$(".kt-container").find(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true,
            todayHighlight: true,
            orientation: "bottom left",
        });
		$(document).on('change', '.departmentchange', function() {
			var value=$(this).val();
			if(value>0){
				window.location = KTAppOptions._RF+"/report/report1?_departmentid="+value;
			}else{
				window.location = KTAppOptions._RF+"/report/report1";				
			}
		});
		
		am4core.ready(function() {
	    	$.getJSON(KTAppOptions._RF+"/m/report/chart/chart_gender?departmentid="+__departmentid, function( chartData ) {
	        	am4core.useTheme(am4themes_animated);
	        	var chart = am4core.create("chartdiv_gender", am4charts.PieChart);
	        	chart.hiddenState.properties.opacity = 0;

	        	chart.radius = am4core.percent(70);
	        	chart.innerRadius = am4core.percent(40);
	        	
	        	var title = chart.titles.create();
	        	title.text = "- Хүйсийн байдал -";
	        	title.fontSize = 14;
	        	title.marginBottom = 20;
	        	title.marginTop = 0;
	    
	        	chart.data = chartData.data;
	        	for(var j=0; j<chartData.colors.length; j++){
	                var row=chartData.colors[j];
	                chart.colors.list[j]=am4core.color(row);
	            }
	            
	        	var pieSeries = chart.series.push(new am4charts.PieSeries());
	        	pieSeries.dataFields.value = "value";
	        	pieSeries.dataFields.category = "column";
	        	pieSeries.slices.template.stroke = am4core.color("#fff");
	        	pieSeries.slices.template.strokeWidth = 2;
	        	pieSeries.slices.template.strokeOpacity = 1;

	        	pieSeries.hiddenState.properties.opacity = 1;
	        	pieSeries.hiddenState.properties.endAngle = -90;
	        	pieSeries.hiddenState.properties.startAngle = -90;

	        	chart.legend = new am4charts.Legend();
	        	chart.legend.fontFamily='Arial';
	        	chart.legend.fontSize=12;
	    	});
	    	$.getJSON(KTAppOptions._RF+"/m/report/chart/chart_age?departmentid="+__departmentid, function( chartData ) {
	        	am4core.useTheme(am4themes_animated);
	        	var chart = am4core.create("chartdiv_age", am4charts.PieChart);
	        	chart.hiddenState.properties.opacity = 0;

	        	chart.radius = am4core.percent(70);
	        	chart.innerRadius = am4core.percent(40);
	        	
	        	var title = chart.titles.create();
	        	title.text = "- Насны ангилал -";
	        	title.fontSize = 14;
	        	title.marginBottom = 20;
	        	title.marginTop = 0;
	    
	        	chart.data = chartData.data;
	        	for(var j=0; j<chartData.colors.length; j++){
	                var row=chartData.colors[j];
	                chart.colors.list[j]=am4core.color(row);
	            }
	            
	        	var pieSeries = chart.series.push(new am4charts.PieSeries());
	        	pieSeries.dataFields.value = "value";
	        	pieSeries.dataFields.category = "column";
	        	pieSeries.slices.template.stroke = am4core.color("#fff");
	        	pieSeries.slices.template.strokeWidth = 2;
	        	pieSeries.slices.template.strokeOpacity = 1;

	        	pieSeries.hiddenState.properties.opacity = 1;
	        	pieSeries.hiddenState.properties.endAngle = -90;
	        	pieSeries.hiddenState.properties.startAngle = -90;

	        	chart.legend = new am4charts.Legend();
	        	chart.legend.fontFamily='Arial';
	        	chart.legend.fontSize=12;
	    	});
		}); 
	};
	return {
		init: function() {
			initToastr();
			init();
		},
	};
}();
jQuery(document).ready(function() {
	AppReport.init();
});