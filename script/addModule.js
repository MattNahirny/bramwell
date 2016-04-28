
var moduleAllLevels;


$(document).ready(function(){
	var data = {"request" : "addModule"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'json'});
	
	//slightly crummy way of doing MAC, but it at least prevents accidental stumbling on the page.
	accessControlCall.success(function(data){
		if(data["hasAccess"] == "true"){
			preparePage();
		}
		else{
			$("#createModuleContainer").empty();
			$("#createModuleContainer").append("<h3>You do not have sufficient access rights to use this page</h3>");
		}	
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
});

function preparePage(){

	loadDropDowns();

	//Function that delegates the onclick event to all the 'add comment' buttons
	//On being clicked, formats a unique ID for the input and appends it to the container
	//ID format is one of the following:
	//	physicalDescStandardComment#
	//	finAnalysisStandardComment#
	//	condAnalysisStandardComment#
	//	defAnalysisStandardComment#
	//
	//	These are also used during the submitting of the module to get all the input values
	
	$("#createModuleContainer").on("click", ".addCommentButton", function(){

		commentName = $(this).attr("name") + "StandardComment" + $(this).parent().siblings(".standardCommentContainer").children().size();
		$(this).parent().siblings(".standardCommentContainer").append("<div class='inputRow'><label>Standard Comment: </label><textarea type='text' id='" + commentName + "'></textarea></div>");
		console.log('test success!');

	});

	$("#createModuleContainer").on("click", ".createLevelButton", function(){
		
		//get what the sibling's id is, check what level it is, and either change the next 1 or two rows
		
		//this means that it's current text, switching to dropdowns

		if($(this).siblings("select").css("display") == "none"){
			var siblingID = $(this).siblings("select").attr("id");
			
			//break-less switch on purpose
			switch(siblingID){
				case "lvlOneDropDown":
					//hide the one, show the other
					$("#lvlOneTextInput").css("display", "none");
					$("#lvlOneDropDown").fadeIn();
					
				case "lvlTwoDropDown":
					$("#lvlTwoTextInput").css("display", "none");
					$("#lvlTwoDropDown").fadeIn();
					
				case "lvlThreeDropDown":
					$("#lvlThreeTextInput").css("display", "none");
					$("#lvlThreeDropDown").fadeIn();
			}
		}
		// this means input is unused, therefore switch from select to the input
		else if($(this).siblings("select").css('display') != "none"){
			var siblingID = $(this).siblings("select").attr("id");
			
			//break-less switch on purpose
			switch(siblingID){
				case "lvlOneDropDown":
					//hide the one, show the other
					$("#lvlOneDropDown").css("display", "none");
					$("#lvlOneTextInput").fadeIn();
					
				case "lvlTwoDropDown":
					$("#lvlTwoDropDown").css("display", "none");
					$("#lvlTwoTextInput").fadeIn();
					
				case "lvlThreeDropDown":
					$("#lvlThreeDropDown").css("display", "none");
					$("#lvlThreeTextInput").fadeIn();
			}
		}
	});
	
	
}
function loadDropDowns(){

	getModulesBasic = $.ajax({url : 'script/server.php', method: 'GET', data : {"request" : "getModulesBasic"}, dataType:'json'});
	
	getModulesBasic.success(function(data){
		moduleAllLevels = data;
		//data is in format data[module#][moduleInfoPiece]
		
		
		for(key in moduleAllLevels){
			if($("#lvlOneDropDown").children("[value='" + moduleAllLevels[key]['levelOneName'] + "']").length == 0){
				$("#lvlOneDropDown").append("<option value='" + moduleAllLevels[key]['levelOneName'] + "'>" + moduleAllLevels[key]['levelOneID'] + ": " + moduleAllLevels[key]['levelOneName'] + "</option>");
			}
		}
		
	});
	
	getModulesBasic.complete(function(){
		$("#lvlOneDropDown").on("change", function(){		
			$("#lvlTwoDropDown").empty();
			$("#lvlThreeDropDown").empty();
			$("#lvlTwoDropDown").append("<option value='default'>-</option>");
			$("#lvlThreeDropDown").append("<option value='default'>-</option>");
			for(key in moduleAllLevels){
				if($("#lvlOneDropDown").val() == moduleAllLevels[key]['levelOneName']){
				
					if($("#lvlTwoDropDown").children("[value='" + moduleAllLevels[key]['levelTwoName'] + "']").length == 0){
						$("#lvlTwoDropDown").append("<option value='" + moduleAllLevels[key]['levelTwoName'] + "'>" + moduleAllLevels[key]['levelTwoID'] + ": " + moduleAllLevels[key]['levelTwoName'] + "</option>");
					}
					
				}
			}
		});
		
		$("#lvlTwoDropDown").on("change", function(){		
			$("#lvlThreeDropDown").empty();
			$("#lvlThreeDropDown").append("<option value='default'>-</option>");
			for(key in moduleAllLevels){
				if($("#lvlTwoDropDown").val() == moduleAllLevels[key]['levelTwoName']){
				
					if($("#lvlThreeDropDown").children("[value='" + moduleAllLevels[key]['levelThreeName'] + "']").length == 0){
						$("#lvlThreeDropDown").append("<option value='" + moduleAllLevels[key]['levelThreeName'] + "'>" + moduleAllLevels[key]['levelThreeID'] + ": " + moduleAllLevels[key]['levelThreeName'] + "</option>");
					}
					
				}
			}
		});
	});
	
	getModulesBasic.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});

}


function submitNewModule(){

	var physicalDescStandardDesc = document.getElementById("physicalDescStndDesc").value;
	
	var physicalDescStandardComments = [];
	if(document.getElementById("physicalDescComments").children.length > 0){
		for(var x = 0; x < document.getElementById("physicalDescComments").children.length; x++){
			var currentID = "physicalDescStandardComment" + x;
			physicalDescStandardComments.push(document.getElementById(currentID).value);
		}	
	}
	else{
		physicalDescStandardComments.push("none");
	}
	
	var finAnalysisDefaultComment = document.getElementById("finAnalysisDefaultComment").value;
	var finAnalysisStandardComments = [];
	if(document.getElementById("finAnalysisComments").children.length){
		for(var x = 0; x < document.getElementById("finAnalysisComments").children.length; x++){
			var currentID = "finAnalysisStandardComment" + x;
			finAnalysisStandardComments.push(document.getElementById(currentID).value);
		}
	}
	else{
		finAnalysisStandardComments.push("none");
	}
	
	var potentDeteriorationDefaultComment = document.getElementById("potentDeteriorationDefaultComment").value;
	
	var condAnalysisDefaultComment = document.getElementById("condAnalysisDefaultComment").value;
	var condAnalysisStandardComments = [];
	if(document.getElementById("condAnalysisComments").children.length > 0){
		for(var x = 0; x < document.getElementById("condAnalysisComments").children.length; x++){
			var currentID = "condAnalysisStandardComment" + x;
			condAnalysisStandardComments.push(document.getElementById(currentID).value);
		}
	}
	else{
		condAnalysisStandardComments.push("none");
	}
	
	var defAnalysisDefaultComment = document.getElementById("defAnalysisDefaultComment").value;
	var defAnalysisStandardComments = [];
	if(document.getElementById("defAnalysisComments").children.length > 0){
		
		for(var x = 0; x < document.getElementById("defAnalysisComments").children.length; x++){
			var currentID = "defAnalysisStandardComment" + x;
			defAnalysisStandardComments.push(document.getElementById(currentID).value);
		}
	}
	else{
		defAnalysisStandardComments.push("none");
	}
	

	var lvlOne = getLevelValue("lvlOne");
	var lvlTwo = getLevelValue("lvlTwo");
	var lvlThree = getLevelValue("lvlThree");

	var name = document.getElementById("inputModuleName").value;
	var unitOfMeasure = document.getElementById("inputUnitOfMeasure").value;
	var costPerUnit = document.getElementById("inputCostPerUnit").value;
	var lifespan = document.getElementById("inputLifespan").value;
	
	var data = {
		"request" : "addModule",
		"physicalDescStandardDesc" : physicalDescStandardDesc,
		"physicalDescStandardComments" : physicalDescStandardComments,
		"finAnalysisDefaultComment" : finAnalysisDefaultComment,
		"finAnalysisStandardComments" : finAnalysisStandardComments,
		"potentDeteriorationDefaultComment" : potentDeteriorationDefaultComment,
		"condAnalysisDefaultComment" : condAnalysisDefaultComment,
		"condAnalysisStandardComments" : condAnalysisStandardComments,
		"defAnalysisDefaultComment" : defAnalysisDefaultComment,
		"defAnalysisStandardComments" : defAnalysisStandardComments,
		"lvlOne": lvlOne,
		"lvlTwo" : lvlTwo,
		"lvlThree" : lvlThree,
		"name" : name,
		"unitOfMeasure" : unitOfMeasure,
		"costPerUnit" : costPerUnit,
		"lifespan" :lifespan
	};
	console.log(data);
	var addModule = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	addModule.success(function(data){
		console.log('success');
		window.location.href = "main.html";
		
	});
	
	addModule.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

function getLevelValue(levelName){
	
	var dropDown = $("#" + levelName + "DropDown");
	var textInput = $("#" + levelName + "TextInput");
	var levelDropdownDisplay = dropDown.css("display");
	var levelTextInputDisplay = textInput.css("display");
	
	
	if( levelDropdownDisplay == "none" &&  levelTextInputDisplay != "none"){
		returnValue = textInput.val();
	}
	// if just drop down is visible
	else if(levelDropdownDisplay != "none" && levelTextInputDisplay == "none"){
		returnValue = dropDown.val();
	}
	//if neither
	else if(levelDropdownDisplay == "none" && levelTextInputDisplay == "none"){
		console.log("No " + levelName + " Value Chosen");
	}
	//if both
	else if(levelDropdownDisplay != "none" && levelTextInputDisplay != "none"){
		//prioritise text input if someone somehow does both
		returnValue = textInput.val();
	}
	return returnValue;
}
