
var modules;

$(document).ready(function(){
	var data = {"request" : "costing"};
	var accessControlCall = $.ajax({url : 'script/htmlMAC.php', method: 'GET', data : data, dataType:'json'});
	
	//slightly crummy way of doing MAC, but it at least prevents accidental stumbling on the page.
	accessControlCall.success(function(data){
		if(data["hasAccess"] == "true"){
			preparePage();
		}
		else{
			$("#costingWholeContainer").empty();
			$("#costingWholeContainer").append("<h3>You do not have sufficient access rights to use this page</h3>");
		}	
	});
	
	accessControlCall.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
});

function preparePage(){
	//get all the 
	data = {"request" : "getModulesVerbose"};
	var getModules = $.ajax({url : 'script/server.php', method: 'GET', data : data, dataType:'json'});
	
	getModules.success(function(data){
		//for loop, check if row exists, if so, put append after it
		var count;
		//puts all the data into the table
		
		
		
		
		for(count in data){
			modules = data;
			var currentLevelOneID = data[count]['levelOneID'];			
			var currentLevelFourID = data[count]['levelFourID'];

			//logic goes: check if the level1 has been created, if so, append level four to the one after it (since we only need row 1 and row 4 info, and sorting cannot be done without a priority value.
			
			var tableRow = document.createElement("TR");
			tableRow.setAttribute("data-levelFourID", currentLevelFourID);
			if($("tr[data-levelFourID=" + currentLevelFourID + "]").length == 0){ 
				document.getElementById("moduleCostingTable").appendChild(tableRow);
			}
			else{
				insertAfter(tableRow, $("tr[data-levelFourID=" + currentLevelFourID + "]"));
			}
			
			var category = document.createElement("TD");
			category.setAttribute("class", "tableText category");
			var categoryText = document.createTextNode(data[count]['levelOneName']);
			category.appendChild(categoryText);
			
			var component = document.createElement("TD");
			component.setAttribute("class", "tableText component");
			var componentText = document.createTextNode(data[count]['levelFourName']);
			component.appendChild(componentText);
			
			var unitOfMeasure = document.createElement("TD");
			unitOfMeasure.setAttribute("class", "tableDropDown unitOfMeasure");
			var unitOfMeasureText = document.createTextNode(data[count]['unitOfMeasure']);
			unitOfMeasure.appendChild(unitOfMeasureText);
			
			var cost = document.createElement("TD");
			cost.setAttribute("class", "tableNumber cost");
			var costText = document.createTextNode(data[count]['cost']);
			cost.appendChild(costText);
			
			var lifespan = document.createElement("TD");
			lifespan.setAttribute("class", "tableNumber lifespan");
			var lifespanText = document.createTextNode(data[count]['lifespan']);
			lifespan.appendChild(lifespanText);
			
			var editRowButtonContainer = document.createElement("TD");
			var editRowButton = document.createElement("INPUT");
			editRowButton.setAttribute("type", "button");
			editRowButton.setAttribute("class", "editCostingButton");
			editRowButton.setAttribute("value", "Edit Row");
			editRowButtonContainer.appendChild(editRowButton);
			
			tableRow.appendChild(category);
			tableRow.appendChild(component);
			tableRow.appendChild(unitOfMeasure);
			tableRow.appendChild(cost);
			tableRow.appendChild(lifespan);
			tableRow.appendChild(editRowButtonContainer);
		}
	
	
		
	});
	getModules.complete(function(){
		
		$(".editCostingButton").on("click", function(){
			
			//has the input already
			if($(this).parent().siblings().children('input').length == 0){
				$(this).attr("value", "Finish");
				var currentDropDownValue = $(this).parent().siblings('.tableDropDown').text();
				var currentLifespanValue = $(this).parent().siblings('.lifespan').text();
				var currentCostValue = $(this).parent().siblings('.cost').text();
				$(this).parent().siblings(".tableDropDown").empty();
				$(this).parent().siblings('.tableDropDown').append("<select>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='number'>Number</option>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='linearFeet'>Linear Feet</option>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='squareFeet'>Square Feet</option>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='elevation'>Elevation</option>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='system'>System(s)</option>");
				$(this).parent().siblings('.tableDropDown').children('select').append("<option value='unit'>Unit(s)</option>");
				
				$(this).parent().siblings('.lifespan').empty();
				$(this).parent().siblings('.lifespan').append("<input type='number' value='" + currentLifespanValue + "'>");
				
				$(this).parent().siblings('.cost').empty();
				$(this).parent().siblings('.cost').append("<input type='number' value='" + currentCostValue + "'>");
				
				
				
			}
			
			else{
				$(this).attr("value", "Edit Row");
				var currentDropDownValue = $(this).parent().siblings(".tableDropDown").children("select").val();
				$(this).parent().siblings('input').empty();
				$(this).parent().siblings('.tableDropDown').text(currentDropDownValue);
				
				var currentLifespanValue = $(this).parent().siblings(".lifespan").children("input").val();
				var currentCostValue = $(this).parent().siblings(".cost").children("input").val();
				if(isNumeric(currentLifespanValue) && isNumeric(currentCostValue)){
					$(this).parent().siblings('.lifespan').empty();
					$(this).parent().siblings('.lifespan').text(currentLifespanValue);
					
					$(this).parent().siblings('.cost').empty();
					$(this).parent().siblings('.cost').text(currentCostValue);
				}
				else{
					window.alert("Non numeric value!");
				}
			}
		});


	});
	
	getModules.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});
	
	//prevents beforeunload message from displaying

	$(".cancelButton").on("mousedown", function(){
		window.onbeforeunload = null;
	});
	
	$(".cancelButton").on("click", function(){
		window.location.href = "main.html";
	});
	
	$(".saveExitButton").on("mousedown", function(){
		window.onbeforeunload = null;
	});
	
	$(".saveExitButton").on("click", function(){
		saveChanges();
		window.location.href = "main.html";
	});
	
	
	
	
	window.onbeforeunload = function (e) {
		return "Continuing will delete any changes. \n\n Use the 'Save and Exit' button to save your changes, or 'Cancel' to discard and leave.";
	};
}

// Need to add save button that makes all the appropriate changes

//does not save any changes made to the component or category!
function saveChanges(){
	
	if($("table input[type=number]").length > 0 || $("table input[type=text").length > 0){
		$(".editCostingButton").each(function(){
			if($(this).parent().siblings().find("input").length > 0){
				$(this).click();
			}
		});
	}
	//create the data, via a loop
	var table = document.getElementById("moduleCostingTable");
	var dataArray = {};
	for(count in table.children){
		var currentArray = {};
		if(table.children[count].nodeName == "TR"){
			currentArray['levelFourID'] = table.children[count].getAttribute("data-levelFourID");
			currentArray['cost'] = table.children[count].querySelector(".cost").innerText;
			currentArray['lifespan'] = table.children[count].querySelector(".lifespan").innerText;
			currentArray['unitOfMeasure'] = table.children[count].querySelector(".unitOfMeasure").innerText;
			dataArray[count] = currentArray;
		}
	}
	
	var data = {"request" : "submitCostingChanges", "dataArray" : dataArray};
	var submitCostingChanges = $.ajax({url : 'script/server.php', method: 'POST', data : data, dataType:'json'});
	
	submitCostingChanges.success(function(data){
		console.log(JSON.stringify(data));
	});
	
	submitCostingChanges.error(function (jqXHR, textStatus, errorThrown){
		alert('Error with the connection: ' + textStatus + " " + errorThrown);
	});

}


















