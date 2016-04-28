
function logIn(){

	var username = document.getElementById("inputUsername").value;
	var password = document.getElementById("inputPassword").value;
	
	var data = {
		"request" : "submitLogInRequest",
		"username" : username,
		"password" : password,
	};
	console.log(data);
	var submitLogInRequest = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitLogInRequest.success(function(data){
		if(data['loggedIn'] == "false"){
			window.alert('Incorrect password/username');
		}
		else if(data['loggedIn'] == "true"){
			console.log('Logged in');
			window.location.href = "main.html";
		}
		
	});
	
	submitLogInRequest.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
		
}
