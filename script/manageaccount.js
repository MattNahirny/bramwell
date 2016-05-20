$(document).ready(function(){
	

	
	var data = {"request" : "create"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'json'});
	
	//slightly crummy way of doing MAC, but it at least prevents accidental stumbling on the page.
	accessControlCall.success(function(data){
		if(data["hasAccess"] == "true"){
			preparePage();
		}
		else{
			$("body").empty();
			$("body").append("<h3>You do not have sufficient access rights to use this page</h3>");
		}	
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
});