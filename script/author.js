//Load Access control on page load.
$(document).ready(function(){
	getViews();
});

//MAC controllstuff
function getViews(){
	var data = {"request" : "admin"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'html'});
	
	accessControlCall.success(function(data){
		if(data.length > 0){
			preparePage();
		}
		else{
			$("#authorContainer").prepend("<h3>You do not have sufficient privileges access to this page</h3>");
		}
		
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}


//Tested: Works
function preparePage(){
    $("body > div").css("display", "none");
    $("#authorNavigation").fadeIn();
}

//Tested: Works
function showAuthorMenu(){
    $("body > div").css("display", "none");
    $("#authorNavigation").fadeIn();

}

//Tested: Works
function showAuthorAdd(){
    $("body > div").css("display", "none");
    $("#createAuthorContainer").fadeIn();

}
//Tested: Works
function showAuthorEdit(){
    $("body > div").css("display", "none");
    $("#editAuthorContainer").fadeIn();
}

//Tested: Works
function toMainMenu(){
    window.location.href = '/main.html';
}


//Function to submit author
function submitAuthor(){

	var name = document.getElementById("inpuAuthorName").value;
	var title = document.getElementById("inputAuthorTitle").value;
	var image = document.getElementById("inputAuthorImage").value;
	var description = document.getElementById("inputAuthorDescription").value;
	
	var data = {
		"request" : "submitAuthor",
		"name" : name,
		"title" : title,
		"image" : image,
		"description" : description
	};
        
        //For Debugging
	console.log(data);
	var submitAuthor = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitAuthor.success(function(data){
		alert('Author created.');
                showAuthorMenu();
	});
	
	submitAuthor.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}


//Pretty much the same as costing table,
//Delicate jQuery to make it all work, beware changing the table structure.
function prepareEditAuthorTable(){
	var data = {"request" : "getAuthors"};
	console.log(data);
	var getUsers = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	getUsers.success(function(data){
		$("#editAuthorTable tr:not(:first-child)").remove();
		for(count in data){
			var AuthorRowID = "authorRow" + count;
			$("#editAuthorTable").append("<tr id='" + authorRowID + "'></tr>");
			$("#" + authorRowID).append("<td class='authorID'>" + data[count]['authorId'] + "</td>");
			$("#" + authorRowID).append("<td class='name'>" + data[count]['name'] + "</td>");
			
			$("#" + authorRowID).append("<td class='email'>" + data[count]['Email'] + "</td>");
			$("#" + authorRowID).append("<td class='accessLevel'>" + data[count]['AccessLevel'] + "</td>");
			$("#" + authorRowID).append("<td><input type='button' class='editAuthorRow' value='Edit User'></input></td>");
		}
	});
	
	getUsers.complete(function(){
		$("#editAuthorContainer").fadeIn();
		
		$(".editAuthorRow").on("click", function(){
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