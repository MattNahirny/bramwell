$(document).ready(function(){
		
		
		$("#reportPrinter").click(function(){
			//change to be determined by drop down/file selector
			print(9); 
		})
		preparePage();
		
		
})


function preparePage(){
	fillViewableTable();
	
}


function fillViewableTable(){
	var data = {"request" : "getViewableReports"};
	var getReports = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getReports.success(function(data){
		for(key in data){
			var currentReportID = data[key]['PlanId'];
			var currentReportName = data[key]['PlanName'];
			var clientName = data[key]['CompanyName'];
			$("#viewReportTable").append("<tr id='" + currentReportID + "'>");
			$("#" + currentReportID).append("<td>" + (Number(key) + 1) + "</td>");
			$("#" + currentReportID).append("<td>" + currentReportName + "</td>");
			$("#" + currentReportID).append("<td>" + clientName + "</td>");
			$("#" + currentReportID).append("<td><input type='button' value='Select' class='selectReportButton'></input></td>");
			
		}
		
		
	});
	getReports.complete(function(){
		$(".selectReportButton").on("click", function(){
			$reportURI = printReport($(this).parent().parent().attr("id"));
			
		});
	});
	getReports.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}


function printReport($fileNo) {
	
	//report # goes here later
	printNewReport = $.ajax(
		{url : 'script/server.php',
		method: 'GET',
		data : {"request": "printReport", "planID" : $fileNo},
		dataType:'json',
		
		success: function(data){
console.log(data);
			
			console.log(data['msg']);
			if(data['uri'] != 'undefined' && data['uri'] != null && data['uri'] != ""){
				window.location = ("/script/" + data["uri"]);
			}
			else{
				console.log('error');
				$('.phase').prepend("<h3 id='errorMessage' style='color:red'>There was an error.</h3>");
				$('#errorMessage').delay(1500).fadeOut();
				setTimeout(function(){$("#errorMessage").remove()}, 2000);
			}
			
		}
		});	
		
		

		
}