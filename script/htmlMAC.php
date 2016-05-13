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
					
                                        /*accessLevel
                                         6= Amdinistrator
                                         5= Assistant
                                         4= Estimator
                                         3= Inpsector with Costing
                                         2= Inspector
                                         1= Property Manager* 
                                         */
                                        case 6:
                                                $return .= "<a href='admin.html'>Administrative Tools</a>";
                                                $return .= "<a href='costing.html'>Adjust Costing</a>";
                                                $return .= "<a href='addmodule.html'>Add Components</a>";
                                                $return .= "<a href='continue.html'>Continue Report</a>";
						$return .= "<a href='create.html'>Create Report</a>";
                                                $return .= "<a href='view.html'>View Report(s)</a>";
                                                $return .= "<a href='author.html'>Create/Edit Authors</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html'>Logout</a>";
                                                break;
                                        case 5:
                                                $return .= "<a href= 'admin.html'>Administrative Tools</a>";
                                                $return .= "<a href= 'view.html'>View Report(s)</a>";
                                                $return .= "<a href='author.html>Create/Edit Authors</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html'>Logout</a>";
					case 4:
						$return .= "<a href='admin.html'>Inspector Tools</a>";
                                                $return .= "<a href='costing.html'>Adjust Costing</a>";
                                                $return .= "<a href='addmodule.html'>Add Components</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html'>Logout</a>";
                                                break;
					case 3:
						$return .= "<a href='costing.html'>Adjust Costing</a>";
						$return .= "<a href='addmodule.html'>Add Components</a>";
                                                $return .= "<a href='continue.html'>Continue Report</a>";
						$return .= "<a href='create.html'>Create Report</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html'>Logout</a>";
                                                break;
					case 2:
						$return .= "<a href='continue.html'>Continue Report</a>";
						$return .= "<a href='create.html'>Create Report</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html'>Logout</a>";
                                                break;
					case 1:
						$return .= "<a href='view.html'>View Report(s)</a>";
                                                $return .= "<a href='manageaccount.html'>Account Management</a>";
                                                $return .= "<a href='index.html' onclick>Logout</a>";
                                                break;
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
					case 6: case 5:
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