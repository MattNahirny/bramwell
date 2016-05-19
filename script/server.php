<?php 


//input controller
//$servername = "198.71.227.98:3306";
//$dbname = "bramwell";
//$username = "bramwell";
//$password = "Pdt9h8!3";
include_once("db.php");

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(
		PDO::ATTR_PERSISTENT => false
		));
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}
	$loggedIn = checkLogIn();
//--- Input controller ---//

//Basic formula is to use a switch on the request, then within that do:
//	A check of who is logged in, and what their access level is
//	A check of if all neccessary variables are set
//	Assigning POST/GET to proper variables
//	Any extra logic (such as setting certain variables like ReportID from session variables
//	Then calling the appropriate function
//	Then finally returning the result of the function

	if(ISSET($_GET['request']) && $loggedIn){
		$request = $_GET['request'];
		
		switch($request){
			
			case "getModulesBasic":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getModulesBasic($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getModulesVerbose":
				$gate = checkAccessLevel();
				if($gate >= 3){
					$return = getModulesVerbose($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 3){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getModulesFromList":
				$gate = checkAccessLevel();
				if($gate >= 2 && ISSET($_GET['moduleList'])){
					$return = getModulesFromList($conn, $_GET['moduleList']);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			//currently, only root can delete, do not enable on create.html
			case "deleteReport":
				$gate = checkAccessLevel();
				if($gate >= 6){
					$return = deletePlan($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 6){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "getFinishedModulesLevelFourId":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getFinishedModulesLevelFourId($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getIncompleteComponents":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getIncompleteComponents($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			
			case "getViewablePausedReports":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getViewablePausedReports($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getUsers":
				$gate = checkAccessLevel();
				if($gate >= 5){
					$return = getUsers($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 5){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getClients":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getClients($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getInspectors":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = getInspectors($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "closeReport":
				$gate = checkAccessLevel();
				if($gate >= 2){
					$return = closeReport();
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "getViewableReports":
				$gate = checkAccessLevel();
				if($gate >= 1){
					$return = getViewableReports($conn);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 1){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "printReport":
				$gate = checkAccessLevel();
				if($gate >= 1 && ISSET($_GET['planID'])){
					
					include("../script/print.php");
					$planID = $_GET['planID'];
					$return = printReport($conn, $planID);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 1){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			default:
				$return = ["message" => "Incorrect Request"];
				echo json_encode($return, JSON_FORCE_OBJECT);
		}
	}
	
	if(ISSET($_POST['request']) && $loggedIn){
		
		$request = $_POST['request'];
		switch($request){
				
			case "submitLogInRequest":
				if(ISSET($_POST["username"], $_POST["password"])){

					$username = $_POST["username"];
					$password = $_POST["password"];
					
					$return = submitLogInRequest($conn, $username, $password);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else{
					$return = ["msg" => "error"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				
				break;
		
			case "addModule":
				$gate = checkAccessLevel();
				if($gate >= 3 && ISSET($_POST["physicalDescStandardDesc"], $_POST["physicalDescStandardComments"], $_POST["finAnalysisDefaultComment"], $_POST["finAnalysisStandardComments"], $_POST["potentDeteriorationDefaultComment"], $_POST["condAnalysisDefaultComment"], $_POST["condAnalysisStandardComments"], $_POST["defAnalysisDefaultComment"], $_POST["defAnalysisStandardComments"], $_POST["lvlOne"], $_POST["lvlTwo"], $_POST["lvlThree"], $_POST["name"], $_POST["unitOfMeasure"], $_POST["costPerUnit"], $_POST["lifespan"])){
					$physicalDescStandardDesc = $_POST["physicalDescStandardDesc"];
					$physicalDescStandardComments = $_POST["physicalDescStandardComments"];//
					$finAnalysisDefaultComment = $_POST["finAnalysisDefaultComment"];
					$finAnalysisStandardComments = $_POST["finAnalysisStandardComments"];//
					$potentDeteriorationDefaultComment = $_POST["potentDeteriorationDefaultComment"];
					$condAnalysisDefaultComment = $_POST["condAnalysisDefaultComment"];
					$condAnalysisStandardComments = $_POST["condAnalysisStandardComments"];//
					$defAnalysisDefaultComment = $_POST["defAnalysisDefaultComment"];
					$defAnalysisStandardComments = $_POST["defAnalysisStandardComments"];//
					$lvlOne = $_POST["lvlOne"];
					$lvlTwo = $_POST["lvlTwo"];
					$lvlThree = $_POST["lvlThree"];
					$name = $_POST["name"];
					$unitOfMeasure = $_POST["unitOfMeasure"];
					$costPerUnit=$_POST["costPerUnit"];
					$lifespan = $_POST["lifespan"];
					
					$return = addModule($conn, $physicalDescStandardDesc, $physicalDescStandardComments, $finAnalysisDefaultComment, $finAnalysisStandardComments, $potentDeteriorationDefaultComment, $condAnalysisDefaultComment, $condAnalysisStandardComments, $defAnalysisDefaultComment, $defAnalysisStandardComments, $lvlOne, $lvlTwo, $lvlThree, $name, $unitOfMeasure, $costPerUnit, $lifespan);
					
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 3){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "submitModule":
				$gate = checkAccessLevel();

				
				if($gate >= 2 && ISSET($_POST['levelFourID'], $_POST['dateAcquired'], $_POST['units'], $_POST['unitOfMeasure'], $_POST['effectiveAge'], $_POST['physCondition'], $_POST['condAnalysis'], $_POST['defAnalysis'], $_POST['finAnalysis'])){
				
					$levelFourID = $_POST['levelFourID'];
					$dateAcquired = $_POST['dateAcquired'];
					$units = $_POST['units'];
					$unitOfMeasure = $_POST['unitOfMeasure'];
					$effectiveAge = $_POST['effectiveAge'];
					$physCondition = $_POST['physCondition'];
					$condAnalysis = $_POST['condAnalysis'];
					$defAnalysis = $_POST['defAnalysis'];
					$finAnalysis = $_POST['finAnalysis'];
				
					session_start();
						$planID = $_SESSION['planID'];
					session_write_close();
					
					$return = submitModule($conn, $planID, $levelFourID, $dateAcquired, $units, $unitOfMeasure, $effectiveAge, $physCondition, $condAnalysis, $defAnalysis, $finAnalysis);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "submitImage":
				$gate = checkAccessLevel();
				
				if($gate >= 2 && ISSET($_FILES['fileToUpload'], $_POST['levelFourID'], $_POST['caption'])){
					$image = $_FILES['fileToUpload'];
					$levelFourID = $_POST['levelFourID'];
					$caption = $_POST['caption'];
					session_start();
						$planID = $_SESSION['planID'];
					session_write_close();
					
					$return = submitImage($conn, $planID, $levelFourID, $image, $caption);
					echo $return["message"];
				}
				else if(ISSET($_FILES['fileToUpload'], $_POST['levelFourID']) == false){
					$return = ["message" => "Not all variables set. "];
					echo $return['message'];
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo $return['message'];
				}
			break;
			
			case "getCurrentContinueReport":
				$gate = checkAccessLevel();
				if($gate >= 2 && ISSET($_POST['planID'])){
					$planID = $_POST['planID'];
					$return = getCurrentContinueReport($conn, $planID);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			
			case "submitContinueModule":
				$gate = checkAccessLevel();
				
				if($gate >= 2 && ISSET($_POST['levelFourID'], $_POST['dateAcquired'], $_POST['units'], $_POST['physDescStandardCommentID'], $_POST['physDescUniqueComment'], $_POST['condAnalysisStandardCommentID'], $_POST['condAnalysisUniqueComment'], $_POST['defAnalysisStandardCommentID'], $_POST['defAnalysisUniqueComment'], $_POST['finAnalysisStandardCommentID'], $_POST['finAnalysisUniqueComment'])){
					$levelFourID = $_POST['levelFourID'];
					$dateAcquired = $_POST['dateAcquired'];
					$units = $_POST['units'];
					$physDescStandardCommentID = $_POST['physDescStandardCommentID'];
					$physDescUniqueComment = $_POST['physDescUniqueComment'];
					$condAnalysisStandardCommentID = $_POST['condAnalysisStandardCommentID'];
					$condAnalysisUniqueComment = $_POST['condAnalysisUniqueComment'];
					$defAnalysisStandardCommentID = $_POST['defAnalysisStandardCommentID'];
					$defAnalysisUniqueComment = $_POST['defAnalysisUniqueComment'];
					$finAnalysisStandardCommentID = $_POST['finAnalysisStandardCommentID'];
					$finAnalysisUniqueComment = $_POST['finAnalysisUniqueComment'];
				
					session_start();
						$planID = $_SESSION['continueReportID'];
					session_write_close();
					
					$return = submitModule($conn, $planID, $levelFourID, $dateAcquired, $units, $physDescStandardCommentID, $physDescUniqueComment, $condAnalysisStandardCommentID, $condAnalysisUniqueComment, $defAnalysisStandardCommentID, $defAnalysisUniqueComment, $finAnalysisStandardCommentID, $finAnalysisUniqueComment);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			
			// this is a painful amount of variables
			case "submitDetails":

				$gate = checkAccessLevel();
				if($gate >= 2 && ISSET($_POST['strataNumber'], $_POST['strataName'], $_POST['clientID'], $_POST['inspectorArray'], $_POST['inspectionDateArray'], $_POST['effectiveDate'], $_POST['materialGiven'], $_POST['street'], $_POST['city'], $_POST['postalCode'], $_POST['location'], $_POST['constructType'], $_POST['strataRegDate'], $_POST['constructYear'], $_POST['numberOfLvl'], $_POST['strataPlans'], $_POST['plansScheduleDetails'], $_POST['sitePlans'], $_POST['restrictedCovenant'], $_POST['residentialLots'], $_POST['commercialLots'], $_POST['complexOwnedLots'], $_POST['numberBuildings'], $_POST['siteArea'], $_POST['siteCoverage'], $_POST['buildingHeight'], $_POST['constOverview'], $_POST['constFoundations'], $_POST['constSubstructure'], $_POST['constExterior'], $_POST['constRoofDrainage'], $_POST['constAmenities'], $_POST['constElectrical'], $_POST['constServices'])){
						
					$strataNumber = $_POST['strataNumber'];
					$strataName = $_POST['strataName'];
					$clientID = $_POST['clientID'];
					$inspectorArray = $_POST['inspectorArray'];
					$inspectionDateArray = $_POST['inspectionDateArray'];
					$effectiveDate = $_POST['effectiveDate'];
					$materialGiven = $_POST['materialGiven'];
					
					
					$street =  $_POST['street'];
					$city =  $_POST['city'];
					$postalCode =  $_POST['postalCode'];
					$location =  $_POST['location'];
					$constructType =  $_POST['constructType'];
					$strataRegDate =  $_POST['strataRegDate'];
					$constructYear =  $_POST['constructYear'];
					$numberOfLvl =  $_POST['numberOfLvl'];
					
					$strataPlans =   $_POST['strataPlans'];
					$plansScheduleDetails =  $_POST['plansScheduleDetails'];
					$sitePlans =  $_POST['sitePlans'];
					
					$restrictedCovenant =  $_POST['restrictedCovenant'];
					$residentialLots =  $_POST['residentialLots'];
					$commercialLots =  $_POST['commercialLots'];
					$complexOwnedLots =  $_POST['complexOwnedLots'];
					$numberBuildings =  $_POST['numberBuildings'];

					$siteArea =  $_POST['siteArea'];
					$siteCoverage =  $_POST['siteCoverage'];
					
					$constOverview =  $_POST['constOverview'];
					$constFoundations =  $_POST['constFoundations'];
					$constSubstructure =  $_POST['constSubstructure'];
					$constExterior =  $_POST['constExterior'];
					$constRoofDrainage =  $_POST['constRoofDrainage'];
					$constAmenities =  $_POST['constAmenities'];
					$constElectrical =  $_POST['constElectrical'];
					$constServices =  $_POST['constServices'];
					
					$buildingHeight =  $_POST['buildingHeight'];
					//Empty arrays are seen as not being set, have to check and set on this end
					if(ISSET($_POST['serviceArray'])){
						$serviceArray = $_POST['serviceArray'];
					}
					else{
						$serviceArray = [];
					}
				
					$return = submitDetails($conn, $strataNumber, $strataName, $clientID, $inspectorArray, $inspectionDateArray, $effectiveDate, $materialGiven, $street, $city, $postalCode, $location, $constructType, $strataRegDate, $constructYear, $numberOfLvl, $strataPlans, $plansScheduleDetails, $sitePlans, $restrictedCovenant, $residentialLots, $commercialLots, $complexOwnedLots, $numberBuildings, $siteArea, $siteCoverage, $buildingHeight, $constOverview, $constFoundations, $constSubstructure, $constExterior, $constRoofDrainage, $constAmenities, $constElectrical, $constServices, $serviceArray);
					
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "submitCostingChanges":
				$gate = checkAccessLevel();
				if($gate >= 3 && ISSET($_POST['dataArray'])){
				
					$dataArray = $_POST['dataArray'];
					
					$return = submitCostingChanges($conn, $dataArray);
					echo json_encode($return, JSON_FORCE_OBJECT);
					
				}
				else if($gate < 3){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "pauseReport":
				$gate = checkAccessLevel();
				if($gate >= 2 && ISSET($_POST['moduleArray'])){
					$moduleArray = $_POST['moduleArray'];
					
					session_start();
						$planID = $_SESSION['planID'];
					session_write_close();
					
					$return = pauseReport($conn, $planID, $moduleArray);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "submitInspector":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phone'], $_POST['cell'])){
					$firstName = $_POST['firstName'];
					$lastName = $_POST['lastName'];
					$email = $_POST['email'];
					$phone = $_POST['phone'];
					$cell = $_POST['cell'];
					
					$return = submitInspector($conn, $firstName, $lastName, $email, $phone, $cell);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;

			case "submitInspectorUpdate":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['dataArray'])){
					$dataArray = $_POST['dataArray'];
					
					$return = submitInspectorUpdate($conn, $dataArray);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "submitClient":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['firstName'], $_POST['lastName'], $_POST['company'], $_POST['address'], $_POST['city'])){
				
					$firstName = $_POST['firstName'];
					$lastName = $_POST['lastName'];
					$company = $_POST['company'];
					$address = $_POST['address'];
					$city = $_POST['city'];
					
					
					$return = submitClient($conn, $firstName, $lastName, $company, $address, $city);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "submitClientUpdate":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['dataArray'])){
					$dataArray = $_POST['dataArray'];
					
					$return = submitClientUpdate($conn, $dataArray);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "submitUser":
				$gate = checkAccessLevel();
				if($gate >= 5 && ISSET($_POST["username"], $_POST["password"], $_POST["email"], $_POST['accessLevel'])){

					$username = $_POST["username"];
					$password = $_POST["password"];
					$email = $_POST["email"];
					$accessLevel = $_POST["accessLevel"];
					
					$return = submitUser($conn, $username, $password, $email, $accessLevel);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 5){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "submitUserUpdate":
				$gate = checkAccessLevel();
				if($gate >= 5 && ISSET($_POST['dataArray'])){
					$dataArray = $_POST['dataArray'];
					
					$return = submitUserUpdate($conn, $dataArray);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 5){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
				
			case "submitConstructInfo":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['infoType'], $_POST['buildingType'], $_POST['comment'])){
					$infoType = $_POST['infoType'];
					$buildingType = $_POST['buildingType'];
					$comment = $_POST['comment'];
					
					$return = submitConstructInfo($conn, $infoType, $buildingType, $comment);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "getConstructionInfo":
				$gate = checkAccessLevel();
				if($gate >= 2 && ISSET($_POST['buildingType'])){
					$buildingType = $_POST['buildingType'];
					//Checking to make sure it's getting appropriate values, these are the only (current) options.
					if($buildingType == "flatland" || $buildingType == "complex" || $buildingType == "townhouse" || $buildingType == "custom"){
						$return = getConstructionInfo($conn, $buildingType);
						echo json_encode($return, JSON_FORCE_OBJECT);
					}
					else{
						$return = ["message" => "Incorrect property type provided"];
						echo json_encode($return, JSON_FORCE_OBJECT);
					}
				}
				else if($gate < 2){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
			case "deleteConstructionInfo":
				$gate = checkAccessLevel();
				if($gate >= 4 && ISSET($_POST['constructInfoID'])){
				
					$constructInfoID = $_POST['constructInfoID'];
					
					$return = deleteConstructionInfo($conn, $constructInfoID);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else if($gate < 4){
					$return = ["message" => "You do not have sufficient privileges to access that function"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				break;
                        case "submitLogoutRequest":
                            $return = var_dump($_SESSION);
                            echo json_encode($return, JSON_FORCE_OBJECT);
                            submitLogOutRequest();
                            break;
			default:
				$return = ["msg" => "Improper request"];
				echo json_encode($return, JSON_FORCE_OBJECT);
		}
		
	}
	if(ISSET($_POST['request']) && !($loggedIn)){
		
		$request = $_POST['request'];
		switch($request){
				
			case "submitLogInRequest":
				if(ISSET($_POST["username"], $_POST["password"])){

					$username = $_POST["username"];
					$password = $_POST["password"];
					
					$return = submitLogInRequest($conn, $username, $password);
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				else{
					$return = ["msg" => "error"];
					echo json_encode($return, JSON_FORCE_OBJECT);
				}
				
				break;
		}
	}
	
//Function for adding modules to the system
function addModule($conn, $physicalDescStandardDesc, $physicalDescStandardComments, $finAnalysisDefaultComment, $finAnalysisStandardComments, $potentDeteriorationDefaultComment, $condAnalysisDefaultComment, $condAnalysisStandardComments, $defAnalysisDefaultComment, $defAnalysisStandardComments, $lvlOne, $lvlTwo, $lvlThree, $name, $unitOfMeasure, $costPerUnit, $lifespan){
	
	//Need to verify data, such as that the dropdowns (levelOne, levelTwo, etc) are not 'default' and correspond to actual levels

	
	//Logic to get if a level for that level name exists, if not it creates an ID for it (in order to maintain foreign keys, done by hand rather than Auto Increment
	//These are all using the insecure countRows function, consider replacing with a dedicated getter

	
	$existingLevelOne = getLevelID($conn, 'levelone', $lvlOne);
	$existingLevelTwo = getLevelID($conn, 'leveltwo', $lvlTwo);
	$existingLevelThree = getLevelID($conn, 'levelthree', $lvlThree);
	$existingLevelFour = getLevelID($conn, 'levelfour', $name);
	
	if(count($existingLevelOne) == 0){
		$levelOneID = insertLevelOne($conn, $lvlOne);
	}
	else{
		$levelOneID = $existingLevelOne[0]['LevelOneId'];
	}
	if(count($existingLevelTwo) == 0){
		$levelTwoID = insertLevelTwo($conn, $lvlTwo, $levelOneID);
	}
	else{
		$levelTwoID = $existingLevelTwo[0]['LevelTwoId'];
	}
	if(count($existingLevelThree) == 0){
		$levelThreeID = insertLevelThree($conn, $lvlThree, $levelTwoID);
	}
	else{
		$levelThreeID = $existingLevelThree[0]['LevelThreeId'];
	}
	if(count($existingLevelFour) == 0){
		$levelFourID = insertLevelFour($conn, $name, $levelThreeID, $physicalDescStandardDesc, $finAnalysisDefaultComment, $condAnalysisDefaultComment, $defAnalysisDefaultComment, $costPerUnit, $lifespan, $potentDeteriorationDefaultComment, $unitOfMeasure);
	}
	else{
		$levelFourID = $existingLevelFour[0]['LevelFourId'];
	}
	//insert into level four all data
	
	
	//create array loop to insert into standard comments
	//Make sure javascript is setting it to 'none', not just sending a blank string, if there are none input as it won't pass the ISSET gate
	
	foreach($physicalDescStandardComments as $standardComment){
		if($standardComment != "none"){
			insertStandardComment($conn, $levelFourID, "physicalDesc", $standardComment); 
		}
	}

	
	foreach($finAnalysisStandardComments as $standardComment){
		if($standardComment != "none"){
			insertStandardComment($conn, $levelFourID, "finAnalysis", $standardComment); 
		}
	}
	

	foreach($condAnalysisStandardComments as $standardComment){
		if($standardComment != "none"){
			insertStandardComment($conn, $levelFourID, "condAnalysis", $standardComment); 
		}
	}
	

	foreach($defAnalysisStandardComments as $standardComment){
		if($standardComment != "none"){
			insertStandardComment($conn, $levelFourID, "defAnalysis", $standardComment);
		}
	}
	
	
	$return = ["message" => "Successfully inserted module"];
	return $return;
}

//Function for submitting the actual module/component, when an inspector is doing a report
//Have to specify one of the following for type:
// [ physicalDesc, finAnalysis, condAnalysis, defAnalysis ]
function submitModule($conn, $planID, $levelFourID, $dateAcquired, $units, $unitOfMeasure, $effectiveAge, $physCondition, $condAnalysis, $defAnalysis, $finAnalysis){

	//uses insecure function countRows, consider replacing with dedicated count function
	
	$moduleExists = getCountModule($conn, $planID, $levelFourID);
	//Checks to see if it should be updating or inserting a new module, in order to simplify the javascript to only need to call 'submitModule' no matter what it's doing
	if($moduleExists == 0){
		$sql = "INSERT INTO plancomponent (PlanId, LevelFourId, YearAcquired, DeficiencyAnalysis, ConditionAnalysis, PhysicalCondition, FinancialAnalysis, NumUnits, EffectiveAge, UnitOfMeasure) VALUES (:planID, :levelFourID, :dateAcquired, :defAnalysis, :condAnalysis, :physCondition, :finAnalysis, :units, :effectiveAge, :unitOfMeasure)";	
		$sth = $conn->prepare($sql);	
	}
	
	else {
		$sql = "UPDATE plancomponent SET YearAcquired = :dateAcquired, DeficiencyAnalysis = :defAnalysis, ConditionAnalysis = :condAnalysis, PhysicalCondition = :physCondition, FinancialAnalysis = :finAnalysis, NumUnits = :units, EffectiveAge = :effectiveAge, UnitOfMeasure = :unitOfMeasure WHERE PlanId = :planID AND LevelFourId = :levelFourID";	
		$sth = $conn->prepare($sql);
	
	}
	
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->bindParam(':dateAcquired', $dateAcquired);
	$sth->bindParam(':defAnalysis', $defAnalysis, PDO::PARAM_STR);
	$sth->bindParam(':condAnalysis', $condAnalysis, PDO::PARAM_STR);
	$sth->bindParam(':physCondition', $physCondition, PDO::PARAM_STR);
	$sth->bindParam(':finAnalysis', $finAnalysis, PDO::PARAM_STR);
	$sth->bindParam(':units', $units, PDO::PARAM_INT, 11);
	$sth->bindParam(':effectiveAge', $effectiveAge, PDO::PARAM_INT, 4);
	$sth->bindParam(':unitOfMeasure', $unitOfMeasure, PDO::PARAM_STR, 45);
	$sth->execute();
	$sth->closeCursor();
	
	$return = ['message' => 'Successfully added the order'];
	return $return;
}
//Submitting images,
//This was mostly scalped from W3 schools, but I modified it to suit our needs

//Stores all images to the /wwwroot/script/uploads/report[REPORTID]
//Stores the URI, the caption, and which plan/levelFourID it belongs to
function submitImage($conn, $planID, $levelFourID, $image, $caption){
	
	$target_dir = "uploads/report" . $planID . "/";
	if(!(is_dir($target_dir))){
		mkdir($target_dir);
	}
	
	$target_file = $target_dir . basename($image["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($image["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$return = ['message' => "File is not an image."];
			return $return;
			$uploadOk = 0;
		}
	}
	
	// Check if file already exists
	if (file_exists($target_file)) {
		$return = ['message' => "File already exists."];
		return $return;
		$uploadOk = 0;
	}

	// Allow only jpg, png, gif and jpeg
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		$return = ['message' => "Sorry, only JPG, JPEG, PNG & GIF files can be uploaded."];
		return $return;
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$return = ['message' => "Sorry, there was an error in the upload process."];
		return $return;

	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($image["tmp_name"], $target_file)) {
			$return = ['message' => "The file ". basename( $image["name"]). " has been uploaded."];
			$sql = "INSERT INTO componentpicture (PlanId, LevelFourId, PictureURI, Caption) VALUES (:planID, :levelFourID, :pictureURI, :caption)";	
			$sth = $conn->prepare($sql);

			$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
			$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
			$sth->bindParam(':pictureURI', $target_file, PDO::PARAM_STR, 45);
			$sth->bindParam(':caption', $caption, PDO::PARAM_STR, 255);

			$sth->execute();
			$sth->closeCursor();
			return $return;
		} else {
			$return = ['message' => "Sorry, there was an error uploading your file."];
		}
	}
}


//SL
//Function to Submit images for the author
function submitAuthorImage($conn, $authorID, $image) {

    $target_dir = "uploads/report/authors";
    if(!(is_dir($target_dir))){
	mkdir($target_dir);
    }
    
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($image["tmp_name"]);
	if($check !== false) {
            $uploadOk = 1;
	} else {
            $return = ['message' => "File is not an image."];
            return $return;
	}
    }
	
// Check if file already exists
    if (file_exists($target_file)) {
        unlink($target_file);
    }

// Allow only jpg, png, gif and jpeg
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
	$return = ['message' => "Sorry, only JPG, JPEG, PNG & GIF files can be uploaded."];
	return $return;
    }

	// if everything is ok, try to upload file
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        $return = ['message' => "The file ". basename( $image["name"]). " has been uploaded."];
	$sql = "UPDATE author SET image = (:pictureURI) WHERE authorId = (:authorID)";
	$sth = $conn->prepare($sql);
	$sth->bindParam(':authorID', authorID, PDO::PARAM_INT, 11);
	$sth->bindParam(':pictureURI', $target_file, PDO::PARAM_STR, 45);
	$sth->execute();
	$sth->closeCursor();
	return $return;
    } else{
        $return = ['message' => "Sorry there was an error uploading your file."];
        }
}
//SL E


//Basic getter for a component (completed by an inspector) based on a levelFourID
function getModule($conn, $planID, $levelFourID){
	$sql = "SELECT * FROM plancomponent WHERE PlanId = :planID AND LevelFourId = :levelFourID";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	return $row;
}
//Basic getter for counting the amount of finished components for a report and a levelfourID
//Essentially checks to see if a certain module was already saved.
function getCountModule($conn, $planID, $levelFourID){
	$sql = "SELECT count(*) FROM plancomponent WHERE PlanId = :planID AND LevelFourId = :levelFourID";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows[0]["count(*)"];
}
//Insert statement for a unique comment, naming is a legacy thing from before some changes were made
//Inserts a type of comment, such as the DeficiencyAnalysis or ConditionAnalysis, as well as the comment itself
// Should be one of the following: [ physicalDesc, finAnalysis, condAnalysis, defAnalysis ]
function insertUnqComment($conn, $planComponentID, $comment, $type){
	$sql = "INSERT INTO unqcomment (PlanComponentId, Comment, Type) VALUES (:planComponentID, :comment, :type)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planComponentID', $planComponentID, PDO::PARAM_INT, 11);
	$sth->bindParam(':comment', $comment, PDO::PARAM_STR);
	$sth->bindParam(':type', $type, PDO::PARAM_STR, 45);
	
	$sth->execute();
	$return = ['message' => 'Successfully added the order'];
	$sth->closeCursor();

	return $return;
}
//Insert statement for the levelone table, for creating the whole level1 -> level2 -> level3 -> level4 structure
function insertLevelOne($conn, $lvlOne){
	$sql = "INSERT INTO levelone (Name) VALUES (:lvlOne)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':lvlOne', $lvlOne, PDO::PARAM_STR, 45);
	
	$sth->execute();
	$return = $conn->lastInsertID();
	$sth->closeCursor();
	
	return $return;
}

//Insert statement for the leveltwo table, for creating the whole level1 -> level2 -> level3 -> level4 structure
function insertLevelTwo($conn, $lvlTwo, $levelOneID){
	$sql = "INSERT INTO leveltwo (Name, LevelOneId) VALUES (:lvlTwo, :levelOneID)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':lvlTwo', $lvlTwo, PDO::PARAM_STR, 45);
	$sth->bindParam(':levelOneID', $levelOneID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$return = $conn->lastInsertID();
	$sth->closeCursor();
	
	return $return;
}

//Insert statement for the levelthree table, for creating the whole level1 -> level2 -> level3 -> level4 structure
function insertLevelThree($conn, $lvlThree, $levelTwoID){
	$sql = "INSERT INTO levelthree (Name, LevelTwoId) VALUES (:lvlThree, :levelTwoID)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':lvlThree', $lvlThree, PDO::PARAM_STR, 45);
	$sth->bindParam(':levelTwoID', $levelTwoID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$return = $conn->lastInsertID();
	$sth->closeCursor();
	
	return $return;
}

//Insert statement for the levelfour table, for creating the whole level1 -> level2 -> level3 -> level4 structure
function insertLevelFour($conn, $name, $levelThreeID, $physicalDescStandardDesc, $finAnalysisDefaultComment, $condAnalysisDefaultComment, $defAnalysisDefaultComment, $costPerUnit, $lifespan, $potentDeteriorationDefaultComment, $unitOfMeasure){
	$sql = "INSERT INTO levelfour (Name, LevelThreeId, DefPhysicalCondition, DefFinancialAnalysis, DefConditionAnalysis, Cost, DefDeficiencyAnalysis, ExpectedLifespan, DefPotentialDeterioration, UnitOfMeasure) VALUES (:name, :levelThreeID, :physicalDescStandardDesc, :finAnalysisDefaultComment, :condAnalysisDefaultComment, :costPerUnit, :defAnalysisDefaultComment, :lifespan, :potentDeteriorationDefaultComment, :unitOfMeasure)";
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':name', $name, PDO::PARAM_STR, 45);
	$sth->bindParam(':levelThreeID', $levelThreeID, PDO::PARAM_INT, 11);
	$sth->bindParam(':physicalDescStandardDesc', $physicalDescStandardDesc, PDO::PARAM_STR);
	$sth->bindParam(':finAnalysisDefaultComment', $finAnalysisDefaultComment, PDO::PARAM_STR);
	$sth->bindParam(':condAnalysisDefaultComment', $condAnalysisDefaultComment, PDO::PARAM_STR);
	$sth->bindParam(':costPerUnit', $costPerUnit, PDO::PARAM_INT);
	$sth->bindParam(':defAnalysisDefaultComment', $defAnalysisDefaultComment, PDO::PARAM_STR);
	$sth->bindParam(':lifespan', $lifespan, PDO::PARAM_INT, 11);
	$sth->bindParam(':potentDeteriorationDefaultComment', $potentDeteriorationDefaultComment, PDO::PARAM_INT, 11);
	$sth->bindParam(':unitOfMeasure', $unitOfMeasure, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$return = $conn->lastInsertID();
	$sth->closeCursor();
	
	return $return;
}


//Inserts a row into the standard comments table,
//Have to specify one of the following for type:
// [ physicalDesc, finAnalysis, condAnalysis, defAnalysis ]

function insertStandardComment($conn, $levelFourID, $type, $comment){

	$sql = "INSERT INTO stndcomment (Comment, LevelFourId, Type) VALUES (:comment, :levelFourID, :type)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':comment', $comment, PDO::PARAM_STR);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->bindParam(':type', $type, PDO::PARAM_STR, 45);
	
	$sth->execute();
	$sth->closeCursor();
	$return = ['message' => 'Successfully added the order'];
	return $return;

}

//Function for pausing the report
//Inserts the reportID, and then saves ALL modules, completed or not, to the incompletemodule table
function pauseReport($conn, $planID, $moduleArray){
	insertTemporaryReport($conn, $planID);
	
	foreach($moduleArray as $module){
		if($module != "none"){
			insertIncompleteModule($conn, $planID, $module);
		}
	}
	
	$return = ["msg" => "successfully saved the modules"];
	return $return;
	
}

//Insert statement for storing a temporary report module/component
function insertIncompleteModule($conn, $planID, $levelFourID){

	$sql = "INSERT INTO incompletecomponents (PlanId, LevelFourId) VALUES (:planID, :levelFourID)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
	$return = ['message' => 'Successfully added the order'];
	return $return;
}

//Insert statement for storing a temporary report
function insertTemporaryReport($conn, $planID){
	$sql = "INSERT INTO temporaryreport (PlanId, DatePaused) VALUES (:planID, CURDATE())";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
	
	
	
	$return = ['message' => 'Successfully added the order'];
	return $return;
}

//Function for de-setting the session planID
//Very important to be calling this anytime someone finishes working on a new/continue report
//Can break the system a bit otherwise, sessions are wack.

function closeReport(){
	session_start();
		unset($_SESSION['planID']);
	session_write_close();
	
	$return = ["msg" => "successfully closed the report"];
	return $return;

}

//Delete all the modules associated with a plan, then the plan itself
//Designed to be used with deleting a current going report if the user refreshes the page
//Or navigates away without using the official save button
//Current needs root level access, not in use.
function deletePlan($conn){

	session_start();
		$planID = $_SESSION['planID'];
	session_write_close();
	
	deletePlanModules($conn, $planID);
	
	$sql = "DELETE FROM plan WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
	
	session_start();
		unset($_SESSION['planID']);
	session_write_close();
	
	$return = ['message' => 'Successfully deleted the report'];
	return $return;

}

//Delete statement for the modules in a report, see above
function deletePlanModules($conn, $planID){

	
	$sql = "DELETE FROM plancomponent WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();

}


//Deleting a continue report, was in use but stopped during testing. Not enabled
function deleteContinueReport($conn, $planID){
	$sql = "DELETE FROM temporaryreport WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
}

//Deleting a continue report module list, was in use but stopped during testing. Not enabled
function deleteUnfinishedModules($conn, $planID){
	$sql = "DELETE FROM incompletecomponents WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
}

//Function for inserting the biggest details of a report itself, such as the postal code, the client, etc.
function submitDetails($conn, $strataNumber, $strataName, $clientID, $inspectorArray, $inspectionDateArray, $effectiveDate, $materialGiven, $street, $city, $postalCode, $location, $constructType, $strataRegDate, $constructYear, $numberOfLvl, $strataPlans, $plansScheduleDetails, $sitePlans, $restrictedCovenant, $residentialLots, $commercialLots, $complexOwnedLots, $numberBuildings, $siteArea, $siteCoverage, $buildingHeight, $constOverview, $constFoundations, $constSubstructure, $constExterior, $constRoofDrainage, $constAmenities, $constElectrical, $constServices, $serviceArray){

	
	//insecure function, consider replacing with a dedicated sql query
	$planCount = countRows($conn, "plan");
	
	//check session for if planID is set, if so, get from the database that the strataNumber == it
	$reportCount = 0;
	session_start();
		if(ISSET($_SESSION['planID'])){
			//Session is set, likely updating the report
			$planID = $_SESSION['planID'];
			//checking to make sure the entered strataname matches the session's planID
			//if it doesn't, likely the session was not unset after a different page used it
			//set it to a new one.
			$reportCount = checkReportExists($conn, $planID, $strataNumber);
			$sessionSet = true;
		}
		else{
			$sessionSet = false;
		}
	session_write_close();
	//Final bullshit check
	

	//if no existing report (uses auto increment), or if the session was not set
	if($reportCount == 0 || $sessionSet == false){
		
		
		$sql = "INSERT INTO plan (StrataNumber, Name, UserId, `PostalCode`, Location, `City`, `Street`, `ConstructionType`, `StrataRegistrationDate`, `ConstructionYear`, `Floors`, `SiteArea`, `BuiltSiteCoverage`, `BuildingHeightLevels`, `StrataPlans`, `BuildingPlans`, `SitePlans`, ClientId, EffectiveDate, MaterialGiven, Overview, Foundations, Substructure, Exterior, RoofDrainage, Amenities, Electrical, Services, NumResidentialStrataLots, NumCommercialStrataLots, NumComplexOwnedStrataLots, NumBuildings) VALUES (:strataNumber, :strataName, :userID, :postalCode, :location, :city, :street, :constructType, :strataRegDate, :constructYear, :numberOfLvl, :siteArea, :siteCoverage, :buildingHeight, :strataPlans, :plansScheduleDetails, :sitePlans, :clientID, :effectiveDate, :materialGiven, :overview, :foundations, :substructure, :exterior, :roofDrainage, :amenities, :electrical, :services, :residentialLots, :commercialLots, :complexOwnedLots, :numberBuildings)";
		
		$return = ['message' => "Successfully added the details"];
	}
	//if report exists, update it instead
	else if($reportCount == 1 ){
		$sql = "UPDATE plan SET Name = :strataName, UserId = :userID, `PostalCode` = :postalCode, Location = :location, `City` = :city, `Street` = :street, `ConstructionType` = :constructType, `StrataRegistrationDate` = :strataRegDate, `ConstructionYear` = :constructYear, `Floors` = :numberOfLvl, `SiteArea` = :siteArea, `BuiltSiteCoverage` = :siteCoverage, `BuildingHeightLevels` = :buildingHeight, `StrataPlans` = :strataPlans, `BuildingPlans` = :plansScheduleDetails, `SitePlans` = :sitePlans, ClientId = :clientID, EffectiveDate = :effectiveDate, MaterialGiven = :materialGiven, Overview = :overview, Foundations = :foundations, Substructure = :substructure, Exterior = :exterior, RoofDrainage = :roofDrainage, Amenities = :amenities, Electrical = :electrical, Services = :services, NumResidentialStrataLots = :residentialLots, NumCommercialStrataLots = :commercialLots, NumComplexOwnedStrataLots = :complexOwnedLots, NumBuildings = :numberBuildings WHERE PlanId = :planID AND StrataNumber = :strataNumber";
		$return = ['message' => "Successfully updated the details"];
	}
	else if($reportCount > 1){
		$return = ["msg" => "There is a huge issue with data integrity"];
		return $return;
	}
	//Assigns reports to the userID of whoever is logged in.
	$userID = getLoggedInID();
	

	$sth = $conn->prepare($sql);
	
	//need to attach it if updating, else it uses auto increment
	if($reportCount == 1 ){
		$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	}
	
	$sth->bindParam(':strataNumber', $strataNumber, PDO::PARAM_STR, 45);
	
	$sth->bindParam(':strataName', $strataName, PDO::PARAM_STR, 45);
	$sth->bindParam(':userID', $userID, PDO::PARAM_INT, 11);
	$sth->bindParam(':postalCode', $postalCode, PDO::PARAM_STR, 6);
	$sth->bindParam(':location', $location, PDO::PARAM_STR, 255);
	$sth->bindParam(':city', $city, PDO::PARAM_STR, 45);
	$sth->bindParam(':street', $street, PDO::PARAM_STR, 255);
	$sth->bindParam(':constructType', $constructType, PDO::PARAM_STR,45);
	$sth->bindParam(':strataRegDate', $strataRegDate);
	$sth->bindParam(':constructYear', $constructYear);
	$sth->bindParam(':numberOfLvl', $numberOfLvl, PDO::PARAM_STR, 45);


	$sth->bindParam(':siteArea', $siteArea, PDO::PARAM_INT, 11);
	$sth->bindParam(':siteCoverage', $siteCoverage, PDO::PARAM_INT, 11);
	$sth->bindParam(':buildingHeight', $buildingHeight, PDO::PARAM_INT, 11);

	$sth->bindParam(':strataPlans', $strataPlans, PDO::PARAM_STR);
	$sth->bindParam(':plansScheduleDetails', $plansScheduleDetails, PDO::PARAM_STR);
	$sth->bindParam(':sitePlans', $sitePlans, PDO::PARAM_STR);
	
	$sth->bindParam(':clientID', $clientID, PDO::PARAM_INT, 11);
	$sth->bindParam(':effectiveDate', $effectiveDate);
	$sth->bindParam(':materialGiven', $materialGiven, PDO::PARAM_STR);
	
	$sth->bindParam(':overview', $constOverview, PDO::PARAM_STR);
	$sth->bindParam(':foundations', $constFoundations, PDO::PARAM_STR);
	$sth->bindParam(':substructure', $constSubstructure, PDO::PARAM_STR);
	$sth->bindParam(':exterior', $constExterior, PDO::PARAM_STR);
	$sth->bindParam(':roofDrainage', $constRoofDrainage, PDO::PARAM_STR);
	$sth->bindParam(':amenities', $constAmenities, PDO::PARAM_STR);
	$sth->bindParam(':electrical', $constElectrical, PDO::PARAM_STR);
	$sth->bindParam(':services', $constServices, PDO::PARAM_STR);
	
	$sth->bindParam(':residentialLots', $residentialLots, PDO::PARAM_INT, 11);
	$sth->bindParam(':commercialLots', $commercialLots, PDO::PARAM_INT, 11);
	$sth->bindParam(':complexOwnedLots', $complexOwnedLots, PDO::PARAM_INT, 11);
	$sth->bindParam(':numberBuildings', $numberBuildings, PDO::PARAM_INT, 11);
	
	
	
	$sth->execute();
	//if it was autoincremented because it was created
	if($reportCount == 0){
		$planID = $conn->lastInsertID();
		session_start();
			$_SESSION['planID'] = $planID;
		session_write_close();
	}
	
	$sth->closeCursor();
	submitInspectedBy($conn, $planID, $inspectorArray);
	submitInspectionDates($conn, $planID, $inspectionDateArray);
	submitServices($conn, $planID, $serviceArray);
	return $return;

}
//Insert function for inserting into the inspectedby table
function submitInspectedBy($conn, $planID, $inspectorArray){
	$sql = "INSERT INTO inspectedby (PlanId, InspectorId) VALUES (:planID, :inspectorID)";
	deleteInspectedBy($conn, $planID);
	foreach($inspectorArray as $inspectorID){
		$sth = $conn->prepare($sql);

		$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
		$sth->bindParam(':inspectorID', $inspectorID, PDO::PARAM_INT, 11);				
		$sth->execute();
		
		$sth->closeCursor();
	}
	$return = ['message' => 'Successfully added the inspector'];
	return $return;

}
//Delete function for inspectedby
function deleteInspectedBy($conn, $planID){
	$sql = "DELETE FROM inspectedby WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
}
//insert function for planservice table
function submitServices($conn, $planID, $serviceArray){
	$sql = "INSERT INTO planservice (PlanId, ServiceName, Comment) VALUES (:planID, :serviceName, :comment)";
	deleteServices($conn, $planID);
	
	foreach($serviceArray as $service){
		$sth = $conn->prepare($sql);

		$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
		$sth->bindParam(':serviceName', $service['serviceName'], PDO::PARAM_STR);				
		$sth->bindParam(':comment', $service['serviceValue'], PDO::PARAM_STR);				
		$sth->execute();
		
		$sth->closeCursor();
	}
	
	$return = ['message' => 'Successfully added the service'];
	return $return;
}

//delete function for planservice table
function deleteServices($conn, $planID){
	$sql = "DELETE FROM planservice WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
}
//submit function for dateinspected table
function submitInspectionDates($conn, $planID, $inspectionDateArray){
	$sql = "INSERT INTO dateinspected (PlanId, Date) VALUES (:planID, :date)";	
	deleteDateInspected($conn, $planID);
	foreach($inspectionDateArray as $date){
		$sth = $conn->prepare($sql);
		$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
		$sth->bindParam(':date', $date);
		$sth->execute();
		
		$sth->closeCursor();
	}

	$return = ['message' => 'Successfully added the date'];
	return $return;
}

//delete function for dateinspected table
function deleteDateInspected($conn, $planID){
	$sql = "DELETE FROM dateinspected WHERE PlanId = :planID";
	$sth = $conn->prepare($sql);
	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
}

//a count function for the plan table, based on strata name and planID,
//Used to check if a report actually exists/verify planID when inserting/updating details
function checkReportExists($conn, $planID, $strataName){
	$sql = "SELECT count(*) FROM plan WHERE PlanId = :planID AND StrataNumber = :strataName";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':strataName', $strataName, PDO::PARAM_STR, 45);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows[0]["count(*)"];
}
//submit function for the construction info dropdowns
//building type should be one of: [flatland, complex, townhouse, custom]
//infotype should be one of: [overview, electrical, amenities, roof, services, exterior, substructure, foundations]
function submitConstructInfo($conn, $infoType, $buildingType, $comment){

	$sql = "INSERT INTO constructioninfo (InfoType, BuildingType, Comment) VALUES (:infoType, :buildingType, :comment)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':infoType', $infoType, PDO::PARAM_STR, 25);
	$sth->bindParam(':buildingType', $buildingType, PDO::PARAM_STR, 25);
	$sth->bindParam(':comment', $comment, PDO::PARAM_STR);
	
	$sth->execute();
	$sth->closeCursor();
	$return = ['message' => 'Successfully added the construction dropdown'];
	return $return;
}
//delete function for construction info dropdowns
function deleteConstructionInfo($conn, $constructInfoID){
	$sql = "DELETE FROM constructioninfo WHERE ConstructInfoID = :constructInfoID";
	$sth = $conn->prepare($sql);
	$sth->bindParam(':constructInfoID', $constructInfoID, PDO::PARAM_INT, 11);
	$sth->execute();
	$sth->closeCursor();
	$return = ['message' => 'Successfully deleted the construction dropdown'];
	return $return;
}
//submits the entire costing report and updates all values, including unchanged ones. 
//Inefficient on database side, but easier.
function submitCostingChanges($conn, $dataArray){
	//do checking of values, make sure okay to submit

	
	$sql = "UPDATE levelfour SET UnitOfMeasure = :unitOfMeasure, Cost = :cost, ExpectedLifespan = :lifespan WHERE LevelFourId = :levelFourID";	
	$sth = $conn->prepare($sql);
	foreach($dataArray as $dataItem){
	
		$sth->bindParam(':levelFourID', $dataItem['levelFourID'], PDO::PARAM_INT, 11);
		$sth->bindParam(':unitOfMeasure', $dataItem['unitOfMeasure'], PDO::PARAM_STR, 45);
		$sth->bindParam(':cost', $dataItem['cost'], PDO::PARAM_INT);
		$sth->bindParam(':lifespan', $dataItem['lifespan'], PDO::PARAM_INT, 11);
		
		$sth->execute();
	}
	$sth->closeCursor();
	$return = ['message' => 'Successfully changed the costing table'];
	return $return;

}
//Submits a new client into system
function submitClient($conn, $firstName, $lastName, $company, $address, $city){

	$sql = "INSERT INTO client (PMFirstName, PMLastName, CompanyName, City, Address) VALUES (:firstName, :lastName, :company, :city, :address)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':firstName', $firstName, PDO::PARAM_STR, 45);
	$sth->bindParam(':lastName', $lastName, PDO::PARAM_STR, 45);
	$sth->bindParam(':company', $company, PDO::PARAM_STR, 45);
	$sth->bindParam(':city', $city, PDO::PARAM_STR, 45);
	$sth->bindParam(':address', $address, PDO::PARAM_STR, 45);
	
	$sth->execute();
	$sth->closeCursor();
	
	
	
	$return = ['message' => 'Successfully added the client'];
	return $return;
}
//submits the entire costing report and updates all values, including unchanged ones. 
//Inefficient on database side, but easier.
function submitClientUpdate($conn, $dataArray){
	$sql = "UPDATE client SET PMFirstName = :firstName, PMLastName = :lastName, CompanyName = :company, Address = :address, City = :city WHERE ClientId = :clientID";	
	
	$sth = $conn->prepare($sql);
	foreach($dataArray as $dataItem){
	
		$sth->bindParam(':clientID', $dataItem['clientID'], PDO::PARAM_INT, 11);
		$sth->bindParam(':firstName', $dataItem['firstName'], PDO::PARAM_STR, 45);
		$sth->bindParam(':lastName', $dataItem['lastName'], PDO::PARAM_STR, 45);
		$sth->bindParam(':company', $dataItem['company'], PDO::PARAM_STR, 45);
		$sth->bindParam(':city', $dataItem['city'], PDO::PARAM_STR, 45);
		$sth->bindParam(':address', $dataItem['address'], PDO::PARAM_STR, 45);
			
		$sth->execute();
	}
	
	$sth->closeCursor();
	$return = ['message' => 'Successfully changed the costing table'];
	return $return;

}
//submit new inspector
function submitInspector($conn, $firstName, $lastName, $email, $phone, $cell){
	$sql = "INSERT INTO inspector (FirstName, LastName, Email, Phone, Cell) VALUES (:firstName, :lastName, :email, :phone, :cell)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':firstName', $firstName, PDO::PARAM_STR, 45);
	$sth->bindParam(':lastName', $lastName, PDO::PARAM_STR, 45);
	$sth->bindParam(':email', $email, PDO::PARAM_STR, 45);
	$sth->bindParam(':phone', $phone, PDO::PARAM_STR, 25);
	$sth->bindParam(':cell', $cell, PDO::PARAM_STR, 25);

	$sth->execute();
	$sth->closeCursor();
	
	$return = ['message' => 'Successfully added the client'];
	return $return;
}
//submits the entire costing report and updates all values, including unchanged ones. 
//Inefficient on database side, but easier.
function submitInspectorUpdate($conn, $dataArray){

	$sql = "UPDATE inspector SET FirstName = :firstName, LastName = :lastName, Email = :email, Phone = :phone, Cell = :cell WHERE InspectorId = :inspectorID";	
	
	$sth = $conn->prepare($sql);
	foreach($dataArray as $dataItem){
	
		$sth->bindParam(':inspectorID', $dataItem['inspectorID'], PDO::PARAM_INT, 11);
		$sth->bindParam(':firstName', $dataItem['firstName'], PDO::PARAM_STR, 45);
		$sth->bindParam(':lastName', $dataItem['lastName'], PDO::PARAM_STR, 45);
		$sth->bindParam(':email', $dataItem['email'], PDO::PARAM_STR, 45);
		$sth->bindParam(':phone', $dataItem['phone'], PDO::PARAM_STR, 25);
		$sth->bindParam(':cell', $dataItem['cell'], PDO::PARAM_STR, 25);
		$sth->execute();
	}
	
	$sth->closeCursor();
	$return = ['message' => 'Successfully changed the costing table'];
	return $return;
}
//Submitting new user
function submitUser($conn, $username, $password, $email, $accessLevel){
	//this salt should be randomly generated and stored, but salting isn't a huge issue ATM (we should never see enough users that it would matter)
	//Currently using the one from the php documentation.
	$salt = '$2a$07$usesomesillystringforsalt$';
	$passwordHash = crypt($password, $salt);
	
	$sql = "INSERT INTO user (Username, Password, Email, AccessLevel) VALUES (:username, :password, :email, :accessLevel)";	
	$sth = $conn->prepare($sql);

	
	$sth->bindParam(':username', $username, PDO::PARAM_STR, 45);
	$sth->bindParam(':password', $passwordHash, PDO::PARAM_STR, 128);
	$sth->bindParam(':email', $email, PDO::PARAM_STR, 90);
	$sth->bindParam(':accessLevel', $accessLevel, PDO::PARAM_INT, 11);
	
	$sth->execute();
	$sth->closeCursor();
	
	$return = ['message' => 'Successfully added the user'];
	return $return;
}
//submits the entire user table and updates most values, including unchanged ones. 
//Inefficient on database side, but easier.
function submitUserUpdate($conn, $dataArray){
	$sql = "UPDATE user SET Username = :username, AccessLevel = :accessLevel, email = :email WHERE UserId = :userID";	
	
	$sth = $conn->prepare($sql);
	foreach($dataArray as $dataItem){
	
		$sth->bindParam(':userID', $dataItem['userID'], PDO::PARAM_INT, 11);
		$sth->bindParam(':username', $dataItem['username'], PDO::PARAM_STR, 45);
		$sth->bindParam(':email', $dataItem['email'], PDO::PARAM_STR, 45);
		$sth->bindParam(':accessLevel', $dataItem['accessLevel'], PDO::PARAM_STR, 45);
		$sth->execute();
	}
	
	$sth->closeCursor();
	$return = ['message' => 'Successfully changed the costing table'];
	return $return;
}
//General function to get the amount of rows in any table (that is in the whitelist)
//Only use internally (eg: this php page)! Vulnerable to sql injection. 
//This should be replaced.
function countRows($conn, $inputTable){

	$table;
	//whitelisting tables that are gettable
	switch($inputTable){
		case "levelone":
			$table = "levelone";
			break;
		case "leveltwo":
			$table = "leveltwo";
			break;
		case "levelthree":
			$table = "levelthree";
			break;
		case "levelfour":
			$table = "levelfour";
			break;
		case "plancomponent":
			$table = "plancomponent";
			break;
		case "plan":
			$table = "plan";
			break;
	}
	
	$sql = "SELECT count(*) FROM $table";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows[0]["count(*)"];
}

//Submits a request to log in.
function submitLogInRequest($conn, $username, $password){

	$sql = "SELECT Password, UserId, AccessLevel, Password FROM user WHERE Username = :username";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':username', $username, PDO::PARAM_STR, 45);
	$sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	//this salt should be randomly generated and stored, but salting isn't a huge issue ATM (we should never see enough users that it would matter)
	//Currently using the one from the php documentation.
	$salt = '$2a$07$usesomesillystringforsalt$';
	if( crypt($password, $salt) == $row['Password']){
		$return = ["msg" => "successfully logged in", "loggedIn" => "true"];
		session_start();
			$_SESSION['loggedIn'] = TRUE;
			$_SESSION['userID'] = $row["UserId"];
			$_SESSION['accessLevel'] = $row["AccessLevel"];
		session_write_close();
	}
	
	else{
		$return = ["msg" => "failed to log in", "loggedIn" => "false"];
	}
	return $return;
	
}

//function to get if someone is logged in
function checkLogIn(){

	session_start();
		if(isset($_SESSION['loggedIn'])){
			$return = $_SESSION['loggedIn'];
		}
		else{
			$return = false;
		}
	session_write_close();
	
	return $return;

}

//function to get the user who is logged in
function getLoggedInID(){

	session_start();
		$return = $_SESSION['userID'];
	session_write_close();
	return $return;
}

//function to get the access level of whoever is logged in
function checkAccessLevel(){

	session_start();
		if(isset($_SESSION['accessLevel'])){
			$return = $_SESSION['accessLevel'];
		}
		else{
			$return = 0;
		}
	session_write_close();
	
	return $return;
}


function getLevelTables($conn, $level){

	$table;
	//whitelisting tables that are gettable
	switch($level){
		case "levelone":
			$table = "levelone";
			break;
		case "leveltwo":
			$table = "leveltwo";
			break;
		case "levelthree":
			$table = "levelthree";
			break;
	}
	
	$sql = "SELECT * FROM $table";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

function getLevelThree($conn){
	$sql = "SELECT * FROM levelthree";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

function getLevelTwo($conn){
	$sql = "SELECT * FROM leveltwo";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

function getLevelOne($conn){
	$sql = "SELECT * FROM levelone";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//checks the level tables for rows with matching names, 
// eg: checking for if a name already exists in the table
// Should be replaced with dedicated requests
// Insecure to SQL injection, switch is marginally better
function getLevelID($conn, $level, $name){

	$table;
	//whitelisting tables that are gettable
	switch($level){
		case "levelone":
			$table = "levelone";
			break;
		case "leveltwo":
			$table = "leveltwo";
			break;
		case "levelthree":
			$table = "levelthree";
			break;
		case "levelfour":
			$table = "levelfour";
			break;
	}
	
	$sql = "SELECT * FROM $table where Name = :name";

	$sth = $conn->prepare($sql);
	$sth->bindParam(':name', $name, PDO::PARAM_STR, 40);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//Function get the reportID of reports you can continue to work on
//currently only gets reports you own
function getViewablePausedReports($conn){
	$sql = "SELECT temporaryreport.PlanId, DatePaused FROM temporaryreport JOIN plan ON temporaryreport.PlanId = plan.PlanId WHERE plan.UserId = :userID";


	$userID = getLoggedInID();

	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':userID', $userID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//gets all user data (besides passwords and internalID)
function getUsers($conn){

	$sql = "SELECT Username, UserId, Email, AccessLevel FROM user";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//gets all client data
function getClients($conn){
	
	$sql = "SELECT * FROM client";
	
	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
	
}

//gets all inspector data
function getInspectors($conn){
	$sql = "SELECT * FROM inspector";
	
	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
	
}

//gets a basic version of all modules (just the level names and IDs for all levels)
function getModulesBasic($conn){
	$sql = "SELECT levelfour.name AS 'levelFourName', levelfour.LevelFourId AS 'levelFourID', levelthree.name AS 'levelThreeName', levelthree.LevelThreeId AS 'levelThreeID',leveltwo.name AS 'levelTwoName', leveltwo.LevelTwoId AS 'levelTwoID', levelone.name AS 'levelOneName', levelone.LevelOneId AS 'levelOneID' FROM `levelfour` JOIN levelthree ON levelfour.LevelThreeId = levelthree.LevelThreeId
	JOIN leveltwo ON levelthree.LevelTwoId = leveltwo.LevelTwoId
	JOIN levelone ON leveltwo.LevelOneId = levelone.LevelOneId";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//gets the costing table version of all the modules, more data that basic version (lifespan, cost, unit of measure, etc);
function getModulesVerbose($conn){
	$sql = "SELECT levelfour.name AS 'levelFourName', levelfour.LevelFourId AS 'levelFourID', levelfour.cost as 'cost', levelfour.ExpectedLifespan AS 'lifespan', levelfour.UnitOfMeasure AS 'unitOfMeasure', levelthree.name AS 'levelThreeName', levelthree.LevelThreeId AS 'levelThreeID',leveltwo.name AS 'levelTwoName', leveltwo.LevelTwoId AS 'levelTwoID', levelone.name AS 'levelOneName', levelone.LevelOneId AS 'levelOneID' FROM `levelfour` JOIN levelthree ON levelfour.LevelThreeId = levelthree.LevelThreeId
	JOIN leveltwo ON levelthree.LevelTwoId = leveltwo.LevelTwoId
	JOIN levelone ON leveltwo.LevelOneId = levelone.LevelOneId";

	$sth = $conn->prepare($sql);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//gets module data but limited to levelfourID's on a list
function getModulesFromList($conn, $list){
	$returnModules = array();
	foreach($list as $listItem){
		$sql = "SELECT * FROM levelfour WHERE LevelFourId = :levelFourID";
	
		$sth = $conn->prepare($sql);
		$sth->bindParam(':levelFourID', $listItem, PDO::PARAM_INT, 11);
		$sth->execute();
		
		$row;
		
		while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
			$row = $r;
		}
		
		$row['physicalDescStandardComments'] = getStandardComment($conn, $listItem, "physicalDesc");
		$row['finAnalysisStandardComments'] = getStandardComment($conn, $listItem, "finAnalysis");
		$row['condAnalysisStandardComments'] = getStandardComment($conn, $listItem, "condAnalysis");
		$row['defAnalysisStandardComments'] = getStandardComment($conn, $listItem, "defAnalysis");
		
		$returnModules[] = $row;
	
	}
	return $returnModules;
}

//gets the plan data for a single report, that is being continued
function getCurrentContinueReport($conn, $planID){
	$sql = "SELECT * FROM plan WHERE PlanId = :planID";

	session_start();
		$_SESSION['planID'] = $planID;
	session_write_close();
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	$row['serviceArray'] = getPlanServices($conn, $planID);
	$row['inspectorArray'] = getInspectedBy($conn, $planID);
	$row['dateArray'] = getDatesInspected($conn, $planID);
	$row['planComponents'] = getPlanComponents($conn, $planID);
	$row['incompleteComponents'] = getIncompleteComponents($conn, $planID);
	//deleteContinueReport($conn, $planID);
	return $row;
}
//gets all the people who were attached to a report (inspectedby)
function getInspectedBy($conn, $planID){

	$sql = "SELECT * FROM inspectedby WHERE planId = :planID";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//gets the dates attached to a report
function getDatesInspected($conn, $planID){

	$sql = "SELECT * FROM dateinspected WHERE planId = :planID";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//gets the services attached a report
function getPlanServices($conn, $planID){

	$sql = "SELECT * FROM planservice WHERE planId = :planID";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//gets all the components that have been saved to a plan
function getPlanComponents($conn, $planID){
	$sql = "SELECT * FROM plancomponent WHERE planId = :planID";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}

//gets just the levelFourID for all components attached a plan
function getFinishedModulesLevelFourId($conn){

	$sql = "SELECT levelFourId FROM plancomponent WHERE planId = :planId";

	session_start();
		$planID = $_SESSION['continueReportID'];
	session_write_close();
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planId', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//gets all the components that were saved (completed or not) to incompletecomponents
function getIncompleteComponents($conn, $planID){

	$sql = "SELECT * FROM incompletecomponents WHERE planId = :planId";
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':planId', $planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	
	//deleteUnfinishedModules($conn, $planID);
	return $rows;
}
//gets all standard comments for a levelFourID
function getStandardComment($conn, $levelFourID, $type){

	$sql = "SELECT StndCommentId, comment FROM stndcomment WHERE LevelFourId = :levelFourID AND type = :type";

	$sth = $conn->prepare($sql);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->bindParam(':type', $type, PDO::PARAM_STR, 45);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;

}
//gets all construction infos (for dropdowns)
function getConstructionInfo($conn, $buildingType){
	if($buildingType == "custom"){
		$sql = "SELECT * FROM constructioninfo ORDER BY InfoType";
	}
	else{
		$sql = "SELECT * FROM constructioninfo WHERE BuildingType = :buildingType ORDER BY InfoType";
	}
	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':buildingType', $buildingType, PDO::PARAM_STR, 25);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;

}
//gets all the reports that a user can read (currently only those you created yourself)
//area that should be extended upon in many ways
function getViewableReports($conn){
	$sql = "SELECT PlanId, plan.Name AS PlanName, CompanyName FROM plan JOIN client ON plan.ClientId = client.ClientId WHERE plan.UserId = :userID";


	$userID = getLoggedInID();

	
	$sth = $conn->prepare($sql);
	$sth->bindParam(':userID', $userID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}




//Function to return user data for Account management.
function getUserData($conn){
        $userID = getLoggedInID();
        $sql ="SELECT * FROM user WHERE userID = " .$userID;
        $sth = $conn->prepare($sql);
        $sth->bindParam(':userID', $userID, PDO::PARAM_INT, 11);
        $sth->execute();
        $row;
        while($r =$sth->fetch(PDO::FETCH_ASSOC)){
            $row= $r;
        }
        
        return $row;
}

function getAccessLevels($conn){

	$sql = "SELECT  title FROM userrole";

	$sth = $conn->prepare($sql);
	$sth->execute();
	$rows = array();
	
	while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $r;
	}
	return $rows;
}
//Function to terminate session.
function submitLogOutRequest(){
     session_start();
     			$_SESSION['loggedIn'] = NULL;
			$_SESSION['userID'] = NULL;
			$_SESSION['accessLevel'] = NULL;
     session_destroy();
                    return true;

}

?>