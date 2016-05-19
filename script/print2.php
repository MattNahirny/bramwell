<?php
error_reporting(E_ALL);
ini_set("display_errors",1 );
include_once("db.php");

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::ATTR_PERSISTENT => false));

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

include_once("GenerateDoc.php");

$generator = new GenerateDoc($conn, 1);
$generator->setUp();
$generator->getComponent();
$generator->getHeader();
$generator->printNewReport();
$generator->save();




exit;

// first function
function printReport($conn, $planID){
	//$componentArray = getVerbosePlanComponents($conn, $planID);
	//$planArray = getPlan($conn, $planID);
	$uri = printNewReport($conn, $componentArray, $planArray, $planID);
	$return = ['msg' => 'Successfully printed the report', 'uri' => $uri];
	return $return;
}




//
//function doit() {
//require_once '../script/PHPWord.php';
//// New Word Document
//$PHPWord = new PHPWord();
//// New portrait section
//$section = $PHPWord->createSection();
//// Define table style arrays
//$styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
//$styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');
//// Define cell style arrays
//$styleCell = array('valign'=>'center');
//$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
//// Define font style for first row
//$fontStyle = array('bold'=>true, 'align'=>'center');
//// Add table style
//$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
//// Add table
//$table = $section->addTable('myOwnTableStyle');
//// Add row
//$table->addRow(900);
//// Add cells
//$table->addCell(2000, $styleCell)->addText('Row 1', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 2', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 3', $fontStyle);
//$table->addCell(2000, $styleCell)->addText('Row 4', $fontStyle);
//$table->addCell(500, $styleCellBTLR)->addText('Row 5', $fontStyle);
//// Add more rows / cells
//for($i = 1; $i <= 10; $i++) {
//	$table->addRow();
//        $aaa = $i * $i;
//	$table->addCell(2000)->addText("$aaa");
//	$table->addCell(2000)->addText("Cell $i");
//	$table->addCell(2000)->addText("Cell $i");
//	$table->addCell(2000)->addText("Cell $i");
//	
//	$text = ($i % 2 == 0) ? 'X' : '';
//	$table->addCell(500)->addText($text);
//}
//// Save File
//$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
//$objWriter->save('../Docs/AdvancedTable.docx');
//}


function printNewReport($conn, $componentArray, $planArray, $planID) {

 
    $strataNumber = str_replace(" ", "", $planArray['StrataNumber']);
	$arrayDate = getdate();
	$root =	$fileName = ( '../Docs/' . $strataNumber . $planArray['PlanId'] . '.' . $arrayDate['year'] . '.docx');

        
	//if (file_exists($fileName)) {
        if(false) {
		echo "b";return $fileName;
	} else {

        require_once '../script/PHPWord.php';

	//initialization of file

	require_once '../script/PHPWord-master/src/PhpWord/Autoloader.php';

	\PhpOffice\PhpWord\Autoloader::register();
    \PhpOffice\PhpWord\Settings::setTempDir(dirname(__FILE__) . '\\temp\\');

	//initialization of file
	$PHPWord = new \PhpOffice\PhpWord\PHPWord();
	//most common settings for the file given by Jeremy
	$PHPWord->setDefaultFontName('Times New Roman');
	$PHPWord->setDefaultFontSize(10);
	$PHPWord->addParagraphStyle('myParaStyle',(array('spaceAfter'=>0, 'spaceBefore'=>0)));
	//header image style
	$imageStyle = array('width'=>50, 'height'=>50,'align'=>'right');
	$tImageStyle = array('width'=>200, 'height'=>200, 'align'=>'center');
	//font style for bold areas
	$boldStyle = array('color'=>'000000', 'size'=>10, 'bold'=>true);
	//header style
	$smallStyle = array('color'=>'000000', 'size'=>8);
	$hCellStyle = array('cellMarginTop'=>0,'cellMarginRight'=>0,'cellMarginLeft'=>0,'cellMarginBottom'=>0);
	//table style with no gridspan (1) All these are done to create a table that can accurately represent Jeremy's info
	$tableStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
						'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
						'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100);
	//gridspan 2			
	$doubleStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
						'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
						'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>2);
	//table style, gridpan of 3
	$tripleStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
						'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
						'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>3);
	//same as above with gridspan of 4
	$firstRowStyle = array('borderTopColor'=>'000000', 'borderLeftColor'=>'000000', 'borderRightColor'=>'000000', 'borderBottomColor'=>'000000',
						'borderTopSize'=>6, 'borderLeftSize'=>6, 'borderBottomSize'=>6, 'borderRightSize'=>6,
						'cellMarginTop'=>100, 'cellMarginBottom'=>100, 'cellMarginRight'=>100, 'cellMarginLeft'=>100, 'gridSpan'=>4);
	//document style
	$sectionStyle = array('marginLeft'=>1450, 'marginRight'=>1450, 'marginTop'=>1450, 'marginBottom'=>1000);
	//all text must be in a 'section' - see documentation of PHPWord
	$section = $PHPWord->createSection($sectionStyle);
        

        
        
	$section->addTextBreak(1);

	
	//loop through every component in the array of components that apply to this plan
	for ($x = 0; $x < count($componentArray); $x++){ 
	
	//begin structure of component page
	$table = $section->addTable();
	$table->addRow(0);
	//'guider' cells that define the width of a column all the way down
	$table->addCell(2500);
	$table->addCell(2500);
	$table->addCell(2500);
	$table->addCell(2500);
	
	$table->addRow();
	$cell = $table->addCell(1, $firstRowStyle);
	$compID = $componentArray[0]['levelOneName'];

	$cell->addText('COMPONENT G' . ($x+1) . ' - ' . $componentArray[$x]['levelOneName'] . ' - ' . $componentArray[$x]['levelFourName'], $boldStyle, 'myParaStyle'); //FORMAT: COMPONENT # - COMPONENT SECTION - COMPONENT NAME style: bold

	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Physical Description', $boldStyle);
	$table->addCell(1, $tripleStyle)->addText($componentArray[$x]['PhysicalCondition'], 0, 'myParaStyle'); //DB->PHYSICAL DESCRIPTION
	
	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Financial Analysis', $boldStyle);
	$table->addCell(1, $tripleStyle)->addText($componentArray[$x]['FinancialAnalysis'], 0, 'myParaStyle'); //DB->FINANCIAL ANALYSIS

	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Potential Deterioration', $boldStyle);
	$table->addCell(1, $tripleStyle)->addText($componentArray[$x]['DefPotentialDeterioration'], 0, 'myParaStyle'); //db info? unsure.
	
	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Condition Analysis', $boldStyle);
	$table->addCell(1, $tripleStyle)->addText($componentArray[$x]['ConditionAnalysis'], 0, 'myParaStyle'); //db condition analysis
	
	
	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Life Cycle Analysis', $boldStyle);
	$cell = $table->addCell(1, $doubleStyle);
	$cell->addText('Date of Aquisition:', 0, 'myParaStyle');  
	$cell->addText('Expected Lifespan: ', 0, 'myParaStyle');
	$cell->addText('Effective Age: ', 0, 'myParaStyle');
	$cell->addText('Remaining Lifespan: ', 0, 'myParaStyle');
	$cell->addText('Estimated Year of Repair or Replacement:', 0, 'myParaStyle');
	$cell = $table->addCell(1, $tableStyle);
	$cell->addText($componentArray[$x]['YearAcquired'], 0, 'myParaStyle'); //DoA
	$cell->addText($componentArray[$x]['ExpectedLifespan'], 0, 'myParaStyle'); //lifespan
	$cell->addText($componentArray[$x]['EffectiveAge'], 0, 'myParaStyle'); //Current Age
	$cell->addText($componentArray[$x]['ExpectedLifespan'] - $componentArray[$x]['EffectiveAge'], 0, 'myParaStyle'); //Remaining Age
	$cell->addText($componentArray[$x]['YearAcquired'] + $componentArray[$x]['ExpectedLifespan'], 0, 'myParaStyle'); //Year of repair
	
	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Unit Quantity and Cost Estimates', $boldStyle);
	$cell = $table->addCell(1, $doubleStyle);
	$cell->addText('Unit Quantity:', 0, 'myParaStyle');  
	$cell->addText('Cost Estimate: ', 0, 'myParaStyle');
	$cell->addText('Current Repair or Replacement Cost Estimate: ', 0, 'myParaStyle');
	$cell = $table->addCell(1, $tableStyle);
	$cell->addText($componentArray[$x]['NumUnits'] . ' ' . $componentArray[$x]['UnitOfMeasure'], 0, 'myParaStyle'); //Quantity
	$cell->addText('$' . number_format($componentArray[$x]['Cost'], 2) . ' per ' . $componentArray[$x]['UnitOfMeasure'], 0, 'myParaStyle'); //Cost
	$cell->addText('$' . number_format($componentArray[$x]['NumUnits'] * $componentArray[$x]['Cost'], 2), 0, 'myParaStyle'); //repair cost / replace cost
	
	$table->addRow();
	$table->addCell(1, $tableStyle)->addText('Deficiency Analysis', $boldStyle);
	$table->addCell(1, $tripleStyle)->addText($componentArray[$x]['DeficiencyAnalysis'], 0, 'myParaStyle'); //deficiency analysis info

	
	//Loop Logic for pictures, insert picture, define picture size, insert description
	$picArray = getPictureArray($conn, $planID, $x+1);
		for ($y = 0; $y < count($picArray);) {
		$table->addRow();
		$cell = $table->addCell(1, $doubleStyle);
		$cell->addImage($picArray[$y]['PictureURI'], $tImageStyle);
		$cell->addText($picArray[$y]['Caption']);
		$y++;
		$cell = $table->addCell(1, $doubleStyle);
		if ($y < count($picArray)) {
			$cell->addImage($picArray[$y]['PictureURI'], $tImageStyle);
			$cell->addText($picArray[$y]['Caption']);
			$y++;
		}
	}
	//end of images

	
	//only page break if more components
	if ($x+1 < count($componentArray)) {
		$section->addPageBreak();
	}
	//end of component loop
	}
	
        
        //
        //
	
//$footer = $section->createFooter();
//$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));
//HEADER INFO
//	$header = $section->createHeader();
//	
//	$tHeader = $header->addTable();
//	
//	$tHeader->addRow();
//	$tCell = $tHeader->addCell(8000, $hCellStyle);
//        
//	$tCell->addPreserveText('Address: ' . $planArray['Street'] . ', ' . $planArray['City'] . ', BC, ' . $planArray['PostalCode'].'          Page {PAGE} of {NUMPAGES}', $smallStyle, 'myParaStyle'); //Address of property here
//	$tCell->addText('Client: Strata Corporation ' . $planArray['StrataNumber'] . '						File No. ' . $planArray['PlanId'], $smallStyle, 'myParaStyle'); //Client information here
//	$tCell->addText(htmlspecialchars('STRATA RESERVE PLAN - a division of Bramwell & Associates Realty Advisors Inc.   Tel: 604-608-6161'), $smallStyle, 'myParaStyle'); 
//	$tCell->addText('875-355 Burrard Street, Vancouver, BC                                        North America Toll Free: 855-STRATA8 or 855-787-2828', $smallStyle, 'myParaStyle'); //this should be the value of whatever Jeremy's office address is
//	
//        $tCell = $tHeader->addCell(100, $hCellStyle);
//	$tCell->addImage('SRP.jpg', $imageStyle);
	
	//END HEADER

	//save format, and save to-do: dynamic document names
//	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'Word2007');
//
//	$objWriter->save($fileName);
	
	//return filename for redirect
      
	//return $fileName;
	}
}


//function getPlan($conn, $planID){
//	$sql = "SELECT * FROM plan WHERE PlanId = :planID";
//
//	$sth = $conn->prepare($sql);
//	$sth->bindParam(':planID', $planID, PDO::PARAM_INT, 11);
//	$sth->execute();
//	
//	$row = $sth->fetch(PDO::FETCH_ASSOC);
//	return $row;
//}





?>