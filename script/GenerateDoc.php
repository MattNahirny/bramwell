<?php

class GenerateDoc {
    
    function __construct($conn, $planID) {
        $this->conn = $conn;
        $this->planID = $planID;
        $PHPWord;
        $section;
        
    }   


    function getVerbosePlanComponents(){
            $sql = "SELECT PlanComponentId, PlanId, YearAcquired, DeficiencyAnalysis
            ConditionAnalysis, PhysicalCondition, DeficiencyAnalysis, FinancialAnalysis, NumUnits, EffectiveAge, plancomponent.UnitOfMeasure, levelfour.Name AS levelFourName, 
            levelone.Name AS levelOneName, Cost, ExpectedLifespan, DefPotentialDeterioration,
            levelfour.Name = 'levelFourName' FROM plancomponent JOIN levelfour ON plancomponent.LevelFourId = levelfour.LevelFourId
            JOIN levelthree ON levelfour.LevelThreeId = levelthree.LevelThreeId
            JOIN leveltwo ON levelthree.LevelTwoId = leveltwo.LevelTwoId
            JOIN levelone ON leveltwo.LevelOneId = levelone.LevelOneId
            WHERE PlanId = :planID";

            $sth = $this->conn->prepare($sql);
            $sth->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
            $sth->execute();

            $rows = array();

            while($r = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $r;
            }

            $this->componentArray = $rows;

    }

    function getPlan(){
	$sql = "SELECT * FROM plan WHERE PlanId = :planID";

	$sth = $this->conn->prepare($sql);
	$sth->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
        $this->planArray = $row;

}


function getHeader() {
    
    $planArray = $this->planArray;
    	$header = $this->section->createHeader();
	
	$tHeader = $header->addTable();
	
	$tHeader->addRow();
	$tCell = $tHeader->addCell(8000, $this->hCellStyle);
        
	$tCell->addPreserveText('Address: ' . $planArray['Street'] . ', ' . $planArray['City'] . ', BC, ' . $planArray['PostalCode'].'          Page {PAGE} of {NUMPAGES}', $this->smallStyle, 'myParaStyle'); //Address of property here
	$tCell->addText('Client: Strata Corporation ' . $planArray['StrataNumber'] . '						File No. ' . $planArray['PlanId'], $this->smallStyle, 'myParaStyle'); //Client information here
	$tCell->addText(htmlspecialchars('STRATA RESERVE PLAN - a division of Bramwell & Associates Realty Advisors Inc.   Tel: 604-608-6161'), $this->smallStyle, 'myParaStyle'); 
	$tCell->addText('875-355 Burrard Street, Vancouver, BC                                        North America Toll Free: 855-STRATA8 or 855-787-2828', $this->smallStyle, 'myParaStyle'); //this should be the value of whatever Jeremy's office address is
	
        $tCell = $tHeader->addCell(100, $this->hCellStyle);
	$tCell->addImage('SRP.jpg', $this->imageStyle);
} 

function save() {
    	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->PHPWord, 'Word2007');
	$objWriter->save($this->fileName);
}


function setUp() {
    //if (file_exists($fileName)) {
  //  if(false) {
  //          echo "b";return $fileName;
  //  } else {

    
    //load plan and other data for compoenents
    self::getPlan();
    self::getVerbosePlanComponents();
    require_once '../script/PHPWord.php';
    require_once '../script/PHPWord-master/src/PhpWord/Autoloader.php';

    \PhpOffice\PhpWord\Autoloader::register();
    \PhpOffice\PhpWord\Settings::setTempDir(dirname(__FILE__) . '\\temp\\');
    
    $strataNumber = str_replace(" ", "", $this->planArray['StrataNumber']);
    $arrayDate = getdate();
    $root = $this->fileName = ( '../Docs/' . $strataNumber . $this->planArray['PlanId'] . '.' . $arrayDate['year'] . '.docx');
  	
    //initialization of file
    $this->PHPWord = new \PhpOffice\PhpWord\PHPWord();
          
    //most common settings for the file given by Jeremy
    $this->PHPWord->setDefaultFontName('Times New Roman');
    $this->PHPWord->setDefaultFontSize(10);
    $this->PHPWord->addParagraphStyle('myParaStyle',(array('spaceAfter'=>0, 'spaceBefore'=>0)));
    //header image style
    $this->imageStyle = array('width'=>50, 'height'=>50,'align'=>'right');
    $this->tImageStyle = array('width'=>200, 'height'=>200, 'align'=>'center');
    //font style for bold areas
    $this->boldStyle = array('color'=>'000000', 'size'=>10, 'bold'=>true);
    //header style
    $this->smallStyle = array('color'=>'000000', 'size'=>8);
    $this->hCellStyle = array('cellMarginTop'=>0,'cellMarginRight'=>0,'cellMarginLeft'=>0,'cellMarginBottom'=>0);
    //table style with no gridspan (1) All these are done to create a table that can accurately represent Jeremy's info
    $this->tableStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
                                            'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
                                            'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100);
    //gridspan 2			
    $this->doubleStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
                                            'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
                                            'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>2);
    //table style, gridpan of 3
    $this->tripleStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
                                            'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
                                            'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>3);
    //same as above with gridspan of 4
    $this->firstRowStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
                                            'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
                                            'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>4);
    //document style
    $this->sectionStyle = array('marginLeft'=>1450, 'marginRight'=>1450, 'marginTop'=>1450, 'marginBottom'=>1000);   

    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    //}
}



function printNewReport() {
        

}

function getPictureArray($levelFourID) {
	
	$sql = "SELECT * FROM componentpicture WHERE PlanId = :planID AND LevelFourId = :levelFourID";
	$sth = $this->conn->prepare($sql);
	$sth->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
	$sth->bindParam(':levelFourID', $levelFourID, PDO::PARAM_INT, 11);
	$sth->execute();
	
	$prow = array();
	
	while ($p = $sth->fetch(PDO::FETCH_ASSOC)) {
		$prow[] = $p;
	}

	return $prow;
}

function getComponent() {
    
   $componentArray =  $this->componentArray ;
    //all text must be in a 'section' - see documentation of PHPWord
    //$section = $this->PHPWord->createSection($this->sectionStyle);

    $this->section->addTextBreak(1);


    //loop through every component in the array of components that apply to this plan
    for ($x = 0; $x < count($componentArray); $x++){ 

    //begin structure of component page
    $table = $this->section->addTable();
    $table->addRow(0);
    //'guider' cells that define the width of a column all the way down
    $table->addCell(2500);
    $table->addCell(2500);
    $table->addCell(2500);
    $table->addCell(2500);
	
    $table->addRow();
    $cell = $table->addCell(1, $this->firstRowStyle);
    $compID = $componentArray[0]['levelOneName'];

    $cell->addText('COMPONENT G' . ($x+1) . ' - ' . $componentArray[$x]['levelOneName'] . ' - ' . $componentArray[$x]['levelFourName'], $this->boldStyle, 'myParaStyle'); //FORMAT: COMPONENT # - COMPONENT SECTION - COMPONENT NAME style: bold

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Physical Description', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['PhysicalCondition'], 0, 'myParaStyle'); //DB->PHYSICAL DESCRIPTION

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Financial Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['FinancialAnalysis'], 0, 'myParaStyle'); //DB->FINANCIAL ANALYSIS

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Potential Deterioration', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['DefPotentialDeterioration'], 0, 'myParaStyle'); //db info? unsure.

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Condition Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['ConditionAnalysis'], 0, 'myParaStyle'); //db condition analysis


    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Life Cycle Analysis', $this->boldStyle);
    $cell = $table->addCell(1, $this->doubleStyle);
    $cell->addText('Date of Aquisition:', 0, 'myParaStyle');  
    $cell->addText('Expected Lifespan: ', 0, 'myParaStyle');
    $cell->addText('Effective Age: ', 0, 'myParaStyle');
    $cell->addText('Remaining Lifespan: ', 0, 'myParaStyle');
    $cell->addText('Estimated Year of Repair or Replacement:', 0, 'myParaStyle');
    $cell = $table->addCell(1, $this->tableStyle);
    $cell->addText($componentArray[$x]['YearAcquired'], 0, 'myParaStyle'); //DoA
    $cell->addText($componentArray[$x]['ExpectedLifespan'], 0, 'myParaStyle'); //lifespan
    $cell->addText($componentArray[$x]['EffectiveAge'], 0, 'myParaStyle'); //Current Age
    $cell->addText($componentArray[$x]['ExpectedLifespan'] - $componentArray[$x]['EffectiveAge'], 0, 'myParaStyle'); //Remaining Age
    $cell->addText($componentArray[$x]['YearAcquired'] + $componentArray[$x]['ExpectedLifespan'], 0, 'myParaStyle'); //Year of repair

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Unit Quantity and Cost Estimates', $this->boldStyle);
    $cell = $table->addCell(1, $this->doubleStyle);
    $cell->addText('Unit Quantity:', 0, 'myParaStyle');  
    $cell->addText('Cost Estimate: ', 0, 'myParaStyle');
    $cell->addText('Current Repair or Replacement Cost Estimate: ', 0, 'myParaStyle');
    $cell = $table->addCell(1, $this->tableStyle);
    $cell->addText($componentArray[$x]['NumUnits'] . ' ' . $componentArray[$x]['UnitOfMeasure'], 0, 'myParaStyle'); //Quantity
    $cell->addText('$' . number_format($componentArray[$x]['Cost'], 2) . ' per ' . $componentArray[$x]['UnitOfMeasure'], 0, 'myParaStyle'); //Cost
    $cell->addText('$' . number_format($componentArray[$x]['NumUnits'] * $componentArray[$x]['Cost'], 2), 0, 'myParaStyle'); //repair cost / replace cost

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Deficiency Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['DeficiencyAnalysis'], 0, 'myParaStyle'); //deficiency analysis info


    //Loop Logic for pictures, insert picture, define picture size, insert description
    $picArray = self::getPictureArray($x+1);
            for ($y = 0; $y < count($picArray);) {
            $table->addRow();
            $cell = $table->addCell(1, $this->doubleStyle);
            $cell->addImage($picArray[$y]['PictureURI'], $this->tImageStyle);
            $cell->addText($picArray[$y]['Caption']);
            $y++;
            $cell = $table->addCell(1, $this->doubleStyle);
            if ($y < count($picArray)) {
                    $cell->addImage($picArray[$y]['PictureURI'], $this->tImageStyle);
                    $cell->addText($picArray[$y]['Caption']);
                    $y++;
            }
    }
    //end of images


    //only page break if more components
    if ($x+1 < count($componentArray)) {
            $this->section->addPageBreak();
    }
    //end of component loop
    }    
    
}

    function __destruct() {
       print "descruct";
    }
}
