$(document).ready(function(){
	getViews();
});

//MAC controll stuff
function getViews(){
	var data = {"request" : "admin"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'html'});
	
	accessControlCall.success(function(data){
		if(data.length > 0){
			$("#adminButtonContainer").prepend(data);
			
			preparePage();
		}
		else{
			$("#adminButtonContainer").prepend("<h3>You do not have sufficient privileges access to this page</h3>");
		}
		
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Mostly event delegation for paging between things
function preparePage(){
	//fade out everything, then fades in the admin control panel
	$(".btnCancel").on("click", function(){
		$("body > div").css("display", "none");
		$("#adminButtonContainer").fadeIn();
	});
	//basic fadeins to selected pages
	$("#adminButtonContainer").on("click", "#createUser", function(){
		$("#adminButtonContainer").css("display", "none");
		$("#createUserContainer").fadeIn();
	});
	
	$("#adminButtonContainer").on("click", "#editConstructInfo", function(){
		$("#adminButtonContainer").css("display", "none");
		$("#editConstructTable").css("display", "none");
		$("#editConstructInfoContainer").css("display", "none");
		$("#displayBuildingType").css("display", "none");
		$("#editConstructInfoChoiceContainer").css("display", "block");
		$("#editConstructionInfo").fadeIn();
		
	});
	//Adding construction info button, adds all these to the table
	//Sets the submission button event delegation
	$("#addConstructInfo").on("click", function(){
		
		$("#editConstructTable").append("<tr id='createConstruct'></tr>");
		
		$("#createConstruct").append("<td id='createInfoType'><select></select></td>");
		$("#createInfoType select").append("<option value='overview'>Overview</option>");
		$("#createInfoType select").append("<option value='substructure'>Substructure</option>");
		$("#createInfoType select").append("<option value='foundations'>Foundations</option>");
		$("#createInfoType select").append("<option value='exterior'>Exterior</option>");
		$("#createInfoType select").append("<option value='roof'>Roof/Drainage</option>");
		$("#createInfoType select").append("<option value='amenities'>Amenities</option>");
		$("#createInfoType select").append("<option value='electrical'>Electrical</option>");
		$("#createInfoType select").append("<option value='services'>Services</option>");
		
		$("#createConstruct").append("<td id='createBuildingType'><select></select></td>");
		$("#createBuildingType select").append("<option value='flatland'>Flat Land</option>");
		$("#createBuildingType select").append("<option value='complex'>Complex</option>");
		$("#createBuildingType select").append("<option value='townhome'>townhome</option>");
		$("#createBuildingType select").append("<option value='all'>All</option>");
		
		$("#createConstruct").append("<td id='createComment'><textarea></textarea></td>");

		$("#createConstruct").append("<td><input type='button' id='submitConstructInfo' value='Submit'></input></td>");
		
		$("#submitConstructInfo").on("click", function(){
			submitConstructionInfo();
		});
		
	});

	$("#editConstructInfoChoiceContainer").on("click", "input[type=button]", function(){
		$("#editConstructInfoChoiceContainer").css("display", "none");
		prepareConstructInfoTable($(this).attr("id"));
	});
	
	$("#adminButtonContainer").on("click", "#editUsers", function(){
		$("#adminButtonContainer").css("display", "none");
		prepareEditUserTable();
	});
	
	$("#adminButtonContainer").on("click", "#createClient", function(){
		$("#adminButtonContainer").css("display", "none");
		$("#createClientContainer").fadeIn();
	});
	
	$("#adminButtonContainer").on("click", "#editClients", function(){
		$("#adminButtonContainer").css("display", "none");
		prepareEditClientTable();
	});
	
	$("#adminButtonContainer").on("click", "#createInspector", function(){
		$("#adminButtonContainer").css("display", "none");
		$("#createInspectorContainer").fadeIn();
	});
	
	$("#adminButtonContainer").on("click", "#editInspectors", function(){
		$("#adminButtonContainer").css("display", "none");
		prepareEditInspectorTable();
	});
}

//Download data for construction info, fill table.
//Can only create new, or delete, for simplicites sake
//This was done with a few days on the clock, dig?
function prepareConstructInfoTable(btnID){
	var buildingType;
	switch(btnID){
		case "btnFlatLand":
			buildingType = "flatland";
			$("#displayBuildingType").text("Bare Land");
			break;
		case "btnComplex":
			buildingType = "complex";
			$("#displayBuildingType").text("Complex");
			break;
		case "btnTownHouse":
			buildingType = "townhouse";
			$("#displayBuildingType").text("Townhouse");
			break;
		default:
			buildingType = "custom";
			$("#displayBuildingType").text("All");
			break;
	}
	var data = {"request" : "getConstructionInfo", "buildingType" : buildingType};
	var getConstructionInfo = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	getConstructionInfo.success(function(data){
		console.log(data);
		//clears table aside from headers
		$("#editConstructTable tr:not(#editConstructTable tr:first-child)").remove();
		for(count in data){
			var constRowID = "constructionRow" + count;
			$("#editConstructTable").append("<tr id='" + constRowID + "' data-constructID='" + data[count]['ConstructInfoId'] + "'></tr>");
			$("#" + constRowID).append("<td>" + data[count]['InfoType'] + "</td>");
			$("#" + constRowID).append("<td>" + data[count]['BuildingType'] + "</td>");
			$("#" + constRowID).append("<td>" + data[count]['Comment'] + "</td>");

			$("#" + constRowID).append("<td><input type='button' class='deleteConstRow' value='Delete'></input></td>");
		}
		//delegation for deleting a row
		$(".deleteConstRow").on("click", function(){
			deleteConstructionInfo($(this).parent().parent().attr('data-constructID'));
		});
	});
	
	getConstructionInfo.complete(function(){
		$("#editConstructTable").fadeIn();
		$("#displayBuildingType").fadeIn();
		$("#editConstructInfoContainer").fadeIn();
		
	});
	
	getConstructionInfo.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Submitting construction info, just the one this time.
function submitConstructionInfo(){
	//add all the values to the table properly, send data to table.
	var infoType = $("#createInfoType select").val();
	var buildingType = $("#createBuildingType select").val();
	var comment = $("#createComment textarea").val();
	
	
	
	var data = {
		"request" : "submitConstructInfo",
		"infoType" : infoType,
		"buildingType" : buildingType,
		"comment" : comment
	};
	console.log(data);
	var submitConstructInfo = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	submitConstructInfo.success(function(data){
		$("#createConstruct").remove();
		
		var constRowID = "constructionRow" + ($("#editConstructTable tr").length - 1);
		$("#editConstructTable").append("<tr id='" + constRowID + "'></tr>");
		$("#" + constRowID).append("<td>" + infoType + "</td>");
		$("#" + constRowID).append("<td>" + buildingType + "</td>");
		$("#" + constRowID).append("<td>" + comment + "</td>");
	});
	
	submitConstructInfo.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Deletes a single row from the table. 
//The other tables could use this, honestly.
function deleteConstructionInfo(constructInfoID){

	var data = {
		"request" : "deleteConstructionInfo",
		"constructInfoID" : constructInfoID
	};
	
	var submitConstructInfo = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	submitConstructInfo.success(function(data){
		$("tr[data-constructID=" + constructInfoID + "]").fadeOut();
		//window.location.href = "index.html";
		

		
	});
	
	submitConstructInfo.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}
//Submitting new user.
function submitUser(){

	var username = document.getElementById("inputUsername").value;
	var password = document.getElementById("inputPassword").value;
	var email = document.getElementById("inputUserEmail").value;
	var accessLevel = document.getElementById("inputAccessLevel").value;
	
	var data = {
		"request" : "submitUser",
		"username" : username,
		"password" : password,
		"email" : email,
		"accessLevel" : accessLevel
	};
	console.log(data);
	var submitUser = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitUser.success(function(data){
		console.log(data);
		$("#adminButtonContainer").fadeIn();
		$("#createUserContainer").fadeOut();
		//window.location.href = "index.html";
		
	});
	
	submitUser.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}
//Pretty much the same as costing table,
//Delicate jQuery to make it all work, beware changing the table structure.
function prepareEditUserTable(){
	var data = {"request" : "getUsers"};
	console.log(data);
	var getUsers = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	getUsers.success(function(data){
		$("#editUserTable tr:not(:first-child)").remove();
		for(count in data){
			var userRowID = "userRow" + count;
			$("#editUserTable").append("<tr id='" + userRowID + "'></tr>");
			$("#" + userRowID).append("<td class='userID'>" + data[count]['UserId'] + "</td>");
			$("#" + userRowID).append("<td class='username'>" + data[count]['Username'] + "</td>");
			
			$("#" + userRowID).append("<td class='email'>" + data[count]['Email'] + "</td>");
			$("#" + userRowID).append("<td class='accessLevel'>" + data[count]['AccessLevel'] + "</td>");
			$("#" + userRowID).append("<td><input type='button' class='editUserRow' value='Edit User'></input></td>");
		}
		//window.location.href = "index.html";
		
	});
	
	getUsers.complete(function(){
		$("#editUserContainer").fadeIn();
		
		$(".editUserRow").on("click", function(){
			//entering edit mode
			if($(this).parent().siblings().children('input').length === 0){
				$(this).attr("value", "Finish");
				var currentUsername = $(this).parent().siblings('.username').text();
				var currentEmail = $(this).parent().siblings('.email').text();
				var currentAccessLevel = $(this).parent().siblings('.accessLevel').text();
				
				$(this).parent().siblings('.username').empty();
				$(this).parent().siblings('.username').append("<input type='text'>");
				$(this).parent().siblings('.username').children("input").val(currentUsername);
				
				$(this).parent().siblings('.email').empty();
				$(this).parent().siblings('.email').append("<input type='text' value='" + currentEmail + "'>");
				$(this).parent().siblings('.email').children("input").val(currentEmail);
				
				$(this).parent().siblings('.accessLevel').empty();
				$(this).parent().siblings('.accessLevel').append("<select type='text' id='inputAccessLevel'><option value='1'>1: Clients</option><option value='2'>2: Inspectors</option><option value='3'>3: Costing</option><option value='4'>4: Estimator</option><option value='5'>5: Assistant</option><option value='6'>6: Administrator</option></select>");
				$(this).parent().siblings('.accessLevel').children("input").val(currentAccessLevel);
				
			}
			//leaving edit
			else{
				$(this).attr("value", "Edit User");
				$(this).parent().siblings('input').empty();
				
				var currentUsername = $(this).parent().siblings(".username").children("input").val();
				var currentEmail = $(this).parent().siblings(".email").children("input").val();
				var currentAccessLevel = parseInt($(this).parent().siblings(".accessLevel").children("select").val());
				console.log(typeof currentAccessLevel); //debugging
                                console.log(currentAccessLevel);
				if(isNumeric(currentAccessLevel)){
					$(this).parent().siblings('.username').empty();
					$(this).parent().siblings('.username').text(currentUsername);
					
					$(this).parent().siblings('.email').empty();
					$(this).parent().siblings('.email').text(currentEmail);
					
					$(this).parent().siblings('.accessLevel').empty();
					$(this).parent().siblings('.accessLevel').text(currentAccessLevel);
				}
				else{
					window.alert("Non numeric value!");
				}
			}
		});
		
		
		$("#btnSubmitUserUpdate").on("click", function(){
			submitUserUpdate();
		});
	});
	
	getUsers.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

//Submits all users again, inefficient but easier than tracking changes and adapting to that.
function submitUserUpdate(){

	if($("#editUserTable input[type=number]").length > 0 || $("#editUserTable input[type=text").length > 0){
		$(".editUserRow").each(function(){
			if($(this).parent().siblings().find("input").length > 0){
				$(this).click();
			}
		});
	}
	
	console.log('going');
	var rows = $("#editUserTable tr").length;
	var dataArray = {};
	
	for(count = 0; count < rows; count++){
		var currentArray = {};
		
		currentArray['userID'] = $("#userRow" + count).children(".userID").text();
		currentArray['username'] = $("#userRow" + count).children(".username").text();
		currentArray['email'] = $("#userRow" + count).children(".email").text();
		currentArray['accessLevel'] = $("#userRow" + count).children(".accessLevel").text();
		dataArray[count] = currentArray;
	}
	
	var data = {"request" : "submitUserUpdate", "dataArray" : dataArray};
	var submitUserUpdate = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitUserUpdate.success(function(data){
		$("body > div").css("display", "none");
		$("#adminButtonContainer").fadeIn();
	});
	
	submitUserUpdate.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}
//submitting new client
function submitClient(){
					
	var firstName = document.getElementById("inputPMFirstName").value;
	var lastName = document.getElementById("inputPMLastName").value;
	var company = document.getElementById("inputCompany").value;
	var address = document.getElementById("inputAddress").value;
	var city = document.getElementById("inputCity").value;
	
	var data = {
		"request" : "submitClient",
		"firstName" : firstName,
		"lastName" : lastName,
		"company" : company,
		"address" : address,
		"city" : city
	};
	
	console.log(data);
	var addModule = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	addModule.success(function(data){
		$("#adminButtonContainer").fadeIn();
		$("#createClientContainer").fadeOut();
		console.log(data);
		//window.location.href = "index.html";
		
	});
	
	addModule.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

//Pretty much the same as costing table,
//Delicate jQuery to make it all work, beware changing the table structure.
function prepareEditClientTable(){

	var data = {
		"request" : "getClients"
	};
	console.log(data);
	var getClient = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getClient.success(function(data){
		$("#editClientTable tr:not(:first-child)").remove();
		for(count in data){
			var clientRowID = "clientRow" + count;
			$("#editClientTable").append("<tr id='" + clientRowID + "'></tr>");
			$("#" + clientRowID).append("<td class='clientID'>" + data[count]['ClientId'] + "</td>");
			$("#" + clientRowID).append("<td class='firstName'>" + data[count]['PMFirstName'] + "</td>");
			$("#" + clientRowID).append("<td class='lastName'>" + data[count]['PMLastName'] + "</td>");
			
			$("#" + clientRowID).append("<td class='company'>" + data[count]['CompanyName'] + "</td>");
			$("#" + clientRowID).append("<td class='address'>" + data[count]['Address'] + "</td>");
			$("#" + clientRowID).append("<td class='city'>" + data[count]['City'] + "</td>");
			$("#" + clientRowID).append("<td><input type='button' class='editClientRow' value='Edit Client'></input></td>");
		}
		console.log(data);
		//window.location.href = "index.html";
		
	});
	
	getClient.complete(function(){
		$("#editClientContainer").fadeIn();
		
		$(".editClientRow").on("click", function(){
			
			//entering edit mode
			if($(this).parent().siblings().children('input').length === 0){
				$(this).attr("value", "Finish");
				var currentFirstName = $(this).parent().siblings('.firstName').text();
				var currentLastName = $(this).parent().siblings('.lastName').text();
				var currentCompany = $(this).parent().siblings('.company').text();
				var currentAddress = $(this).parent().siblings('.address').text();
				var currentCity = $(this).parent().siblings('.city').text();
				
				$(this).parent().siblings('.firstName').empty();
				$(this).parent().siblings('.firstName').append("<input type='text'>");
				$(this).parent().siblings('.firstName').children("input").val(currentFirstName);
				
				$(this).parent().siblings('.lastName').empty();
				$(this).parent().siblings('.lastName').append("<input type='text'>");
				$(this).parent().siblings('.lastName').children("input").val(currentLastName);
				
				$(this).parent().siblings('.company').empty();
				$(this).parent().siblings('.company').append("<input type='text'>");
				$(this).parent().siblings('.company').children("input").val(currentCompany);
				
				$(this).parent().siblings('.address').empty();
				$(this).parent().siblings('.address').append("<input type='text'>");
				$(this).parent().siblings('.address').children("input").val(currentAddress);
				
				$(this).parent().siblings('.city').empty();
				$(this).parent().siblings('.city').append("<input type='text'>");
				$(this).parent().siblings('.city').children("input").val(currentCity);
			}
			//leaving edit
			else{
				$(this).attr("value", "Edit Client");
				$(this).parent().siblings('input').empty();
				
				var currentFirstName = $(this).parent().siblings(".firstName").children("input").val();
				var currentLastName = $(this).parent().siblings(".lastName").children("input").val();
				var currentCompany = $(this).parent().siblings(".company").children("input").val();
				var currentAddress = $(this).parent().siblings(".address").children("input").val();
				var currentCity = $(this).parent().siblings(".city").children("input").val();
				
				
				$(this).parent().siblings('.firstName').empty();
				$(this).parent().siblings('.firstName').text(currentFirstName);
				
				$(this).parent().siblings('.lastName').empty();
				$(this).parent().siblings('.lastName').text(currentLastName);
				
				$(this).parent().siblings('.company').empty();
				$(this).parent().siblings('.company').text(currentCompany);
				
				$(this).parent().siblings('.address').empty();
				$(this).parent().siblings('.address').text(currentAddress);
			
				$(this).parent().siblings('.city').empty();
				$(this).parent().siblings('.city').text(currentCity);
			
			}
		});
		
		$("#btnSubmitClientUpdate").on("click", function(){
			submitClientUpdate();
		});
			
	});
	
	getClient.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}
//Submits all clients again, inefficient but easier than tracking changes and adapting to that.
function submitClientUpdate(){
	console.log('going');
	var rows = $("#editClientTable tr").length;
	var dataArray = {};
	
	if($("#editClientTable input[type=number]").length > 0 || $("#editClientTable input[type=text").length > 0){
		$(".editClientRow").each(function(){
			if($(this).parent().siblings().find("input").length > 0){
				$(this).click();
			}
		});
	}
	for(count = 0; count < rows; count++){
		var currentArray = {};
		
		currentArray['clientID'] = $("#clientRow" + count).children(".clientID").text();
		currentArray['firstName'] = $("#clientRow" + count).children(".firstName").text();
		currentArray['lastName'] = $("#clientRow" + count).children(".lastName").text();
		currentArray['company'] = $("#clientRow" + count).children(".company").text();
		currentArray['address'] = $("#clientRow" + count).children(".address").text();
		currentArray['city'] = $("#clientRow" + count).children(".city").text();
		dataArray[count] = currentArray;
	}
	console.log(dataArray);
	
	var data = {"request" : "submitClientUpdate", "dataArray" : dataArray};
	var submitClientUpdate = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitClientUpdate.success(function(data){
		$("body > div").css("display", "none");
		$("#adminButtonContainer").fadeIn();
	});
	
	submitClientUpdate.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

//Submitting new inspector
function submitInspector(){					
	var firstName = document.getElementById("inputInspectorFirstName").value;
	var lastName = document.getElementById("inputInspectorLastName").value;
	var email = document.getElementById("inputInspectorEmail").value;
	var phone = document.getElementById("inputInspectorPhone").value;
	var cell = document.getElementById("inputInspectorCell").value;
	
	var data = {
		"request" : "submitInspector",
		"firstName" : firstName,
		"lastName" : lastName,
		"email" : email,
		"phone" : phone,
		"cell" : cell
	};
	console.log(data);
	var addModule = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	addModule.success(function(data){
		console.log(data);
		$("#adminButtonContainer").fadeIn();
		$("#createInspectorContainer").fadeOut();
		//window.location.href = "index.html";
		
	});
	
	addModule.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

//Pretty much the same as costing table,
//Delicate jQuery to make it all work, beware changing the table structure.
function prepareEditInspectorTable(){
	
	var data = {
		"request" : "getInspectors"
	};
	console.log(data);
	var getInspectors = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getInspectors.success(function(data){
		console.log(data);
		$("#editInspectorTable tr:not(:first-child)").remove();
		for(count in data){
			var userRowID = "inspectorRow" + count;
			$("#editInspectorTable").append("<tr id='" + userRowID + "'></tr>");
			$("#" + userRowID).append("<td class='inspectorID'>" + data[count]['InspectorId'] + "</td>");
			$("#" + userRowID).append("<td class='firstName'>" + data[count]['FirstName'] + "</td>");
			
			$("#" + userRowID).append("<td class='lastName'>" + data[count]['LastName'] + "</td>");
			$("#" + userRowID).append("<td class='email'>" + data[count]['Email'] + "</td>");
			$("#" + userRowID).append("<td class='phone'>" + data[count]['Phone'] + "</td>");
			$("#" + userRowID).append("<td class='cell'>" + data[count]['Cell'] + "</td>");
			$("#" + userRowID).append("<td><input type='button' class='editInspectorRow' value='Edit Inspector'></input></td>");
		}
		
	});
	
	getInspectors.complete(function(){
		$("#editInspectorContainer").fadeIn();
		
		$(".editInspectorRow").on("click", function(){
			
			//entering edit mode
			if($(this).parent().siblings().children('input').length === 0){
				$(this).attr("value", "Finish");
				var currentFirstName = $(this).parent().siblings('.firstName').text();
				var currentLastName = $(this).parent().siblings('.lastName').text();
				var currentEmail = $(this).parent().siblings('.email').text();
				var currentPhone = $(this).parent().siblings('.phone').text();
				var currentCell = $(this).parent().siblings('.cell').text();
				
				$(this).parent().siblings('.firstName').empty();
				$(this).parent().siblings('.firstName').append("<input type='text'>");
				$(this).parent().siblings('.firstName').children("input").val(currentFirstName);
				
				
				$(this).parent().siblings('.lastName').empty();
				$(this).parent().siblings('.lastName').append("<input type='text'>");
				$(this).parent().siblings('.lastName').children("input").val(currentLastName);
				
				
				$(this).parent().siblings('.email').empty();
				$(this).parent().siblings('.email').append("<input type='text' value='" + currentEmail + "'>");
				$(this).parent().siblings('.email').children("input").val(currentEmail);
				
				$(this).parent().siblings('.phone').empty();
				$(this).parent().siblings('.phone').append("<input type='text' value='" + currentPhone + "'>");
				$(this).parent().siblings('.phone').children("input").val(currentPhone);
				
				$(this).parent().siblings('.cell').empty();
				$(this).parent().siblings('.cell').append("<input type='text' value='" + currentCell + "'>");
				$(this).parent().siblings('.cell').children("input").val(currentCell);
			}
			//leaving edit
			else{
				$(this).attr("value", "Edit Inspector");
				$(this).parent().siblings('input').empty();
				
				var currentFirstName = $(this).parent().siblings(".firstName").children("input").val();
				var currentLastName = $(this).parent().siblings(".lastName").children("input").val();
				var currentEmail = $(this).parent().siblings(".email").children("input").val();
				var currentPhone = $(this).parent().siblings(".phone").children("input").val();
				var currentCell = $(this).parent().siblings(".cell").children("input").val();
				
				
				$(this).parent().siblings('.firstName').empty();
				$(this).parent().siblings('.firstName').text(currentFirstName);
				
				$(this).parent().siblings('.lastName').empty();
				$(this).parent().siblings('.lastName').text(currentLastName);
				
				$(this).parent().siblings('.email').empty();
				$(this).parent().siblings('.email').text(currentEmail);
				
				$(this).parent().siblings('.phone').empty();
				$(this).parent().siblings('.phone').text(currentPhone);
				
				$(this).parent().siblings('.cell').empty();
				$(this).parent().siblings('.cell').text(currentCell);
				
			}
		});
		
		$("#btnSubmitInspectorUpdate").on("click", function(){
			submitInspectorUpdate();
		});
	});
	
	getInspectors.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}
//Submits all inspectors again, inefficient but easier than tracking changes and adapting to that.
function submitInspectorUpdate(){
	if($("#editInspectorTable input[type=number]").length > 0 || $("#editInspectorTable input[type=text").length > 0){
		$(".editInspectorRow").each(function(){
			if($(this).parent().siblings().find("input").length > 0){
				$(this).click();
			}
		});
	}
	var rows = $("#editInspectorTable tr").length;
	var dataArray = {};
	
	for(count = 0; count < rows; count++){
		var currentArray = {};
		
		currentArray['inspectorID'] = $("#inspectorRow" + count).children(".inspectorID").text();
		currentArray['firstName'] = $("#inspectorRow" + count).children(".firstName").text();
		currentArray['lastName'] = $("#inspectorRow" + count).children(".lastName").text();
		currentArray['email'] = $("#inspectorRow" + count).children(".email").text();
		currentArray['phone'] = $("#inspectorRow" + count).children(".phone").text();
		currentArray['cell'] = $("#inspectorRow" + count).children(".cell").text();
		dataArray[count] = currentArray;

	}
	
	var data = {"request" : "submitInspectorUpdate", "dataArray" : dataArray};
	var submitInspectorUpdate = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitInspectorUpdate.success(function(data){
		console.log('fin!');
		$("body > div").css("display", "none");
		$("#adminButtonContainer").fadeIn();
	});
	
	submitInspectorUpdate.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}
