$(document).ready(function(){
	getViews();
});

function getViews(){
	var data = {"request" : "main"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'html'});
	
	accessControlCall.success(function(data){
		if(data.length > 0){
			$("#mainButtonPanel").append(data);
		}
		else{
			$("#mainButtonPanel").append("<h3>You do not have sufficient privileges access to this page</h3>");
		}
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Working on a logout function


function logout() {
	var data = {"request" : "submitLogOutRequest"};
        var submitLogOutRequest = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
        submitLogOutRequest.success(function(data){
            alert("submitLogoutRequest Success");
            window.location.replace("/index.html");
        });
        
        submitLogOutRequest.error(function (jqXHR, textStatus, errorThrown){
            alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}
