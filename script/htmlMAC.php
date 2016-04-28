<?php
	
	
	if(ISSET($_GET['request']) && getLoggedIn()){
		$request = $_GET['request'];
		
		session_start();
			$accessLevel = $_SESSION['accessLevel']; 
		session_write_close();
		
		switch($request){
			case "main":
				$return = "";
				switch($accessLevel){
					
					case 9: case 8: case 7: case 6: case 5:
						$return .= "<a href='admin.html'>Admin Tools</a>";
					case 4:
						$return .= "<a href='admin.html'>Client/Inspector Tools</a>";
					case 3:
						$return .= "<a href='costing.html'>Adjust Costing</a>";
						$return .= "<a href='addmodule.html'>Add Components</a>";
					case 2:
						$return .= "<a href='continue.html'>Continue Report</a>";
						$return .= "<a href='create.html'>Create Report</a>";
					case 1:
						$return .= "<a href='view.html'>View Report(s)</a>";
				}
				echo $return;
			break;
			
			//For create, it simply checks if the user is signed in and has access, it doesn't hide any html. Ajax expects JSON response
			case "create":
				$return["hasAccess"] = "false";
				if($accessLevel >= 2){
					$return["hasAccess"] = "true";
				}
				
				echo json_encode($return, JSON_FORCE_OBJECT);
			break;
			
			case "addModule":
				$return["hasAccess"] = "false";
				if($accessLevel >= 3){
					$return["hasAccess"] = "true";
				}
				echo json_encode($return, JSON_FORCE_OBJECT);
			break;
			
			case "costing":
				$return["hasAccess"] = "false";
				if($accessLevel >= 3){
					$return["hasAccess"] = "true";
				}
				echo json_encode($return, JSON_FORCE_OBJECT);
			break;
			
			case "admin":
				$return = "";
				switch($accessLevel){
					case 9: case 8: case 7: case 6: case 5:
						$return .= "<div ><input id='createUser' type='button' value='Create User'></input></div>
									<div ><input id='editUsers' type='button' value='Edit Users'></input></div>";
					case 4:
						$return .= "<div><input id='createClient' type='button' value='Create Client'></input></div>
									<div><input id='editClients' type='button' value='Edit Clients'></input></div>
									<div><input id='createInspector' type='button' value='Create Inspector'></input></div>
									<div><input id='editInspectors' type='button' value='Edit Inspector'></input></div>
									<div><input id='editConstructInfo' type='button' value='Construction Dropdowns'></input></div>";
									
				}
				echo $return;
			break;
		}
	}
	
function getLoggedIn(){
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

?>