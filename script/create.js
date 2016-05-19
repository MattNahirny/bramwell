var selectedModuleData;
var selectedModules = [];
var finishedModuleData = [];
var constructYear;

//needs to start true in order for system to work properly
var pageModified = true;

//On ready, do the media access control
//then prepare the page if they have access
$(document).ready(function(){

	var data = {"request" : "create"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'json'});
	
	//slightly crummy way of doing MAC, but it at least prevents accidental stumbling on the page.
	accessControlCall.success(function(data){
		if(data["hasAccess"] == "true"){
			preparePage();
		}
		else{
			$("#reportDetails").empty();
			$("#reportDetails").append("<h3>You do not have sufficient access rights to use this page</h3>");
		}	
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
});

//I really shouldn't have needed this
function toHex(str) {
    var result = '';
    for (var i=0; i<str.length; i++) {
      result += str.charCodeAt(i).toString(16);
    }
    return result;
 }
 
 //prepare page function, mostly event delegation
function preparePage(){

	// Event delegation: 
	// Sets the various titles of modules/submodules/individuals to expand or compress their children elements on click
	
	
	//Setting index/header phase-display up
	$("#currentPhase").text("Report Details");
	$("#reportIndex li").attr("class", "notCurrentIndex");
	$("#liReportDetails").attr("class", "currentIndex");
	
	//This operates the index for the report, lots of fades etc
	$("#reportIndex").on("click", "li", function(){
		switch($(this).attr("id")){
			case "liReportDetails":
				setPhase("reportDetails");
				break;
			case "liBuildingDetails":
				setPhase("buildingDetails");
				break;
			case "liBuildingTypeSelect":
				setPhase("buildingTypeSelect");
				break;
			case "liPropertyStatistics":
				setPhase("propertyStatistics");
				break;
			case "liConstructionInfo":
				setPhase("constructionInfo");
				break;
			case "liModuleSelection":
				setPhase("moduleSelection");
				break;
			case "liModuleConfirmation":
				//prevents people from jumping ahead to empty data
				console.log(selectedModuleData);
				if(selectedModuleData != undefined){
					setPhase("moduleConfirmation");
				}
				else{
					setPhase("moduleSelection");
				}
				break;
			case "liModule":
				if(selectedModuleData != undefined){
					setPhase("module");
				}
				else{
					setPhase("moduleSelection");
				}
				break;
		}
	});
	
	//Add image button functionality, clones the add image input
	$(".btnAddImage").on("click", function(){
		var submitForm = $(this).parent().siblings('.moduleChoiceGroup:first').clone();
		submitForm.insertBefore($(this));
		submitForm.find("input[name=caption]").val("");
	});
	//Submit image functionality
	$("#module").on("click", ".btnSubmitImage", function(){
		console.log('Submitting...');
		$(this).parent().parent("form").append("<div class='uploadStatus'>Uploading...</div>");
		var test = $(this).parent().parent("form");
		$(this).parent().parent("form").ajaxSubmit({complete: function(xhr){
			test.find(".uploadStatus").text(xhr.responseText);
			test.find(".uploadStatus").delay('3000').fadeOut();
			}
		});
		return false;
	});

	//Add inspector functionality, clones other inspector field
	$("#reportDetails").on("click", "#btnAddInspector", function(){
		$("#selectInspectorContainer").children("input").before($(".selectInspector:first").parent().clone());		
	});
	
	//Add inspector date of inspection, clones other date of inspection field
	$("#reportDetails").on("click", "#btnAddDate", function(){
		$("#dateOfInspectionContainer").children("input").before("<span><input type='date' class='inputDateOfInspect'></span>");
	});
	
	//This works the little arrows changing depending on if their contents are expanded or not.
	//Only works if you click the arrow itself :(
	$("#moduleSelection").on("click", ".moduleTitle span", function(){
		$(this).fadeOut("100", function(){
			if(toHex($(this).html()) == "25c0"){
				$(this).html("&#x25bc");
			}
			else{
				$(this).html("&#x25c0");
			}
		});
		$(this).fadeIn("100");
		$(this).parent().next('.moduleContainer').slideToggle();
	});
	$("#moduleSelection").on("click", ".groupTitle span", function(){
		$(this).fadeOut("100", function(){
			if(toHex($(this).html()) == "25c0"){
				$(this).html("&#x25bc");
			}
			else{
				$(this).html("&#x25c0");
			}
		});
		$(this).fadeIn("100");
		$(this).parent().next('.groupContainer').slideToggle();
	});
	$("#moduleSelection").on("click", ".individualTitle span", function(){
		$(this).fadeOut("100", function(){
			if(toHex($(this).html()) == "25c0"){
				$(this).html("&#x25bc");
			}
			else{
				$(this).html("&#x25c0");
			}
		});
		$(this).fadeIn("100");
		$(this).parent().siblings('.individualContainer').slideToggle();
	});

	$(".saveExitButton").on("mousedown", function(){
		window.onbeforeunload = null;
	});
	
	//Save exit functionality, calls the save function
	$(".saveExitButton").on("click", function(){
		//do save function!
		//probably going to need to check which event triggered the call, so we can save stage-appropriately
		saveExit();
		//window.location.href = "index.html";
	});
	
	//Loading files
	fillModuleTable();
	fillInspectorSelect();
	fillClientSelect();
	
	
	
	
	
	//Event delegation to switch between 'pages'
	$("#reportDetails").on("click", "#btnReportDetailsFinish", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("buildingDetails");
	});
	
	$("#buildingDetails").on("click", "#btnBuildingDetailsFinish", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("buildingTypeSelect");
	});
	$("#buildingDetails").on("click", "#btnBuildingDetailsBack", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("reportDetails");
	});
	
	$("#buildingTypeSelect").on("click", "#buildingSelectContainer input", function(){
		//be sure these are set to display none, if going to be doing fades.
		preparePropertyStatistics($(this).attr("id"));
		setPhase("propertyStatistics");
	});
	
	$("#buildingTypeSelect").on("click", "#btnBuildingTypeSelectBack", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("buildingDetails");
	});
	
	$("#propertyStatistics").on("click", "#btnPropertyStatsFin", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("constructionInfo");
		
	});
	
	$("#propertyStatistics").on("click", "#btnPropertyStatsBack", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("buildingTypeSelect");
	});
	
	//save state here
	$("#constructionInfo").on("click", "#btnConstructInfoFin", function(){
		//be sure these are set to display none, if going to be doing fades.
		if(pageModified){
			saveReport();
		}
		
		//used to set the next major button click to send the report data to dB (updating it)
		$("#reportDetails").on("change", "input", function(){
			pageModified = true;
			console.log("something changed!");
		
		});
		$("#buildingDetails").on("change", "input", function(){
			pageModified = true;
			console.log("something changed!");
		});
		$("#propertyStatistics").on("change", "input", function(){
			pageModified = true;
			console.log("something changed!");
		});
		
		setPhase("moduleSelection");
	});
	

	$("#constructionInfo").on("click", "#btnConstructInfoBack", function(){
		setPhase("propertyStatistics");
	});
	
	//makes the text input fields fill based on the selected dropdown field	
	$("#constructionInfo").on("change", ".choiceDropDown", function(){
			if($(this).children(":selected").val() != "writeOwn"){
				$(this).parent().siblings("div").children("textarea").text($(this).children(":selected").val());
			}
			$(this).parent().siblings("div").fadeIn();
	});
	
	$("#moduleSelection").on("click", "#btnModuleSelectFin", function(){
		//be sure these are set to display none, if going to be doing fades.
		$("#moduleSelection").css("display", "none");
		$("#currentPhase").text("Component Confirmation");
		$("#reportIndex li").attr("class", "notCurrentIndex");
		$("#liModuleConfirmation").attr("class", "currentIndex");
		prepareModules(); //downloads module info
		prepareModuleConfirm(); //fills confirmation table
		
	});
	
	$("#moduleSelection").on("click", "#btnModuleSelectBack", function(){
		//be sure these are set to display none, if going to be doing fades.
		setPhase("constructionInfo");
	});
	
	//makes the text input fields fill based on the selected dropdown field
	$("#module").on("change", ".choiceDropDown", function(){
		if($(this).children(":selected").text() != "Write Own..."){
			$(this).parent().siblings("div").children("textarea").text($(this).children(":selected").text());
		}
		$(this).parent().siblings("div").fadeIn();
	});
	
	//event delegation to make selecting a drop down set the text to be in the comment box
	
	
	//save state
	$("#moduleConfirmation").on("click", "#btnModuleConfirmFin", function(){
		//be sure these are set to display none, if going to be doing fades.
		$("#moduleConfirmation").css("display", "none");
		
		$("#currentPhase").text("Component Details");
		$("#reportIndex li").attr("class", "notCurrentIndex");
		$("#liModule").attr("class", "currentIndex");
		if(pageModified){
			saveReport();
		}
		createModule(true);
	
	});
	
	$("#moduleConfirmation").on("click", "#btnModuleConfirmBack", function(){
		//be sure these are set to display none, if going to be doing fades.
		$("#moduleConfirmation").css("display", "none");
		$("#reportIndex li").attr("class", "notCurrentIndex");
		$("#liModuleSelection").attr("class", "currentIndex");
		$("#moduleSelection").fadeIn();
	});
	
	$("#module").on("click", "#btnModuleFin", function(){
		//submit report details
		$("#module").css("display", "none");
		
		submitModule();
		
		if(pageModified){
			saveReport();
		}
	});
	
	$("#module").on("click", "#btnModuleBack", function(){
		$("#reportIndex li").attr("class", "notCurrentIndex");
		$("#liModuleConfirmation").attr("class", "currentIndex");
		preparePreviousModule();
	});
	
	//Minor code to make it look nicer

	$("#module").on("click", "#btnSubmitImage", function(){
		console.log('Submitting...');
		$(this).parent("form").ajaxSubmit({complete: function(xhr){
		console.log(xhr.responseText)}
		});
		return false;
	});
	
	//Fills the certain fields based on input (if the units entered changes, recalculates cost)
	$("#module").on("focusout", "#inputUnits", function(){
		var cost = document.getElementById("inputUnits").value * document.getElementById("defaultCostEstimate").innerText;
		document.getElementById("defaultRepairCost").innerText = cost.toFixed(2);
	});
	
	$("#module").on("focusout", "#inputEffectiveAge", function(){
		if($("#inputEffectiveAge").val() > parseInt(document.getElementById("defaultExpectedLifespan").innerText)){
			$("#inputEffectiveAge").val(parseInt(document.getElementById("defaultExpectedLifespan").innerText));
		}
	});
	//Calculates certain fields based on changed input (when date of acquisition changes, sets effective age and remaining life)
	$("#module").on("focusout", "#inputDateOfAquisition", function(){
		var currentYear = new Date().getFullYear();
		
		document.getElementById("inputDateOfAquisition").value;
		document.getElementById("inputEffectiveAge").value = currentYear - document.getElementById("inputDateOfAquisition").value;
		document.getElementById("defaultRemainingAge").textContent =  parseInt(document.getElementById("inputDateOfAquisition").value) - parseInt(currentYear) + parseInt(document.getElementById("defaultExpectedLifespan").innerText);
	});
	//When unit of measure changes, set the displayed fields to match it
	$("#module").on("change", "#inputUnitOfMeasurement", function(){
		if($(this).val() == "unique"){
			$(this).replaceWith("<input type='text' id='inputUnitOfMeasurement'>");
		}
		$("#unitOfMeasurementUnits").text($(this).val());
		$("#unitOfMeasurementCost").text($(this).val());
	});
	
	window.onbeforeunload = function (e) {
		finishReport();
		//return "Continuing will delete the current report. \n\nIf you need to close the report to resume later, use the save and exit button.";
		//this might trigger even if they say 'no, stay on page'
		
		//deleteCurrentReport();
	};
}

// function for operating the switching between phases
function setPhase(phase){
	switch(phase){
		case "reportDetails":
			$("body > div:not(#reportHeader, #reportIndex)").fadeOut();
			$("#currentPhase").text("Report Details");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liReportDetails").attr("class", "currentIndex");
			$("#reportDetails").fadeIn();
			break;
		case "buildingDetails":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Building Details");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liBuildingDetails").attr("class", "currentIndex");
			$("#buildingDetails").fadeIn();
			break;
		case "buildingTypeSelect":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Building Type Selection");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liBuildingTypeSelect").attr("class", "currentIndex");
			$("#buildingTypeSelect").fadeIn();
			break;
		case "propertyStatistics":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Property Statistics");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liPropertyStatistics").attr("class", "currentIndex");
			$("#propertyStatistics").fadeIn();
			break;
		case "constructionInfo":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Construction Info");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liConstructionInfo").attr("class", "currentIndex");
			$("#constructionInfo").fadeIn();
			break;
		case "moduleSelection":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Component Selection");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liModuleSelection").attr("class", "currentIndex");
			$("#moduleSelection").fadeIn();
			break;
		case "moduleConfirmation":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Component Confirmation");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liModuleConfirmation").attr("class", "currentIndex");
			$("#moduleConfirmation").fadeIn();
			break;
		case "module":
			$("body > div:not(#reportHeader, #reportIndex)").css("display", "none");
			$("#currentPhase").text("Component Details");
			$("#reportIndex li").attr("class", "notCurrentIndex");
			$("#liModule").attr("class", "currentIndex");
			if(pageModified){
				saveReport();
			}
			createModule(true);
			break;
	
	}

}

//Downloads modules and assigns it to global variables
function prepareModules(){

	selectedModules = [];
	var checkBoxes = document.getElementsByClassName("sub-elementCheckbox");
	for(var x = 0; x < checkBoxes.length; x++){
		if(checkBoxes[x].checked){
			selectedModules.push(checkBoxes[x].value);
		}
	}
	$("#btnModuleConfirmFin").css("display", "none");
	var data = {"request" : "getModulesFromList", "moduleList" : selectedModules};
	var getModules = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getModules.success(function(data){
		selectedModuleData = data;		
		$("#btnModuleConfirmFin").fadeIn();
	});
	
	getModules.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Fills inspector dropdowns from database data
function fillInspectorSelect(){
	var data = {"request" : "getInspectors"};
	var getInspectors = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getInspectors.success(function(data){

		for(count in data){
			$(".selectInspector").append("<option value='" + data[count]['InspectorId'] + "'>" + data[count]['InspectorId'] + ": " + data[count]['FirstName'] + "</option>");
		}
	});
	
	getInspectors.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

//Fills client dropdowns from database data
function fillClientSelect(){
	var data = {"request" : "getClients"};
	var getClients = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getClients.success(function(data){

		for(count in data){
			$("#selectClient").append("<option value='" + data[count]['ClientId'] + "'>" + data[count]['ClientId'] + ": " + data[count]['CompanyName'] + "</option>");
		}
	});
	
	getClients.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}
//Fills module table from database data
function fillModuleTable(){
	var data = {"request" : "getModulesBasic"};
	var getModules = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getModules.success(function(data){
		//for loop, check if id exists, if not, place it
		var count;
		for(count in data){
			var currentLevelOneID = data[count]['levelOneID'];			
			var currentLevelTwoID = data[count]['levelTwoID'];
			var currentLevelThreeID = data[count]['levelThreeID'];
			var currentLevelFourID = data[count]['levelFourID'];
			
			//Places them within appropriate parents, such as if it's parent catagory exists, place this module within it. Else, create the parent
			if($("#levelOneID" + currentLevelOneID + "Container").length == 0){
				$("#moduleSelectTable").append("<div class='moduleTitle' id='levelOneID" + currentLevelOneID + "Title'><span>&#9664</span>"+  data[count]['levelOneName'] + "</div>");
				$("#moduleSelectTable").append("<div class='moduleContainer' id='levelOneID" + currentLevelOneID + "Container'></div>");
			}
			if($("#levelTwoID" + currentLevelTwoID + "Container").length == 0){
				$("#levelOneID" + currentLevelOneID + "Container").append("<div class='groupTitle' id='levelTwoID" + currentLevelTwoID + "Title'><span>&#9664</span>"+  data[count]['levelTwoName'] + "</div>");
				$("#levelOneID" + currentLevelOneID + "Container").append("<div class='groupContainer' id='levelTwoID" + currentLevelTwoID + "Container'></div>");
			}
			if($("#levelThreeID" + currentLevelThreeID + "Container").length == 0){
				$("#levelTwoID" + currentLevelTwoID + "Container").append("<div class='individualTitle' id='levelThreeID" + currentLevelThreeID + "Title'><span>&#9664</span>"+  data[count]['levelThreeName'] + "</div>");
				$("#levelTwoID" + currentLevelTwoID + "Container").append("<div class='individualContainer' id='levelThreeID" + currentLevelThreeID + "Container'></div>");
			}
			if($("#levelFourID" + currentLevelFourID + "Container").length == 0){
				$("#levelThreeID" + currentLevelThreeID + "Container").append("<div class='sub-elementTitle' id='levelFourID" + currentLevelFourID + "Title'>"+  data[count]['levelFourName'] + "<input class='sub-elementCheckbox' type='checkbox' value='" + currentLevelFourID + "'></div>");
				//$("#levelThreeID" + currentLevelThreeID + "Container").append("<input class='sub-elementCheckbox' type='checkbox' value='" + currentLevelFourID + "'>");
			}
			
		}
		
		
	});
	
	getModules.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}


//Fills the property statistics based on which kind of property was selected
//Depending on which, sets the optional fields to be either enabled or not
function preparePropertyStatistics(buttonID){
	$("#propertyStatistics input[type=checkbox]").prop("checked", false);
	$("#propertyStatistics input[type=checkbox]").parent().siblings().children("input").prop("disabled", true);
	switch(buttonID){
		case "btnFlatLand":
			prepareConstructionInfo("flatland");
			break;
			
		case "btnComplex":
			prepareConstructionInfo("complex");
			$("#inputServices").parent().siblings("label").children().prop("checked", true);
			$("#inputServices").prop('disabled', false);
			$("#inputPlayGround").parent().siblings("label").children().prop("checked", true);
			$("#inputPlayGround").prop('disabled', false);
			$("#inputLandscapedArea").parent().siblings("label").children().prop("checked", true);
			$("#inputLandscapedArea").prop('disabled', false);
			$("#inputFencing").parent().siblings("label").children().prop("checked", true);
			$("#inputFencing").prop('disabled', false);
			$("#inputDriveParkArea").parent().siblings("label").children().prop("checked", true);
			$("#inputDriveParkArea").prop('disabled', false);
			$("#inputWoodDeck").parent().siblings("label").children().prop("checked", true);
			$("#inputWoodDeck").prop('disabled', false);
			$("#inputRoadways").parent().siblings("label").children().prop("checked", true);
			$("#inputRoadways").prop('disabled', false);
			$("#inputCurbs").parent().siblings("label").children().prop("checked", true);
			$("#inputCurbs").prop('disabled', false);
			$("#inputExteriorLighting").parent().siblings("label").children().prop("checked", true);
			$("#inputExteriorLighting").prop('disabled', false);
			break;
			
		case "btnTownHome":
			prepareConstructionInfo("townhouse");
			$("#inputFencing").parent().siblings("label").children("input").prop("checked", true);
			$("#inputFencing").prop('disabled', false);
			$("#inputDriveParkArea").parent().siblings("label").children().prop("checked", true);
			$("#inputDriveParkArea").prop('disabled', false);
			$("#inputWoodDeck").parent().siblings("label").children().prop("checked", true);
			$("#inputWoodDeck").prop('disabled', false);
			$("#inputRoadways").parent().siblings("label").children().prop("checked", true);
			$("#inputRoadways").prop('disabled', false);
			$("#inputCurbs").parent().siblings("label").children().prop("checked", true);
			$("#inputCurbs").prop('disabled', false);
			$("#inputExteriorLighting").parent().siblings("label").children().prop("checked", true);
			$("#inputExteriorLighting").prop('disabled', false);
			
			break;
		case "btnCustom":
			prepareConstructionInfo("custom");
			$("#propertyStatistics input[type=checkbox]").prop("checked", true);
			$("#propertyStatistics input[type=checkbox]").parent().siblings().children("input").prop("disabled", false);
			break;
	}

	
	$("#propertyStatistics").on("change", "input[type=checkbox]", function(){

		if($(this).prop("checked") == false){
			$(this).parent().siblings().find("input").prop("disabled", true);

			
		}
		else if($(this).prop("checked") == true){
			$(this).parent().siblings().find("input").prop("disabled", false);
		}
	});
	
}
//Fills the construction info dropdowns based on database info
function prepareConstructionInfo(buildingType){
	var data = {"request" : "getConstructionInfo", "buildingType" : buildingType};
	var getConstructionInfo = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	getConstructionInfo.success(function(data){
		console.log(data);
		//for loop, check if id exists, if not, place it
		$("#constructionInfo select").empty();
		var count;

		$("#selectOverview").append("<option value=''>-</option>");
		$("#selectFoundations").append("<option value=''>-</option>");
		$("#selectSubstructure").append("<option value=''>-</option>");
		$("#selectExterior").append("<option value=''>-</option>");
		$("#selectRoofDrainage").append("<option value=''>-</option>");
		$("#selectAmenities").append("<option value=''>-</option>");
		$("#selectElectrical").append("<option value=''>-</option>");
		$("#selectServices").append("<option value=''>-</option>");
					
		for(count in data){
			//Trims the displayed value to be less than 100 characters
			if(data[count]['Comment'].length > 100){
				var display = data[count]['Comment'].substring(0, 100) + "...";
			}
			else{
				display = data[count]['Comment'];
			}
			switch(data[count]['InfoType']){
				case "overview":
					$("#selectOverview").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "foundations":
					$("#selectFoundations").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "substructure":
					$("#selectSubstructure").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "exterior":
					$("#selectExterior").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "roof":
					$("#selectRoofDrainage").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "amenities":
					$("#selectAmenities").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "electrical":
					$("#selectElectrical").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
				case "services":
					$("#selectServices").append("<option value='" + data[count]['Comment'] + "'>" + display + "</option>");
					break;
			}
		}
		$("#selectOverview").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectFoundations").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectSubstructure").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectExterior").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectRoofDrainage").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectAmenities").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectElectrical").append("<option value='writeOwn'>Write Own...</option>");
		$("#selectServices").append("<option value='writeOwn'>Write Own...</option>");
	});
	
	getConstructionInfo.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}
//Prepares the confirmation page, just displays the selected ones on a page
function prepareModuleConfirm(){
	selectedModulesString = [];
	var checkBoxes = document.getElementsByClassName("sub-elementCheckbox");
	for(var x = 0; x < checkBoxes.length; x++){
		
		if(checkBoxes[x].checked){
			var levelFourID = (checkBoxes[x].parentNode.innerText);
			var levelThreeID = checkBoxes[x].parentNode.parentNode.previousSibling.innerText;
			levelThreeID = levelThreeID.substring(1, levelThreeID.length);
			var levelTwoID = checkBoxes[x].parentNode.parentNode.parentNode.previousSibling.innerText;
			levelTwoID = levelTwoID.substring(1, levelTwoID.length);
			var levelOneID = checkBoxes[x].parentNode.parentNode.parentNode.parentNode.previousSibling.innerText;
			levelOneID = levelOneID.substring(1, levelOneID.length);
			selectedModulesString.push(levelOneID + " -> " + levelTwoID + " -> " + levelThreeID + " -> " + levelFourID);
			
		}
	}
	$("#moduleConfirmContainer").empty();
	for(count in selectedModulesString){
		$("#moduleConfirmContainer").append("<div id='chosenModule'>" + selectedModulesString[count] + "</div>");
	}
	$("#moduleConfirmation").fadeIn();
}

//Filling in all the appropriate default data, then showing the module for an inspector to work on
function createModule(firstTime){
	//Gets the current module index
	var currentModuleIndex = Number($("#module").attr("data-moduleIndex"));
	
	//clears the form
	$("#moduleForm").trigger("reset");
	$("#module textarea").text(" ");
	
	//if this is not the first time this function has been run, it immidately increases the index by one.
	if(firstTime == false){
		currentModuleIndex += 1;
		//Checks to see if there is any data that was filled in, whether in database if continuing or just stored while working on it
		createModuleNonStandard();
	}
	
	$(".imageLevelFourValue").val(selectedModuleData[currentModuleIndex]['LevelFourId']);
	$("#moduleName").text(selectedModuleData[currentModuleIndex]['Name']);
	$("#defaultExpectedLifespan").text(selectedModuleData[currentModuleIndex]['ExpectedLifespan']);

	//change this once the database takes just a year and not all this split string nonsense
	
	document.getElementById("inputDateOfAquisition").value = constructYear.split("-")[0];
	var currentYear = new Date().getFullYear();
	document.getElementById("inputEffectiveAge").value = currentYear - constructYear.split("-")[0];
	document.getElementById("defaultRemainingAge").textContent =  parseInt(constructYear.split("-")[0]) - parseInt(currentYear) + parseInt(selectedModuleData[currentModuleIndex]['ExpectedLifespan']);
	$("#defaultCostEstimate").text(selectedModuleData[currentModuleIndex]['Cost']);
	
	$("#unitOfMeasurementUnits").text(selectedModuleData[currentModuleIndex]['UnitOfMeasure']);
	$("#unitOfMeasurementCost").text(selectedModuleData[currentModuleIndex]['UnitOfMeasure']);
	
	
	
	$("#physicalDescriptionDefault").text(selectedModuleData[currentModuleIndex]['DefPhysicalCondition']);
	$("#physicalDescriptionDropDown").empty();
	$("#physicalDescriptionDropDown").append("<option value='default'>-</option>");
	for(key in selectedModuleData[currentModuleIndex]['physicalDescStandardComments']){
			$("#physicalDescriptionDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['physicalDescStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['physicalDescStandardComments'][key]['comment'] + "</option>");
	};
	$("#physicalDescriptionDropDown").append("<option value='writeOwn'>Write Own...</option>");
	
	$("#financialAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefFinancialAnalysis']);
	$("#financialAnalysisDropDown").empty();
	$("#financialAnalysisDropDown").append("<option value='default'>-</option>");
	for(key in selectedModuleData[currentModuleIndex]['finAnalysisStandardComments']){
			$("#financialAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['finAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['finAnalysisStandardComments'][key]['comment'] + "</option>");
	};
	$("#financialAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
	
	$("#potentialDeteriorationDefault").text(selectedModuleData[currentModuleIndex]['DefPotentialDeterioration']);
	
	$("#conditionAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefConditionAnalysis']);
	$("#conditionAnalysisDropDown").empty();
	$("#conditionAnalysisDropDown").append("<option value='default'>-</option>");
	for(key in selectedModuleData[currentModuleIndex]['condAnalysisStandardComments']){
			$("#conditionAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['condAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['condAnalysisStandardComments'][key]['comment'] + "</option>");
	};
	$("#conditionAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
	
	$("#deficiencyAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefDeficiencyAnalysis']);
	$("#deficiencyAnalysisDropDown").empty();
	$("#deficiencyAnalysisDropDown").append("<option value='default'>-</option>");
	for(key in selectedModuleData[currentModuleIndex]['defAnalysisStandardComments']){
			$("#deficiencyAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['defAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['defAnalysisStandardComments'][key]['comment'] + "</option>");
	};
	$("#deficiencyAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
	
	$("#inputUnits").val("0");
	$("#inputUnitOfMeasurement").val(selectedModuleData[currentModuleIndex]['UnitOfMeasure']);

	document.getElementById("defaultRepairCost").innerText = "0";
	
	
	
	$("#module").fadeIn();
	
	//sets the module to keep track of the moduleIndex
	//Perhaps this should just be stored as a global variable
	$("#module").attr("data-moduleIndex", currentModuleIndex);
	
}



//Fills in non-standard info, in the case that you are continuing a report, or are just going back a page, and it should have all the previously set values there
function createModuleNonStandard(){
	var currentModuleIndex = Number($("#module").attr("data-moduleIndex"))
	


	for(count = 0; count < finishedModuleData.length; count++){

		if(finishedModuleData[count]['levelFourID'] == selectedModuleData[currentModuleIndex]['LevelFourId']){
			console.log('found it');
			$("#physicalDescriptionUnique").text(finishedModuleData[count]['physCondition']);
			$("#financialAnalysisUnique").text(finishedModuleData[count]['finAnalysis']);
			$("#conditionAnalysisUnique").text(finishedModuleData[count]['condAnalysis']);
			$("#deficiencyAnalysisUnique").text(finishedModuleData[count]['defAnalysis']);
			
			$("#inputUnits").val(finishedModuleData[count]['units']);
			$("#inputDateOfAquisition").val(finishedModuleData[count]['dateAcquired']);
			$("#inputEffectiveAge").val(finishedModuleData[count]['effectiveAge']);
			$("#inputUnitOfMeasurement").val(finishedModuleData[count]['unitOfMeasure']);
		
		}
	}
}

//Same as creating a component, but using a different index method.
//This might be able to be combined with the other create function
function preparePreviousModule(){
	//get previous selected module index, also set index -1 at end
	//get info from dB, fill in fields.
	
	var currentModuleIndex = Number($("#module").attr("data-moduleIndex"))
	if(currentModuleIndex > 0){
		currentModuleIndex -= 1;
		
		$("#module input[type=text").val("");
		$("#moduleName").text(selectedModuleData[currentModuleIndex]['Name']);
		$("#defaultExpectedLifespan").text(selectedModuleData[currentModuleIndex]['ExpectedLifespan']);
		
		//make autofill in from the date given in the details section (date building acquired)
		
		//change this once the database takes just a year and not all this split string nonsense
		document.getElementById("inputDateOfAquisition").value = constructYear;
		var currentYear = new Date().getFullYear();
		document.getElementById("inputEffectiveAge").value = currentYear - constructYear;
		document.getElementById("defaultRemainingAge").textContent =  parseInt(constructYear) - parseInt(currentYear) + parseInt(selectedModuleData[currentModuleIndex]['ExpectedLifespan']);
		$("#defaultCostEstimate").text(selectedModuleData[currentModuleIndex]['Cost']);
		
		$("#unitOfMeasurementUnits").text(selectedModuleData[currentModuleIndex]['UnitOfMeasure']);
		$("#unitOfMeasurementCost").text(selectedModuleData[currentModuleIndex]['UnitOfMeasure']);
		
		
		
		$("#physicalDescriptionDefault").text(selectedModuleData[currentModuleIndex]['DefPhysicalCondition']);
		$("#physicalDescriptionDropDown").empty();
		$("#physicalDescriptionDropDown").append("<option value='default'>-</option>");
		for(key in selectedModuleData[currentModuleIndex]['physicalDescStandardComments']){
				$("#physicalDescriptionDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['physicalDescStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['physicalDescStandardComments'][key]['comment'] + "</option>");
		};
		$("#physicalDescriptionDropDown").append("<option value='writeOwn'>Write Own...</option>");
		
		$("#financialAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefFinancialAnalysis']);
		$("#financialAnalysisDropDown").empty();
		$("#financialAnalysisDropDown").append("<option value='default'>-</option>");
		for(key in selectedModuleData[currentModuleIndex]['finAnalysisStandardComments']){
				$("#financialAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['finAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['finAnalysisStandardComments'][key]['comment'] + "</option>");
		};
		$("#financialAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
		
		$("#potentialDeteriorationDefault").text(selectedModuleData[currentModuleIndex]['DefPotentialDeterioration']);
		
		$("#conditionAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefConditionAnalysis']);
		$("#conditionAnalysisDropDown").empty();
		$("#conditionAnalysisDropDown").append("<option value='default'>-</option>");
		for(key in selectedModuleData[currentModuleIndex]['condAnalysisStandardComments']){
				$("#conditionAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['condAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['condAnalysisStandardComments'][key]['comment'] + "</option>");
		};
		$("#conditionAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
		
		$("#deficiencyAnalysisDefault").text(selectedModuleData[currentModuleIndex]['DefDeficiencyAnalysis']);
		$("#deficiencyAnalysisDropDown").empty();
		$("#deficiencyAnalysisDropDown").append("<option value='default'>-</option>");
		for(key in selectedModuleData[currentModuleIndex]['defAnalysisStandardComments']){
				$("#deficiencyAnalysisDropDown").append("<option value='" + selectedModuleData[currentModuleIndex]['defAnalysisStandardComments'][key]['StndCommentId'] + "'>" + selectedModuleData[currentModuleIndex]['defAnalysisStandardComments'][key]['comment'] + "</option>");
		};
		$("#deficiencyAnalysisDropDown").append("<option value='writeOwn'>Write Own...</option>");
		
		$("#inputUnits").val("0");
		document.getElementById("defaultRepairCost").innerText = "0";
		
		$("#module").fadeIn();
		

		$("#module").attr("data-moduleIndex", currentModuleIndex);
		createModuleNonStandard();
	}
	else{
		$("#module").css("display", "none");
		$("#moduleConfirmation").fadeIn();
	}
}

//Saving a report, so submitting all details to be either updated or added to the database
function saveReport(){
	//var datePattern = new RegExp('\d\d\d\d-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])');
	var postalPattern = new RegExp("[a-z][0-9][a-z](-|\s|)[0-9][a-z][0-9]", "i");

	var strataNumber = document.getElementById("inputStrataNumber").value;
	var clientID = document.getElementById("selectClient").value;
	var inspectorArray = [];
	for(count = 0; count < (document.getElementsByClassName("selectInspector").length); count ++){
		inspectorArray.push(document.getElementsByClassName("selectInspector")[count].value);
	}
	
	var inspectionDateArray = [];
	for(count = 0; count < (document.getElementsByClassName("inputDateOfInspect").length); count ++){
		inspectionDateArray.push(document.getElementsByClassName("inputDateOfInspect")[count].value);
	}
	var effectiveDate = document.getElementById("inputEffectiveDate").value;
	
	//I think someone made these names for some reason, leaving for support reasons
	var strataPlans = document.getElementById("inputStrataPlans").value;
	var plansScheduleDetails = document.getElementById("inputPlansScheduleDetails").value;
	var sitePlans = document.getElementById("inputSitePlans").value;
	
	var materialGiven = document.getElementById("inputMaterialGiven").value;
	
	var strataName = document.getElementById("inputStrataName").value;
	var street = document.getElementById("inputStreet").value;
	var city = document.getElementById("inputCity").value;
	var postalCode = document.getElementById("inputPostalCode").value;
	var location = document.getElementById("inputLocation").value;
	var constructType = document.getElementById("inputConstructType").value;
	var strataRegDate = document.getElementById("inputStrataRegDate").value;
	constructYear = document.getElementById("inputConstructYear").value;
	
	var numberOfLvl = document.getElementById("inputNumberOfLvl").value;

	


	var siteArea = document.getElementById("inputSiteArea").value;
	var siteCoverage = document.getElementById("inputSiteCoverage").value;
	var restrictedCovenant = document.getElementById("inputRestrictedCovenant").value;
	var residentialLots = document.getElementById("inputResidentialLots").value;
	var commercialLots = document.getElementById("inputCommercialLots").value;
	var complexOwnedLots = document.getElementById("inputComplexOwnedLots").value;
	var numberBuildings = document.getElementById("inputNumberBuildings").value;
	var buildingHeight = document.getElementById("inputBuildingHeight").value;
	
	var serviceArray = [];
	$(".optionalService").each(function(){
		//Checking for if the service was enabled
		if($(this).parent().siblings().find("input[type=checkbox]").prop("checked") == true){
			var currentArray = {};
			//checking if the service is one of the provided ones...
			if($(this).parent().siblings().find("input[type=text]").length == 0){
				currentArray['serviceName'] = $(this).parent().siblings("label").text();
			}
			// or one that was written by hand
			else{
				currentArray['serviceName'] = $(this).parent().siblings().find("input[type=text]").val();
			}
			
			currentArray['serviceValue'] = $(this).val();
			serviceArray.push(currentArray);
		}
	});
	
	var constOverview = document.getElementById("inputConstOverview").value;
	var constFoundations = document.getElementById("inputConstFoundations").value;
	var constSubstructure = document.getElementById("inputConstSubstructure").value;
	var constExterior = document.getElementById("inputConstExterior").value;
	var constRoofDrainage = document.getElementById("inputConstRoofDrainage").value;
	var constAmenities = document.getElementById("inputConstAmenities").value;
	var constElectrical = document.getElementById("inputConstElectrical").value;
	var constServices = document.getElementById("inputConstServices").value;
	
	$("#strataNumber").text(strataNumber);
	$("#strataName").text(strataName);
	
	if(postalPattern.test(postalCode) && isNumeric(numberOfLvl) && isNumeric(siteArea) && isNumeric(siteCoverage) && isNumeric(residentialLots) && isNumeric(commercialLots) && isNumeric(complexOwnedLots) && isNumeric(buildingHeight) && isNumeric(constructYear)){
	
		$("#reportDetails").fadeOut();

	
	
	var numberBuildings = document.getElementById("inputNumberBuildings").value;
	var buildingHeight = document.getElementById("inputBuildingHeight").value;
	
		var data = {"request" : "submitDetails",
		"strataNumber" : strataNumber,
		"strataName" : strataName,
		"clientID" : clientID,
		"inspectorArray" : inspectorArray,
		"inspectionDateArray" : inspectionDateArray,
		"effectiveDate" : effectiveDate,
		"materialGiven" : materialGiven,
		
		
		"street" : street,
		"city" : city,
		"postalCode" : postalCode,
		"location" : location,
		"constructType" : constructType,
		"strataRegDate" : strataRegDate,
		"constructYear" : constructYear,
		"numberOfLvl" : numberOfLvl,
		
		"strataPlans" : strataPlans,
		"plansScheduleDetails" : plansScheduleDetails,
		"sitePlans" : sitePlans,
		
		"siteArea" : siteArea,
		"siteCoverage" : siteCoverage,
		"restrictedCovenant" : restrictedCovenant,
		"residentialLots" : residentialLots,
		"commercialLots" : commercialLots,
		"complexOwnedLots" : complexOwnedLots,
		"numberBuildings" : numberBuildings,
		"buildingHeight" : buildingHeight,
		"serviceArray" : serviceArray,
		
		"constOverview" : constOverview,
		"constFoundations" : constFoundations,
		"constSubstructure" : constSubstructure,
		"constExterior" : constExterior,
		"constRoofDrainage" : constRoofDrainage,
		"constAmenities" : constAmenities,
		"constElectrical" : constElectrical,
		"constServices" : constServices
		};
		console.log(data);
		var submitDetails = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
		submitDetails.success(function(data){
			pageModified = false;
			console.log(data);
		});
		
		submitDetails.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: '  + jqXHR + " " + textStatus + " " + errorThrown);
		});
	}
	else{
		window.alert("There are invalid entries in the fields");
	}
	
};
//submitting an individual component
function submitModule(){
	
	var isNumberPattern = new RegExp("[0-9]");
	
	var dateAcquired = document.getElementById("inputDateOfAquisition").value;
	var units = document.getElementById("inputUnits").value;
	

	if(document.getElementById("physicalDescriptionUnique").value == "" || document.getElementById("physicalDescriptionUnique").value == null || document.getElementById("physicalDescriptionUnique").value == " "){
		var physCondition = $("#physicalDescriptionDefault").text();
	}
	else{
		var physCondition = document.getElementById("physicalDescriptionUnique").value;
	}
	
	if(document.getElementById("conditionAnalysisUnique").value == "" || document.getElementById("conditionAnalysisUnique").value == null || document.getElementById("conditionAnalysisUnique").value == " "){
		var condAnalysis = $("#conditionAnalysisDefault").text();
	}
	else{
		var condAnalysis = document.getElementById("conditionAnalysisUnique").value;
	}
	
	if($("#deficiencyAnalysisUnique").val() == "" || $("#deficiencyAnalysisUnique").val() == null || $("#deficiencyAnalysisUnique").val() == " "){
		var defAnaylsis = $("#deficiencyAnalysisDefault").text();
	}
	else{
		var defAnalysis = document.getElementById("deficiencyAnalysisUnique").value;
	}
	
	if(document.getElementById("financialAnalysisUnique").value == "" || document.getElementById("financialAnalysisUnique").value == null || document.getElementById("financialAnalysisUnique").value == " "){
		var finAnalysis = $("#financialAnalysisDefault").text();
	}
	else{
		var finAnalysis = document.getElementById("financialAnalysisUnique").value;
	}
	
	var currentModuleIndex = $("#module").attr("data-moduleIndex");
	var levelFourID = selectedModuleData[currentModuleIndex]['LevelFourId'];
	var unitOfMeasure = document.getElementById("inputUnitOfMeasurement").value;
	var effectiveAge = document.getElementById("inputEffectiveAge").value;
	
	//This is storing the data so that going back a step will have something to pull from without making a database call
	finishedModuleData.push({"levelFourID" : levelFourID, "unitOfMeasure" : unitOfMeasure, "effectiveAge" : effectiveAge, "dateAcquired" : dateAcquired, "units" : units, "physCondition" : physCondition, "condAnalysis" : condAnalysis, "defAnalysis" : defAnalysis, "finAnalysis" : finAnalysis});
	
	for(count = 0; count < finishedModuleData.length; count++){
		if(finishedModuleData[count]['levelFourID'] == levelFourID){
			finishedModuleData[count]['physCondition'] = physCondition;
			finishedModuleData[count]['finAnalysis'] = finAnalysis;
			finishedModuleData[count]['condAnalysis'] = condAnalysis;
			finishedModuleData[count]['defAnalysis'] = defAnalysis;
			
			finishedModuleData[count]['units'] = units;
			finishedModuleData[count]['dateAcquired'] = dateAcquired;
			finishedModuleData[count]['effectiveAge'] = effectiveAge;
			finishedModuleData[count]['unitOfMeasure'] = unitOfMeasure;
		}
	}
	
	var data = {"request" : "submitModule", "levelFourID" : levelFourID, "unitOfMeasure" : unitOfMeasure, "effectiveAge" : effectiveAge, "dateAcquired" : dateAcquired, "units" : units, "physCondition" : physCondition, "condAnalysis" : condAnalysis, "defAnalysis" : defAnalysis, "finAnalysis" : finAnalysis};
	console.log(data);
	//Basic regex check to make sure providing numbers
	if(isNumberPattern.test(dateAcquired) && isNumberPattern.test(units)){
		var submitModule = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});

		submitModule.success(function(data){
			console.log(data);
		});
		
		submitModule.complete(function(){
			
			var currentModuleIndex = $("#module").attr("data-moduleIndex");
			if(!(currentModuleIndex >= (selectedModules.length - 1))){
				createModule(false);
				
			}
			else if(currentModuleIndex >= (selectedModules.length - 1)){
				window.onbeforeunload = null;
				finishReport();
				window.location.href = "main.html";
			}
		});
		
		submitModule.error(function (jqXHR, textStatus, errorThrown){
			alert('Error with the connection: ' + jqXHR + " " + textStatus + " " + errorThrown);
		});
	}
	else{
		//make this highlight the fields, display message
		window.alert('Not numbers in the year or units field!');
	}
}

//For pausing a report
function saveExit(){
	//first saves any changes
	if(pageModified){
			saveReport();
	}
	
	//get modules selected, submit them too
	var allSelectedModules = [];
	var checkBoxes = document.getElementsByClassName("sub-elementCheckbox");
	for(var x = 0; x < checkBoxes.length; x++){
		if(checkBoxes[x].checked && checkBoxes[x].getAttribute("disabled") != 'disabled'){
			allSelectedModules.push(checkBoxes[x].value);
		}
	}
	//make sure set to none if none are selected, won't pass the ISSET checks on other end otherwise
	if(allSelectedModules.length == 0){
		allSelectedModules.push("none");
	}
	
	data = {"request" : "pauseReport", "moduleArray" : allSelectedModules};
	var pauseReport = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	pauseReport.success(function(data){
		console.log(data);
		window.location.href = "main.html";
		
	});
	
	pauseReport.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
}

//closes sessions on server
function finishReport(){
	var data = {"request" : "closeReport"};
	var closeReport = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	closeReport.success(function(data){
		console.log(data);
	});
	
	closeReport.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}
//Deletes report, unused. Requires root access
function deleteCurrentReport(){
	//will delete current report, if user just navigates away
	var data = {"request" : "deleteReport"};
	var deleteReport = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	deleteReport.success(function(data){
		console.log(data);
		
		
	});
	
	deleteReport.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
}

