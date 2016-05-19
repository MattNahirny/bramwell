<?php


//get data from plan
function getPlan($conn, $planId){
	$sql = "SELECT * FROM plan WHERE PlanId = :PlanId";

	$sth = $conn->prepare($sql);
	$sth->bindParam(':PlanId', $planId, PDO::PARAM_INT, 11);
	$sth->execute();
	
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        $row['ConstructionInflationRate'] = "3.50"; 
        $row['InverstmentsInterasteRate'] = "1.50"; 
        $row['FileNumber'] = "1317-14-SU-BL-DR"; 

        if($row['ClientId'] == 1) {
            $row['xxx'] = "xxx"; 
        }

        else {
            $clientInfor = getClient($conn, $ClientId);
            $row['PMFirstName'] = "xxx"; 
            $row['PMLastName'] = "xxx"; 
            $row['CompanyName'] = "xxx"; 
            $row['City'] = "xxx"; 
            $row['Address'] = "xxx"; 
        }
           
        
         
	return $row;
}

function getClient($conn, $ClientId) {
    
    	$sql = "SELECT * FROM client WHERE ClientId = :ClientId";

	$sth = $conn->prepare($sql);
	$sth->bindParam(':ClientId', $ClientId, PDO::PARAM_INT, 11);
	$sth->execute();
	
        $row = $sth->fetch(PDO::FETCH_ASSOC);

         
	return $row;
}

function getViewableReports($conn){
	$sql = "SELECT PlanId, plan.Name AS PlanName, CompanyName "
                . "FROM plan JOIN client ON plan.ClientId = client.ClientId WHERE plan.UserId = :userID";
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


    function getVerbosePlanComponents($conn, $planId){
            $sql = "SELECT PlanComponentId, PlanId, YearAcquired, DeficiencyAnalysis
            ConditionAnalysis, PhysicalCondition, DeficiencyAnalysis, FinancialAnalysis, NumUnits, EffectiveAge, plancomponent.UnitOfMeasure, levelfour.Name AS levelFourName, 
            levelone.Name AS levelOneName, Cost, ExpectedLifespan, DefPotentialDeterioration,
            levelfour.Name = 'levelFourName' 
            FROM plancomponent JOIN levelfour ON plancomponent.LevelFourId = levelfour.LevelFourId
            JOIN levelthree ON levelfour.LevelThreeId = levelthree.LevelThreeId
            JOIN leveltwo ON levelthree.LevelTwoId = leveltwo.LevelTwoId
            JOIN levelone ON leveltwo.LevelOneId = levelone.LevelOneId
            WHERE PlanId = :planID";

            $sth = $conn->prepare($sql);
            $sth->bindParam(':planID', $planId, PDO::PARAM_INT, 11);
            $sth->execute();

            $rows = array();

            while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $r;
            }

            return $rows;
    }
    
    
    
     function getInspectionDates($conn, $planId){
            $sql = "SELECT * FROM dateinspected
            WHERE PlanId = :planID";

            $sth = $conn->prepare($sql);
            $sth->bindParam(':planID', $planId, PDO::PARAM_INT, 11);
            $sth->execute();

            $rows = array();

            while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $r;
            }

            return $rows;
    }   
/*
FileNumber




 */
    
    
    // ALTER TABLE `client` ADD `Title` VARCHAR(100) NOT NULL AFTER `PMLastName`;