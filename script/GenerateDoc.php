<?php
include_once("Util.php");
class GenerateDoc {
    
    function __construct($conn, $planID) {
        $this->conn = $conn;
        $this->planID = $planID;
        $PHPWord;
        $section;
        define("TEMPLATE_IMG_PATH",     "../image/img2/");
        
        $this->arrPlan = null;
        $this->arrComponent = null;
        
    }   


function header() {
    
    $header = $this->section->AddHeader();

    $tHeader = $header->addTable(array('width'=>110, 'cellMargin'=>0));
    
    $tHeader->addRow();
    $tCell = $tHeader->addCell(800);
    $tCell->addText('Address:', $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(4400);
    $tCell->addText($this->arrPlan['Street'] . ', ' . $this->arrPlan['City'] . ', BC', $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(2700);
    $tCell->addText(htmlspecialchars('.'), $this->smallStyleW, 'compTableP');
    $tCell = $tHeader->addCell(2800);
    $tCell->addPreserveText('Page {PAGE} of {NUMPAGES}', $this->hCellStyle9, 'compTablePR'); 
    $tCell = $tHeader->addCell(1100,array('vMerge' => 'restart'));
    $tCell->addImage(TEMPLATE_IMG_PATH.'SRP.jpg', $this->imageStyle);
    
    
    $tHeader->addRow();
    $tCell = $tHeader->addCell(800);
    $tCell->addText('Client: ', $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(2400);
    $tCell->addText('Strata Corporation ' . $this->arrPlan['StrataNumber'], $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(2700);
    $tCell->addText(htmlspecialchars('.'), $this->smallStyleW, 'compTableP');
    $tCell = $tHeader->addCell(2400);
    $tCell->addText('File: ' . $this->arrPlan['FileNumber'], $this->hCellStyle9, 'compTablePR');
    $tCell = $tHeader->addCell(150,array('vMerge' => 'continue'));
    
    
    //smallStyle
    $tHeader->addRow();
    $tCell = $tHeader->addCell(800,array('gridSpan'=>3));
    $tCell->addText(htmlspecialchars('STRATA RESERVE PLAN - a division of Bramwell & Associates Realty Advisors Inc.'), $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(2400);
    $tCell->addText('Tel: 604-608-6161', $this->hCellStyle9, 'compTablePR');
    $tCell = $tHeader->addCell(150,array('vMerge' => 'continue'));
    
    $tHeader->addRow();
    $tCell = $tHeader->addCell(800, array('gridSpan'=>2));
    $tCell->addText('1000–355 Burrard Street, Vancouver, BC V6C 2G8', $this->hCellStyle9, 'compTableP');
    $tCell = $tHeader->addCell(2400, array('gridSpan'=>2));
    $tCell->addText('North America Toll Free: 855-STRATA8 or 855-787-2828', $this->hCellStyle9, 'compTablePR');
    $tCell = $tHeader->addCell(150,array('vMerge' => 'continue'));


    /*
    $tHeader->addRow();
    $tCell = $tHeader->addCell(8000, $this->hCellStyle);

    $tCell->addPreserveText('Address: ' . $this->arrPlan['Street'] . ', ' . $this->arrPlan['City'] . ', BC, ' . Util::formatZIP($this->arrPlan['PostalCode']).'          Page {PAGE} of {NUMPAGES}', $this->smallStyle, 'myParaStyle'); //Address of property here
    $tCell->addText('Client: Strata Corporation ' . $this->arrPlan['StrataNumber'] . '						File No. ' . $this->arrPlan['PlanId'], $this->smallStyle, 'myParaStyle'); //Client information here
    $tCell->addText(htmlspecialchars('STRATA RESERVE PLAN - a division of Bramwell & Associates Realty Advisors Inc.   Tel: 604-608-6161'), $this->smallStyle, 'myParaStyle'); 
    $tCell->addText('875-355 Burrard Street, Vancouver, BC                                        North America Toll Free: 855-STRATA8 or 855-787-2828', $this->smallStyle, 'myParaStyle'); //this should be the value of whatever Jeremy's office address is

    $tCell = $tHeader->addCell(100, $this->hCellStyle);
    $tCell->addImage(TEMPLATE_IMG_PATH.'SRP.jpg', $this->imageStyle);
     */ 
 
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
    //self::getPlan();
    //self::getVerbosePlanComponents();
    require_once '../script/PHPWord.php';
    require_once '../script/PHPWord-master/src/PhpWord/Autoloader.php';

    \PhpOffice\PhpWord\Autoloader::register();
    \PhpOffice\PhpWord\Settings::setTempDir(dirname(__FILE__) . '\\temp\\');
    
    $strataNumber = str_replace(" ", "", $this->arrPlan['StrataNumber']);
    $arrayDate = getdate();
    $root = $this->fileName = ( '../Docs/' . $strataNumber . $this->arrPlan['PlanId'] . '.' . $arrayDate['year'] . '.docx');
  	
    //initialization of file
    $this->PHPWord = new \PhpOffice\PhpWord\PHPWord();
          
    //most common settings for the file given by Jeremy
    $this->PHPWord->setDefaultFontName('Times New Roman');
    $this->PHPWord->setDefaultFontSize(10);
    $this->PHPWord->addParagraphStyle('myParaStyle',(array('align'=>'justify','spaceAfter'=>100, 'spaceBefore'=>0)));

     $this->PHPWord->addParagraphStyle('compTableP',(array('align'=>'justify','spaceAfter'=>0, 'spaceBefore'=>0)));
    
     $this->PHPWord->addParagraphStyle('compTablePR',(array('align'=>'right','spaceAfter'=>0, 'spaceBefore'=>0)));
     $this->PHPWord->addParagraphStyle('compTableCenter',(array('align'=>'center','spaceAfter'=>0, 'spaceBefore'=>0)));
     
    //cover imagem
    $this->imageCoverH = array('width'=>200, 'height'=>100, 'align'=>'center');
    $this->imageCoverM = array('width'=>400, 'height'=>200, 'align'=>'center');
    
    $this->PHPWord->addFontStyle('coverTitle', array('color'=>'000000', 'size'=>18, 'bold'=>true));
    $this->PHPWord->addParagraphStyle('coverTitleP',(array('align'=>'center','spaceAfter'=>150, 'spaceBefore'=>250)));

    $this->PHPWord->addFontStyle('coverText', array('color'=>'000000', 'size'=>12, 'bold'=>false));
    $this->PHPWord->addParagraphStyle('coverTextP',(array('align'=>'center','spaceAfter'=>150, 'spaceBefore'=>300)));  
    $this->PHPWord->addFontStyle('coverTextRight', array('color'=>'000000','align'=>'right', 'size'=>12, 'bold'=>false));
    
    $this->PHPWord->addFontStyle('coverTextB14', array('color'=>'000000', 'size'=>14, 'bold'=>true));
    $this->PHPWord->addParagraphStyle('coverTextB14P',(array('align'=>'center')));  
    
    $this->PHPWord->addFontStyle('coverTextB12', array('color'=>'000000', 'size'=>12, 'bold'=>true));
    $this->PHPWord->addParagraphStyle('coverTextB12P',(array('align'=>'center')));  
    
    $this->PHPWord->addFontStyle('regular11', array('color'=>'000000', 'size'=>11, 'bold'=>false));
    $this->PHPWord->addParagraphStyle('regular11PR',(array('align'=>'right','spaceAfter'=>100, 'spaceBefore'=>0)));
    
    $this->PHPWord->addFontStyle('regular12', array('color'=>'000000', 'size'=>12, 'bold'=>false));
    $this->PHPWord->addParagraphStyle('regular12PR',(array('align'=>'right','spaceAfter'=>100, 'spaceBefore'=>0)));

    $this->PHPWord->addFontStyle('coverTextRed', array('color'=>'FF0000', 'size'=>12, 'bold'=>false));

    $this->PHPWord->addFontStyle('regular12Red', array('color'=>'FF0000', 'size'=>12, 'bold'=>false));
    $this->PHPWord->addFontStyle('coverTextRedRight', array('color'=>'FF0000', 'align'=>'right', 'size'=>12, 'bold'=>false));
    
    //regular text / paragraphs
    $this->PHPWord->addParagraphStyle('paragraphRegularC', array('color'=>'000000', 'align'=>'center', 'size'=>12));
    $this->PHPWord->addParagraphStyle('paragraphRegularJ', array('color'=>'000000', 'align'=>'justify', 'size'=>12));
    $this->PHPWord->addFontStyle('styleRegular', array('color'=>'000000', 'align'=>'justify', 'size'=>12));
    $this->PHPWord->addFontStyle('styleRegularBold', array('color'=>'000000', 'align'=>'justify', 'size'=>12, 'bold'=>true));
    $this->PHPWord->addFontStyle('regular10Bold', array('color'=>'000000', 'align'=>'justify', 'size'=>10, 'bold'=>true));
    $this->PHPWord->addParagraphStyle('paragraphRegular', array('color'=>'000000', 'align'=>'justify', 'size'=>12,'spaceBefore' => 5 * 20, 'spaceAfter' => 10 * 20));
    $this->PHPWord->addFontStyle('styleRegularTitle', array('color'=>'000000', 'align'=>'justify', 'size'=>14, 'bold'=>true));
    $this->PHPWord->addFontStyle('styleRegularBoldItalic', array('color'=>'000000', 'align'=>'justify', 'size'=>12, 'bold'=>true, 'italic'=>true));
    $this->PHPWord->addFontStyle('styleRegularUnderline', array('color'=>'000000', 'align'=>'justify', 'size'=>12, 'underline'=>'single'));
    $this->PHPWord->addParagraphStyle('paragraphRegularR', array('color'=>'000000', 'align'=>'right', 'size'=>12,'spaceBefore' => 5 * 20, 'spaceAfter' => 10 * 20));
    $this->PHPWord->addParagraphStyle('paragraphRegularL', array('color'=>'000000', 'align'=>'left', 'size'=>12,'spaceBefore' => 5 * 20, 'spaceAfter' => 10 * 20));
    
    $this->PHPWord->addFontStyle('styleRegularWhite', array('color'=>'FFFFFF', 'align'=>'center', 'size'=>11));
    $this->PHPWord->addFontStyle('styleRegularTitleWhite', array('color'=>'FFFFFF', 'align'=>'center', 'size'=>14, 'bold'=>true));

        $this->PHPWord->addFontStyle('regular10BoldC', array('color'=>'000000', 'align'=>'center', 'size'=>10, 'bold'=>true));

    
    $this->PHPWord->addFontStyle('styleRegularItalic', array('color'=>'000000', 'align'=>'justify', 'size'=>12, 'bold'=>false, 'italic'=>true));
  
    //hang
    // 
$this->PHPWord->addParagraphStyle('regularHaning', array('keepLines' => true,'align'=>'justify', 'indentation' => array('left' => 240, 'hanging' => 640)));    
$this->PHPWord->addParagraphStyle('regularHaning2', array('keepLines' => true,'align'=>'justify', 'indentation' => array('left' => 550, 'hanging' => 340)));    
$this->PHPWord->addParagraphStyle('regularHaning3', array('keepLines' => true,'align'=>'justify', 'indentation' => array('left' =>1500, 'hanging' => 1500)));    
  
    //small caps 
    
    $this->PHPWord->addFontStyle('styleRegularSC', array('smallCaps'=>true,'color'=>'000000', 'align'=>'justify', 'size'=>12));

    $this->PHPWord->addFontStyle('styleRegularSCB', array('smallCaps'=>true,'bold'=>true,'color'=>'000000', 'align'=>'justify', 'size'=>12));

    
           $this->PHPWord->addParagraphStyle('paragraphRegularIdent1', array('indent'=>1,'color'=>'000000', 'align'=>'justify', 'size'=>12));
      $this->PHPWord->addParagraphStyle('paragraphRegularIdent2', array('indent'=>2,'color'=>'000000', 'align'=>'justify', 'size'=>12));         

     
     $this->PHPWord->addFontStyle('graphArea', array('color'=>'000000', 'size'=>36, 'bold'=>true));
    $this->PHPWord->addParagraphStyle('graphAreaP',(array('align'=>'center','spaceAfter'=>300, 'spaceBefore'=>300)));
    //foooter
    
    $this->PHPWord->addParagraphStyle('footerP',(array('align'=>'center', 'spaceBefore' => 5 * 1, 'spaceAfter' => 10 * 1))); 
    
    //citation
    $this->PHPWord->addFontStyle('styleRegularCit', array('color'=>'000000', 'align'=>'justify', 'size'=>12, 'italic'=>true));
$this->PHPWord->addParagraphStyle('paragraphRegularCit', array('indent'=>1,'color'=>'000000', 'align'=>'justify', 'size'=>12));   
$this->PHPWord->addParagraphStyle('paragraphRegularCit2', array('indent'=>2,'color'=>'000000', 'align'=>'justify', 'size'=>12));  

    //header image style
    $this->imageStyle = array('width'=>50, 'height'=>50,'align'=>'right');
    $this->tImageStyle = array('width'=>200, 'height'=>200, 'align'=>'center');
    //font style for bold areas
    $this->boldStyle = array('color'=>'000000', 'size'=>10, 'bold'=>true);
    //header style
    $this->smallStyle = array('color'=>'000000', 'size'=>8);
    $this->smallStyleW = array('color'=>'FFFFFF', 'size'=>8);
    $this->hCellStyle9 = array('cellMarginTop'=>0,'cellMarginRight'=>0,'cellMarginLeft'=>0,'cellMarginBottom'=>0, 'size'=>8);
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
    //$this->sectionStyle = array('marginLeft'=>1450, 'marginRight'=>1450, 'marginTop'=>1450, 'marginBottom'=>1000);  
    

    $this->sectionStyleLandscape = array('orientation'=>'landscape',
        'pageSizeW'=>15840, 'pageSizeH'=>12240 ,'marginLeft'=>720, 'marginRight'=>720, 'marginTop'=>720, 'marginBottom'=>720);
    
    $this->sectionStyle = array('pageSizeW'=>12240, 'pageSizeH'=> 15840,'marginLeft'=>1440, 'marginRight'=>1440, 'marginTop'=>1440, 'marginBottom'=>1440);  
    
    $this->sectionStyle = array('pageSizeW'=>12240, 'pageSizeH'=> 15840,'marginLeft'=>1440, 'marginRight'=>1440, 'marginTop'=>1440, 'marginBottom'=>1440);  
    //$this->sectionStyle = array('paperSize'=>'Letter','marginLeft'=>1440, 'marginRight'=>1071, 'marginTop'=>1440, 'marginBottom'=>1440);  
//
    
    
    $this->PHPWord->addNumberingStyle(
    'hNum',
    array('type' => 'multilevel', 'levels' => array(
        array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'),
        array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'),
        array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'),
        )
    )
);
$this->PHPWord->addTitleStyle('H1', array('size' => 14, 'bold'=>true), array('numStyle' => 'hNum', 'numLevel' => 0,'spaceAfter'=>300, 'spaceBefore'=>300));
$this->PHPWord->addTitleStyle('H2', array('size' => 12, 'bold'=>true), array('numStyle' => 'hNum', 'numLevel' => 1,'spaceAfter'=>300, 'spaceBefore'=>300));
$this->PHPWord->addTitleStyle('H3', array('size' => 10, 'bold'=>true), array('numStyle' => 'hNum', 'numLevel' => 2));

//title w/o number
$this->PHPWord->addTitleStyle('H1N', array('size' => 14, 'bold'=>true), array('align' => 'center','numStyle' => '', 'numLevel' => 0,'spaceAfter'=>300, 'spaceBefore'=>300));
   // $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    
$FontStyleTitle1 = array ( 'size' => 14, 'color' => '000000', 'bold' => true);
$FontStyleTitle2 = array ( 'size' => 16, 'color' => '666666', 'italic' => true);  
    // Define the Title paragraph styles
// Points in doc * 20
$ParagraphStyleTitle1 = array ( 'align' => 'center', 'spaceBefore' => 5 * 20, 'spaceAfter' => 10 * 20);
$ParagraphStyleTitle2 = array ( 'align' => 'left', 'spaceBefore' => 4 * 20, 'spaceAfter' => 8 * 20);

// Add Title Styles
$this->PHPWord->addTitleStyle (1, $FontStyleTitle1, $ParagraphStyleTitle1);
$this->PHPWord->addTitleStyle (2, $FontStyleTitle2, $ParagraphStyleTitle2);

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

function component() {
    
   $componentArray =  $this->arrComponent;

    //loop through every component in the array of components that apply to this plan
   $count = 1;
    for ($x = 0; $x < count($componentArray); $x++){ 
        
    ///aki o
    $styleTable = array('cellMargin'=>80, 'align'=>'center');
    $styleFirstRow = array('borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
    $this->PHPWord->addTableStyle('myOwnTableStyleComp', $styleTable, $styleFirstRow);
    $rowStyle = array('cantSplit'=>true);
    //begin structure of component page
    $table = $this->section->addTable('myOwnTableStyleComp');
    $table->addRow(0);
    //'guider' cells that define the width of a column all the way down
    $table->addCell(2000);
    $table->addCell(3000);
    $table->addCell(2500);
    $table->addCell(2500);
	
    $table->addRow();
    $cell = $table->addCell(1, $this->firstRowStyle);
    $compID = $componentArray[0]['levelOneName'];

    $cell->addText('COMPONENT ' . ($x+1) . ' - ' . $componentArray[$x]['levelOneName'] . ' - ' . $componentArray[$x]['levelFourName'], $this->boldStyle, 'myParaStyle'); //FORMAT: COMPONENT # - COMPONENT SECTION - COMPONENT NAME style: bold

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Physical Description', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['PhysicalCondition'], 0, 'compTableP'); //DB->PHYSICAL DESCRIPTION

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Financial Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['FinancialAnalysis'], 0, 'compTableP'); //DB->FINANCIAL ANALYSIS

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Potential Deterioration', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['DefPotentialDeterioration'], 0, 'compTableP'); //db info? unsure.

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Condition Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['ConditionAnalysis'], 0, 'compTableP'); //db condition analysis


    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Life Cycle Analysis', $this->boldStyle);
    $cell = $table->addCell(1, $this->doubleStyle);
    $cell->addText('Date of Aquisition:', 0, 'compTableP');  
    $cell->addText('Expected Lifespan: ', 0, 'compTableP');
    $cell->addText('Effective Age: ', 0, 'compTableP');
    $cell->addText('Remaining Lifespan: ', 0, 'compTableP');
    $cell->addText('Estimated Year of Repair or Replacement:', 0, 'compTableP');
    $cell = $table->addCell(1, $this->tableStyle);
    $cell->addText($componentArray[$x]['YearAcquired'], 0, 'compTableP'); //DoA
    $cell->addText($componentArray[$x]['ExpectedLifespan'].' years', 0, 'compTableP'); //lifespan
    $cell->addText($componentArray[$x]['EffectiveAge'].' years', 0, 'compTableP'); //Current Age
    $cell->addText($componentArray[$x]['ExpectedLifespan'] - $componentArray[$x]['EffectiveAge'].' years', 0, 'compTableP'); //Remaining Age
    $cell->addText($componentArray[$x]['YearAcquired'] + $componentArray[$x]['ExpectedLifespan'], 0, 'compTableP'); //Year of repair

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Unit Quantity and Cost Estimates', $this->boldStyle);
    $cell = $table->addCell(1, $this->doubleStyle);
    $cell->addText('Unit Quantity:', 0, 'compTableP');  
    $cell->addText('Cost Estimate: ', 0, 'compTableP');
    $cell->addText('Current Repair or Replacement Cost Estimate: ', 0, 'compTableP');
    $cell = $table->addCell(1, $this->tableStyle);
    $cell->addText(number_format($componentArray[$x]['NumUnits']) . ' ' . $componentArray[$x]['UnitOfMeasure'], 0, 'compTableP'); //Quantity
    $cell->addText('$' . number_format($componentArray[$x]['Cost'], 2) . ' per ' . $componentArray[$x]['UnitOfMeasure'], 0, 'compTableP'); //Cost
    $cell->addText('$' . number_format($componentArray[$x]['NumUnits'] * $componentArray[$x]['Cost'], 2), 0, 'compTableP'); //repair cost / replace cost

    $table->addRow();
    $table->addCell(1, $this->tableStyle)->addText('Deficiency Analysis', $this->boldStyle);
    $table->addCell(1, $this->tripleStyle)->addText($componentArray[$x]['DeficiencyAnalysis'], 0, 'compTableP'); //deficiency analysis info


    //Loop Logic for pictures, insert picture, define picture size, insert description
    $picArray = self::getPictureArray($x+1);
            for ($y = 0; $y < count($picArray);) {
            $table->addRow('',$rowStyle);
            $cell = $table->addCell(1, $this->doubleStyle);
            $cell->addImage($picArray[$y]['PictureURI'], $this->tImageStyle);
            $cell->addText('Figure: '.$count.': '.$picArray[$y]['Caption'],'regular10Bold','compTableCenter');
            $y++;
            $count++;
            $cell = $table->addCell(1, $this->doubleStyle);
            if ($y < count($picArray)) {
                    $cell->addImage($picArray[$y]['PictureURI'], $this->tImageStyle);
                    $cell->addText('Figure: '.$count.': '.$picArray[$y]['Caption'],'regular10Bold','compTableCenter');
                    $y++;
                    $count++;
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

function cover() {

    $this->section = $this->PHPWord->createSection($this->sectionStyle);

    
 
    $header = $this->section->createHeader();
    $header->addImage(TEMPLATE_IMG_PATH.'header.jpg', $this->imageCoverH);
    $this->section->addText("");
    
    $this->section->addText("Depreciation Report",'coverTitle', 'coverTitleP');
    
    $this->section->addText("Strata Corporation ".$this->arrPlan["StrataNumber"]." – ".$this->arrPlan["Name"],'coverText', 'coverTextP');
    $this->section->addText($this->arrPlan["Street"].", ".$this->arrPlan["City"].", ".$this->arrPlan["City"],'coverText', 'coverTextP');

    $this->section->addText("");
    $this->section->addImage(TEMPLATE_IMG_PATH.'main.jpg', $this->imageCoverM);
    
    $this->section->addText("Prepared For:",'coverText', 'coverTextP');    
    $this->section->addText("Strata Corporation ".$this->arrPlan["StrataNumber"]." – ".$this->arrPlan["Name"],'coverTextB14', 'coverTextB14P');
    $this->section->addText("C/O Ron Hall",'coverTextB14', 'coverTextB14P');

    $this->section->addText("Effective Date:",'coverText', 'coverTextP');
    $this->section->addText(Util::formatDate($this->arrPlan["EffectiveDate"]),'coverTextB14', 'coverTextB14P');
    
    $this->section->addText("Prepared By:",'coverText', 'coverTextP'); 
    $this->section->addText("Jeremy Bramwell, AACI, RI, CRP",'coverTextB14', 'coverTextB14P');
    $this->section->addText(htmlspecialchars("Bramwell & Associates Realty Advisors Inc."),'coverTextB14', 'coverTextB14P');
    $this->section->addText("1000–355 Burrard Street",'coverTextB14', 'coverTextB14P');
    $this->section->addText("Vancouver, British Columbia, V6C 2G8",'coverTextB14', 'coverTextB14P');
    $this->section->addText("StrataReservePlanning.Com",'coverTextB14', 'coverTextB14P');

    $this->section->addPageBreak();

}

function letter() {
    
    $regular = array('bold'=>false, 'size'=>12);
    $regularPRight = array('align'=>'right');
    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $header = $this->section->createHeader();
    $header->addImage(TEMPLATE_IMG_PATH.'header.jpg', $this->imageCoverH);
    
    $this->section->addTextBreak();
    
    
    $styleTable = array('cellMarginBottom'=>10,'cellMarginTop'=>10);
   $this->PHPWord->addTableStyle('tableLetter', $styleTable);
    
    //self
    $table = $this->section->addTable('tableLetter');
    
    $table->addRow();
    $table->addCell(5000)->addText("June 20, 2014", 'coverText');
    $table->addCell(5000)->addText("File No.: ".$this->arrPlan['FileNumber'], 'coverText', $regularPRight);
            
    $table->addRow();
    $table->addCell(5000)->addText("Strata Corporation ".$this->arrPlan["StrataNumber"]." – ".$this->arrPlan["Name"], 'coverText');
    $table->addRow();
    $table->addCell(5000)->addText("C/O Ron Hall", 'coverText');
    $table->addRow();
    $cell = $table->addCell(5000);
    $cell->addText($this->arrPlan["Street"], 'coverText');  
    $cell->addText($this->arrPlan["City"].", BC, ".Util::formatZIP($this->arrPlan["PostalCode"]), 'coverText');
    //self end
    
    $this->section->addTextBreak();
    
    //not self
    $table = $this->section->addTable('tableLetter');
    
    $table->addRow();
    $table->addCell(5000)->addText("September 21, 2015", 'coverTextRed');
    $table->addCell(5000)->addText("File No.: 1317-14-SU-BL-DR", 'coverTextRed', $regularPRight);
            
    $table->addRow();
    $table->addCell(5000)->addText("Strata Corporation VR 721 – The Hacienda", 'coverTextRed');
    $table->addRow();
    // name     title
    $table->addCell(5000)->addText("C/O John Cartwright, Property Manager", 'coverTextRed');
    $table->addRow();
    $table->addCell(5000)->addText("False Creek Management (2006) Ltd.", 'coverTextRed');
    
    $table->addRow();
    $cell = $table->addCell(5000);
    $cell->addText("811 Wintrop Street", 'coverTextRed');
    $cell->addText("New Westminster, BC, V3L 5N4", 'coverTextRed');    
    $this->section->addTextBreak();

    //not self end

    $this->section->addText("Dear XXX, ", 'styleRegular','paragraphRegular');
    $this->section->addTextBreak(1);

    $this->section->addText(htmlspecialchars('Depreciation Report'), 'coverTextB12','coverTextB12P'); 
    $this->section->addText(htmlspecialchars('Strata Corporation '.$this->arrPlan["StrataNumber"]." – ".$this->arrPlan["Name"]), 'coverTextB12','coverTextB12P'); 
    $this->section->addText(htmlspecialchars($this->arrPlan["Street"]." – ".$this->arrPlan["City"].', BC'), 'coverTextB12','coverTextB12P'); 


    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('Pursuant to your request for a depreciation report of the within described strata corporation, '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular'); 
    $textrun->addText(htmlspecialchars(' – a division of Bramwell & Associates Realty Advisors Inc. has prepared and submits to you this depreciation report.
'), 'styleRegular','paragraphRegular'); 




$this->section->addText(htmlspecialchars('We recommend that the enclosed funding plan be adopted and implemented, and that reserve fund contributions be increased from '.'$'.number_format($this->arrAux['AnnualReserveFundContributions'][0]['value']).' in '.$this->arrAux['AnnualReserveFundContributions'][0]['year'].' to '.'$'.number_format($this->arrAux['AnnualReserveFundContributions'][1]['value']).' per annum in '.$this->arrAux['AnnualReserveFundContributions'][1]['year'].', and further increased as per the cash flow table for each subsequent year. A '.'$'.number_format($this->arrAux['AnnualReserveFundContributions'][2]['value']).' special levy is also recommended in '.$this->arrAux['AnnualReserveFundContributions'][2]['year'].', with periodic additional special levies required to maintain an adequate contingency reserve fund. As outlined in this depreciation report, the current reserve fund balance and the recommended annual contributions will ensure that reserve funds are adequate to cover potential expenditures required to repair or replace common elements or assets of the strata corporation when needed.
'), 'styleRegular','paragraphRegular'); 
    
$this->section->addText(htmlspecialchars('The three (3) depreciation report funding scenarios are based on the hypothetical condition that the increase in reserve fund contributions is approved at a strata corporation annual general meeting (AGM) or at a special general meeting (SGM) during the current fiscal-year cycle.
'), 'styleRegular','paragraphRegular'); 

    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular'); 
    $textrun->addText(htmlspecialchars(' will be pleased to provide you with complete review and updating services for your strata corporation as required in three years. We are also able to provide insurance valuation services for your strata corporation. We appreciate that you trusted us with this opportunity to complete your depreciation report. If you have any questions, please do not hesitate to contact the undersigned.
'), 'styleRegular','paragraphRegular'); 
    
$this->section->addTextBreak();   
$this->section->addText(htmlspecialchars('Respectfully submitted,'), 'styleRegular','paragraphRegular'); 
    $this->section->addTextBreak();
$this->section->addText(htmlspecialchars('Strata Reserve Planning '), 'styleRegularSCB','paragraphRegular');  
    
    
$this->section->addImage(TEMPLATE_IMG_PATH.'sign.jpg', array('width'=>185, 'height'=>55));
$this->section->addText(htmlspecialchars('Jeremy Bramwell, AACI, P.App., RI (BC), CRP '), 'styleRegular','paragraphRegular');      

    
    
    $footer = $this->section->createFooter();
    $footer->addTextBreak();
    $footer->addText(htmlspecialchars('1000–355 Burrard Street, Vancouver, BC V6C 2G8  Tel: 604-608-6161  Fax: 604-669-6968  Web: StrataReservePlanning.Com 
Strata Reserve Planning – a division of Bramwell & Associates Realty Advisors Inc.'), null, 'footerP'); 

    
            
     $this->section->addPageBreak();
     
}

    function TOC() {
        
        $this->section = $this->PHPWord->createSection($this->sectionStyle);
        self::header();
        
        $footer = $this->section->createFooter();

    $this->section->addText('TABLE OF CONTENTS','coverTextB12', 'coverTextB14P');
        $this->section->addTextBreak();
        // Define the TOC fontStyle
    $FontStyleTOC = array ('spaceBefore'=>0,'spaceAfter'=>0, 'size'=>10);
    $StyleTOC = array ('table vein' => PHPWord_Style_TOC :: TABLEADER_DOT, 'tabPos' => 8000,'spaceBefore'=>0,'spaceAfter'=>0);

        $fontStyle = array('spaceAfter'=>0, 'size'=>10);
        $this->section->addTOC($FontStyleTOC,$StyleTOC);
        $this->section->addPageBreak();
    }
    
    
function importantInformation()  {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    self::header();


    
    
// $this->section->addTitle('IMPORTANT INFORMATION', 1);
    $this->section->addText('IMPORTANT INFORMATION','coverTextB14', 'coverTextB14P');
    
    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('This document has been provided pursuant to an agreement containing restrictions on its use. No part of this report shall be reproduced or used in any form or by any means – graphic, electronic or mechanical; including photocopying, recording, typing or information storage and retrieval – without the written permission of Bramwell & Associates Realty Advisors Inc. '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('©Copyright 2011–'.date('Y').' by '), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSCB','paragraphRegular');        
    $textrun->addText(htmlspecialchars(' (Bramwell & Associates Realty Advisors Inc.)'), 'styleRegularBold','paragraphRegular');      

    $this->section->addText(htmlspecialchars('Notwithstanding the foregoing, the client herein has permission to reproduce the report in whole or in part for the legitimate purposes of providing information to the strata council, strata lot owners and others who have an interest in the development. Specifically, the client has permission to provide depreciation report information in disclosure documents such as a Form B.'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('This depreciation report and the parameters under which it has been completed are based upon information provided to us in part by representatives of the strata corporation, its contractors, assorted vendors, specialist and independent contractors, the Real Estate Institute of Canada (REIC), and various construction pricing and scheduling manuals including, but not limited to: Marshall & Swift Valuation Service, RS Means Facilities Maintenance & Repair Cost Data and RS Means Repair & Remodeling Cost Data and McGraw-Hill Professional. Additionally, costs were obtained from numerous vendor catalogues, actual quotations or historical costs, and our own experience in the field of property management and depreciation report preparation.'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('It has been assumed, unless otherwise noted in this report, that all assets have been designed and constructed properly and that each estimated effective age will approximate that of the norm per industry standards and/or manufacturer’s specifications. In some cases, estimates may have been used on assets, which have an indeterminable but potential liability to the strata corporation. The decision for the inclusion of these as well as all assets considered is left to the client.'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('We recommend that your depreciation report be updated regularly due to fluctuating interest rates, inflationary changes, and the unpredictable nature of the lives of many of the assets under consideration. All of the information collected during our inspection of the strata corporation and computations made subsequently in preparing this depreciation report are retained in our computer files. Therefore, annual updates may be completed quickly and inexpensively each year.'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('Preparing the annual budget and overseeing the strata corporation’s finances are perhaps the most important responsibilities of strata council members. This depreciation report is provided as an aid for budget planning purposes and not as an accounting tool. Since it deals with events yet to take place, there is no assurance that the results enumerated within it will, in fact, occur as described. The annual operating and reserve budgets reflect the planning and goals of the strata corporation and set the level and quality of service for all of the strata corporation’s activities.'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('Strata Reserve Planning would like to thank you for using our services. We invite you to call us at any time, should you have questions, comments or need assistance.'), 'styleRegular','paragraphRegular'); 

    $this->section->addPageBreak();
    
}


function executiveSummary() {
    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    self::header();
        
    $this->section->addTitle('EXECUTIVE SUMMARY OF FACTS AND CONCLUSIONS', 1);

    $this->section->addText(htmlspecialchars('Overview'), 'styleRegularTitle','paragraphRegular'); 
    
    $this->section->addText(htmlspecialchars('This executive summary has been prepared as a reference to the pertinent facts and estimates of this Depreciation Report, and it is provided as convenience only. Readers are advised to refer to the full text of this depreciation report that follows for detailed information.'), 'styleRegular','paragraphRegular'); 
    
    $this->section->addText('');
        $styleTable = array('cellMarginBottom'=>50);
//    $styleFirstRow = array('cellMarginBottom'=>1,'cellMarginTop'=>1,'borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
   $this->PHPWord->addTableStyle('tableExecutive', $styleTable);
    $table = $this->section->addTable('tableExecutive');
    
    $table->addRow();
    $table->addCell(4000)->addText('Client', 'styleRegularBold');
    $cell = $table->addCell(6000);
    $cell->addText('Strata Corporation '.$this->arrPlan["StrataNumber"].' – '.$this->arrPlan["Name"],  'regular11');    
    $cell->addText($this->arrPlan["Street"].', '.$this->arrPlan["City"].', BC', 'regular11');
    $cell->addText(Util::formatZIP($this->arrPlan["PostalCode"]), 'regular11');
    
    $table->addRow();
    $table->addCell(3500)->addText("Date of Inspection:", 'styleRegularBold');
    $table->addCell(6500)->addText(Util::formatDate($this->arrInspectionDates[0]["Date"]), 'regular11');
    
    $table->addRow();
    $table->addCell(3500)->addText("Effective Date:", 'styleRegularBold');
    $table->addCell(6500)->addText(Util::formatDate($this->arrPlan["EffectiveDate"]), 'regular11');

    $table->addRow();
    $table->addCell(1)->addText('Property', 'styleRegularBold');
    $cell = $table->addCell(1);
    $cell->addText('Strata Corporation '.$this->arrPlan["StrataNumber"].' – '.$this->arrPlan["Name"],  'regular11');  
    $cell->addText($this->arrPlan["Street"].', '.$this->arrPlan["City"].', BC', 'regular11');
    



    $table->addRow();
    $table->addCell(1)->addText('Reserve Fund Groups', 'styleRegularBold');
    $cell = $table->addCell(1);
    $cell->addText('10 Site Improvements Reserve Components',  'regular11');  
    $cell->addText('1 Consultant Report', 'regular11');


    $table->addRow();
    $table->addCell(3500)->addText("Construction Inflation Rate:", 'styleRegularBold');
    $table->addCell(6500)->addText($this->arrPlan['ConstructionInflationRate'].'%', 'regular11');
    
    $table->addRow();
    $table->addCell(3500)->addText("Investments Interest Rate:", 'styleRegularBold');
    $table->addCell(6500)->addText($this->arrPlan['InverstmentsInterasteRate'].'%', 'regular11');

    $table->addRow();
    $table->addCell(3500)->addText("Year 1 Reserve Adequacy:", 'styleRegularBold');
    $table->addCell(6500)->addText($this->arrAux['Year1ReserveAdequacy'].'%', 'regular11');
    
    $table->addRow();
    $table->addCell(3500)->addText("Year 30 Reserve Adequacy:", 'styleRegularBold');
    $table->addCell(6500)->addText($this->arrAux['Year30ReserveAdequacy'].'%', 'regular11');

    $table->addRow();
    $table->addCell(1)->addText('Significant Reserve Fund Estimates', 'styleRegularBold');
    $cell = $table->addCell(1);
    $cell->addText('Current Replacement Reserves or Costs:', 'regular11', 'myParaStyle');
    $cell->addText('Future Replacement Reserves or Costs:', 'regular11', 'myParaStyle');
    $cell->addText('Current Reserve Fund Requirements: ', 'regular11', 'myParaStyle');
    $cell->addText('Future Reserve Fund Accumulations:', 'regular11', 'myParaStyle');
    $cell->addText('Future Reserve Fund Requirements:', 'regular11', 'myParaStyle');
    $cell->addText('Fully Funded Annual Reserve Fund Contributions:','regular11', 'myParaStyle');
    $cell->addText('Recommended Annual Reserve Fund Contributions (Period ending December 31, 2015):', 'regular11', 'myParaStyle');
    $cell = $table->addCell(1500);
    $cell->addText('$'.number_format($this->arrAux['CurrentReplacementCost']), 'regular11', 'regular11PR');
    $cell->addText('$'.number_format($this->arrAux['FutureReplacementCost']),'regular11', 'regular11PR');
    $cell->addText('$'.number_format($this->arrAux['CurrentReserveFundCostReq']), 'regular11', 'regular11PR');
    $cell->addText('$'.number_format($this->arrAux['FutureReserveFundAcc']), 'regular11', 'regular11PR');
    $cell->addText('$'.number_format($this->arrAux['FutureReserveFundReq']), 'regular11', 'regular11PR');
    $cell->addText('$'.number_format($this->arrAux['ReserveFundAnnualCon']), 'regular11', 'regular11PR');
    $cell->addText("$2,640", 'regular11', 'regular11PR');

    $this->section->addPageBreak();

    $this->section->addText(htmlspecialchars('What is a Depreciation Report?'), 'styleRegularTitle','paragraphRegular'); 
    
    $this->section->addText(htmlspecialchars('A depreciation report – also known as a reserve fund study – involves the art and science of anticipating and preparing for a strata corporation’s common areas’ major repair and replacement expenditures. It is part art, because we are making projections about the future. It is part science, because our work is a process of research and analysis along well-defined methodologies.  
'), 'styleRegular','paragraphRegular'); 
   
    $this->section->addText(htmlspecialchars('All depreciation reports begin with a reserve Component list that determines what a strata corporation is to reserve for in their reserve fund.'), 'styleRegular','paragraphRegular'); 
    

   $textrun = $this->section->createTextRun('paragraphRegular');

    $imageStyle = array(
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$textrun->addImage(TEMPLATE_IMG_PATH.'executiveTable1.jpg', $imageStyle);
//    
    $textrun->addText(htmlspecialchars('The component list contains our estimates for the Expected Lifespan, the Effective Age, the Remaining Lifespan, and the Current Repair or Replacement Cost for each component that the strata corporation is obligated to maintain and replace. Based on this reserve component list and your starting reserve fund balance, we calculate herein the strata corporation’s reserve fund strength, measured in terms of its Reserve Adequacy and then recommend a 30 year reserve fund plan to offset future reserve fund expenditures.
'), 'styleRegular','paragraphRegular'); 

     $this->section->addText(htmlspecialchars('As the physical assets (buildings and equipment) age and deteriorate, it is important to accumulate financial assets (money) to keep the two “in balance”. A stable Contingency Reserve Fund that offsets irregular expenditures will ensure that each owner pays their “fair share” of ongoing common assets’ deterioration.
'), 'styleRegular','paragraphRegular');    
    
    $this->section->addTextBreak();
    
  $this->section->addText(htmlspecialchars('Types of Depreciation Reports'), 'styleRegularTitle','paragraphRegular');   
    

      $this->section->addText(htmlspecialchars('Most depreciation reports fit into one of the three following categories:'), 'styleRegular','paragraphRegular');       
    

    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('In a '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Full Depreciation Report'), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars(', the Depreciation Report / Reserve Study Planner conducts a Component Inventory, a condition assessment (based upon on-site visual observations) and life and valuation estimates to determine both the Fund Status and the Funding Plan.
'), 'styleRegular','paragraphRegular'); 
    
     $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('In an '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Update with Site Inspection Depreciation Report'), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars(', the depreciation report / reserve study planner conducts a component inventory (verification only, not quantification unless new components have been added to the inventory), a condition review assessment (based upon on-site visual observations), and life and valuation estimates to determine both the fund status and funding plan.
'), 'styleRegular','paragraphRegular'); 
    
    
    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('In an '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Update without Site Inspection Depreciation Report'), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars(', the depreciation report / reserve study planner updates the life and valuation estimates, or the financial section of the depreciation report, to bring up to date the fund status and funding plan in light of these new data.'), 'styleRegular','paragraphRegular'); 
    
    
    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('This report is a '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('Full Depreciation Report.'), 'styleRegularBold','paragraphRegular'); 

    
    
    

    
    $this->section->addText(htmlspecialchars('The Depreciation Report – A Physical and a Financial Analysis'), 'styleRegularTitle','paragraphRegular');  
    
    
    $this->section->addText(htmlspecialchars('There are two integral parts to a depreciation report: the Physical Analysis and the Financial Analysis. What follows is a brief description of how your depreciation report was put together.
'), 'styleRegular','paragraphRegular');    
    $this->section->addTextBreak();
    $this->section->addText(htmlspecialchars('Physical Analysis'), 'styleRegularBold','paragraphRegular'); 
    
     $this->section->addText(htmlspecialchars('During the physical analysis, a depreciation report / reserve study planner evaluates information regarding the physical status and major repair or replacement cost of the strata corporation’s major common area components (exterior walls, roofing, hallways, elevators, amenity rooms, parking, etc.). 
'), 'styleRegular','paragraphRegular');    
     
    $this->section->addText(htmlspecialchars('To do so, the depreciation report / reserve study planner conducts a component inventory, a condition assessment review and prepares life span estimates.
'), 'styleRegular','paragraphRegular');    
   
         $this->section->addTextBreak(1);
         $this->section->addText(htmlspecialchars('How Is the Reserve Component List Established?'), 'styleRegularBoldItalic','paragraphRegular'); 

 
    $textrun = $this->section->createTextRun('paragraphRegular');    
  $textrun->addText(htmlspecialchars('The budgeting process begins with an inventory of all the major components for which the strata corporation is responsible. The determination of whether an item should be labelled as operational, reserve, or excluded altogether is somewhat subjective. As this labelling may have a major impact on the financial plans of the strata corporation, '), 'styleRegular','paragraphRegular');      
    $textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular');        
    $textrun->addText(htmlspecialchars(' uses the following considerations when selecting what is to be included in the depreciation report.'), 'styleRegular','paragraphRegular');      
     
     
     

     
     
     $this->section->addText(htmlspecialchars('We use a standard four-part test to determine what should be funded through the reserve fund:'), 'styleRegular','paragraphRegular');   
      $this->section->addTextBreak();    
         
 
            $textrun = $this->section->createTextRun('paragraphRegular');

    $imageStyle = array(
    'width' => 260,
    'height' => 132,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$textrun->addImage(TEMPLATE_IMG_PATH.'executiveTable2.jpg', $imageStyle);
//    
    $textrun->addText(htmlspecialchars('First, the component must be a common responsibility. Second, the component must have a limited lifespan.  Third, the limited life span must be predictable (or it by definition is a “surprise”, which cannot be accurately anticipated).  Fourth, the component cost must be above a minimum threshold determined by the strata council. This limits reserve fund components to major predictable expenditures. 
'), 'styleRegular','paragraphRegular'); 
         
  
         $this->section->addText(htmlspecialchars('Within this framework, it is inappropriate to include “lifetime” components, unpredictable expenses (such as damage due to fire, flood, or earthquake), and minor expenses more appropriately handled from the Operational Fund or as an insured loss.
'), 'styleRegular','paragraphRegular');  
        
         
                  $this->section->addText(htmlspecialchars('However, there are Allowances in every reserve fund for some items based on the possibility that they may need repair during a depreciation report’s thirty year period. An example is underground service pipes: a strata corporation is typically responsible for the section under roadways within the strata plan property lines. While not predictable, it is prudent to make an allowance available for deterioration of this component. 
'), 'styleRegular','paragraphRegular');  
         
                  
                  
     $this->section->addText(htmlspecialchars('Preparing the Depreciation Report'), 'styleRegularTitle','paragraphRegular');  

    $this->section->addText(htmlspecialchars('Once the reserve fund components have been identified and quantified, their respective replacement costs, expected lifespan, effective age and remaining lifespan must be determined so that a funding schedule can be established. Replacement costs and lifespans can be found in published manuals from construction estimators, appraisal handbooks and valuation guides. Remaining lifespans are calculated from the expected lifespan and observed condition of assets and further adjusted based on consideration of design, manufactured quality, usage, exposure to the elements and maintenance history.
'), 'styleRegular','paragraphRegular');                   
                  
                  $this->section->addTextBreak();
 $this->section->addText(htmlspecialchars('How are Expected Lifespan and Remaining Lifespan Established?'), 'styleRegularBoldItalic','paragraphRegular');                                
   $this->section->addTextBreak(1);   

    $table = $this->section->addTable('myOwnTableStyle');
    $table->addRow();
    $table->addCell(1000)->addText('1)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Visual inspection (observed wear and age)', 'regular12', 'myParaStyle');

    $table->addRow();
    $table->addCell(1000)->addText('2)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Strata Reserve Planning depth of experience', 'regular12', 'myParaStyle');
    
    $table->addRow();
    $table->addCell(1000)->addText('3)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Client component history and', 'regular12', 'myParaStyle');    
    
    $table->addRow();
    $table->addCell(1000)->addText('4)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Vendor evaluation and recommendation.', 'regular12', 'myParaStyle');    

$this->section->addTextBreak(2); 

 $this->section->addText(htmlspecialchars('How Are Cost Estimates Established?'), 'styleRegularBoldItalic','paragraphRegular');                                

    $this->section->addText(htmlspecialchars('Financial projections are based on an average of our Best Case and Worst Case estimates, which are established with consideration of the following:
'), 'styleRegular','paragraphRegular'); 
    
    $table = $this->section->addTable('myOwnTableStyle');
    $table->addRow();
    $table->addCell(1000)->addText('1)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Client cost history', 'regular12', 'myParaStyle');

    $table->addRow();
    $table->addCell(1000)->addText('2)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(htmlspecialchars(' Comparison to Strata Reserve Planning / Bramwell & Associates database of work done at similar strata developments'), 'regular12', 'myParaStyle');
    
    $table->addRow();
    $table->addCell(1000)->addText('3)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Vendor recommendations and', 'regular12', 'myParaStyle');    
    
    $table->addRow();
    $table->addCell(1000)->addText('4)  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(htmlspecialchars(' Reliable national industry cost estimating guidebooks, such as Marshall & Swift Valuation Service, RS Means Facilities Maintenance & Repair Cost Data, RS Means Repair & Remodeling Cost Data and McGraw-Hill Professional.'), 'regular12', 'myParaStyle');  


$this->section->addTextBreak(2); 

 $this->section->addText(htmlspecialchars('How Are Cost Allowances Established? '), 'styleRegularBoldItalic','paragraphRegular');                                

    $this->section->addText(htmlspecialchars('Reserve funds amounts and/or allowances are calculated as a percentage of the estimated replacement cost for each component. The calculation is established by the depreciation report / reserve study planner based on their assessment of the probability of the component’s repair or replacement needs.
'), 'styleRegular','paragraphRegular'); 

    $this->section->addText(htmlspecialchars('By following the recommendations of an effective depreciation report, the strata corporation is well placed to avoid major shortfalls. However, to remain accurate, and as mandated by the Strata Property Act and Regulations, the report should be updated to reflect such changes as shifts in economic parameters, additional phases or new assets and expenditures. The strata corporation can assist in simplifying the depreciation report update process by keeping accurate records of these changes throughout the year.
'), 'styleRegular','paragraphRegular');     

    
     $this->section->addText(htmlspecialchars('Financial Analysis'), 'styleRegularTitle','paragraphRegular');  
    
    $this->section->addText(htmlspecialchars('A Depreciation report financial analysis assesses the strata corporation’s reserve fund balance or fund status to determine a recommendation for the appropriate reserve fund contributions in the future, known as the funding plan.
'), 'styleRegular','paragraphRegular');       
    
    
     $this->section->addText(htmlspecialchars('What is Covered in the Depreciation Report?'), 'styleRegularBoldItalic','paragraphRegular');                                
    
    $this->section->addText(htmlspecialchars('Operating Expenses are Excluded'), 'styleRegularBold','paragraphRegular');
    
    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('Expenses that occur at least annually, no matter how large the expense, and can be budgeted for annually are excluded. These are reasonably predictable, in terms of both frequency and cost. Operating expenses include all minor expenses, which would not otherwise adversely affect an Operating Fund from one year to the next. This is where most items end up. Examples of '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('operating expenses '), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('include: '), 'styleRegular','paragraphRegular'); 
    
    
    
    
    $styleTable = array('cellMargin'=>0, 'align'=>'left');
    $styleFirstRow = array('borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
    $this->PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

    //begin structure of component page
    $table = $this->section->addTable('myOwnTableStyle');
    $table->addRow(0);
    //'guider' cells that define the width of a column all the way down
    $table->addCell(1500)->addText('Utilities:', 'regular12', 'regular12P');
    $table->addCell(3500)->addText('Administrative Costs:', 'regular12', 'regular12P');
    $table->addCell(2500)->addText('Scheduled Services:', 'regular12', 'regular12P');
    $table->addCell(2500)->addText('Repair Expenses:', 'regular12', 'regular12P');
	
    $table->addRow();
    $table->addCell(2500)->addText(' Electricity', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Supplies', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Landscaping', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Roof', 'regular12', 'regular12P');
    
    $table->addRow();
    $table->addCell(2500)->addText(' Gas', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Bank Charges', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Pool Maintenance', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Equipment', 'regular12', 'regular12P');
    
    $table->addRow();
    $table->addCell(2500)->addText(' Water', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Dues and Publications', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Street Sweeping', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Concrete (minor)', 'regular12', 'regular12P');
    
    $table->addRow();
    $table->addCell(2500)->addText(' Telephone', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Licenses, Permits and  Fees', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Accounting', 'regular12', 'regular12P');
    $table->addCell(2500)->addText('', 'regular12', 'regular12P');
    
    $table->addRow();
    $table->addCell(2500)->addText(' Cable (TV)', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Insurance(s)', 'regular12', 'regular12P');
    $table->addCell(2500)->addText('', 'regular12', 'regular12P');
    $table->addCell(2500)->addText('', 'regular12', 'regular12P');
   
      
      
   $this->section->addTextBreak();
   
    $this->section->addText(htmlspecialchars('Reserve Fund Expenditures are Included'), 'styleRegularBold','paragraphRegular');
    
    $textrun = $this->section->createTextRun('paragraphRegular');    
    $textrun->addText(htmlspecialchars('These are major financial commitments that occur other than annually, and which must be budgeted for in advance to ensure the availability of the necessary funds in time for their use. Reserve expenditures are reasonably predictable in terms of both frequency and cost. However, they may include significant assets that have an indeterminable but potential liability that may be demonstrated as a likely occurrence. They are expenditures that, when incurred, would have a significant effect on the smooth operation of the budgetary process from one year to the next, if they were not reserved for in advance. Examples of '), 'styleRegular','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('reserve expenditures '), 'styleRegularBold','paragraphRegular'); 
    $textrun->addText(htmlspecialchars('include: '), 'styleRegular','paragraphRegular'); 
   
   
    $styleTable = array('cellMargin'=>0, 'align'=>'center');
    $styleFirstRow = array('borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
    $this->PHPWord->addTableStyle('myOwnTableStyleRI', $styleTable, $styleFirstRow);

    //begin structure of component page
    $table = $this->section->addTable('myOwnTableStyleRI');
    $table->addRow(0);
    //'guider' cells that define the width of a column all the way down
    $table->addCell(3000)->addText('', 'regular12', 'regular12P');
    $table->addCell(4000)->addText('', 'regular12', 'regular12P');
    $table->addCell(3000)->addText('', 'regular12', 'regular12P');
	
    $table->addRow();
    $table->addCell(2500)->addText(' Roof Replacements', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Asphalt Seal Coating', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Park/Playground Equipment', 'regular12', 'regular12P');

    
    $table->addRow();
    $table->addCell(2500)->addText(' Deck Resurfacing', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Fencing Replacements', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Lighting Replacement', 'regular12', 'regular12P');
    
    $table->addRow();
    $table->addCell(2500)->addText(' Asphalt Repairs', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Interior and Exterior Painting', 'regular12', 'regular12P');
    $table->addCell(2500)->addText(' Depreciation Report', 'regular12', 'regular12P');
   
       $this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Other Budgetary Exclusions'), 'styleRegularBold','paragraphRegular');


$this->section->addText(htmlspecialchars('Replacements of assets that are deemed to have an estimated lifespan equal to or exceeding the estimated lifespan of the facility or development itself, or exceeding the legal life of the development as defined in a strata corporation’s governing documents, are excluded. Examples include the replacement of hard-wiring and major plumbing. Also excluded are insignificant expenses or expenditures that may be covered either by an operating contingency, or otherwise through a general maintenance fund. Expenses that are necessitated by acts of nature, accidents or other occurrences that are more properly insured for, rather than reserved for, are also excluded.
'), 'styleRegular','paragraphRegular'); 



    $this->section->addText(htmlspecialchars('Funding Strategies'), 'styleRegularTitle','paragraphRegular'); 

    
$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('Once a strata corporation has established its Funding Goals, the strata council can select an appropriate funding plan. '), 'styleRegular','paragraphRegular');  
$textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular');
$textrun->addText(htmlspecialchars(' has identified five (5) primary funding models currently in use by strata corporations in British Columbia. Depending on current strata corporations’ finances and financial health, one of these models may be currently in operation.'), 'styleRegular','paragraphRegular'); 



$textrun = $this->section->createTextRun('regularHaning3');    
$textrun->addText(htmlspecialchars('Benchmark or Fully Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('– setting a reserve funding goal of keeping the contingency reserve fund balance at or near 100% funded on a year-to-year basis. This means the strata corporation is adhering to the simple and responsible principle that you “replace what you use up”. Members of fully funded strata enjoy low exposure to the risk of special levies or deferred maintenance. Strong interest earnings will minimize owners’ reserve fund contributions. Council members thus enjoy peace of mind in knowing that the strata corporation’s physical and financial assets are in balance which also ensures a degree of insulation from claims of fiscal irresponsibility.
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('regularHaning3');    
$textrun->addText(htmlspecialchars('Threshold Funded Model'), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('– setting a reserve funding goal of keeping the reserve balance between 0% and 100% of fully funded. Strata corporations choosing threshold funding select this option in a manner that customizes their risk exposure. Depending on the mix of common area major components, the threshold model may be more or less conservative than the fully funded model, as it is dependent on strata council input. '), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('regularHaning3');    
$textrun->addText(htmlspecialchars('Baseline Funded Model'), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – setting a reserve funding goal of keeping the contingency reserve fund balance at or above a dollar amount arbitrarily chosen by the Strata Council. This approach is riskier for strata lot owners, as experience has shown that this method makes it more difficult for strata corporations to meet reserve fund requirements, and as such, it is not usually recommended.
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('regularHaning3');    
$textrun->addText(htmlspecialchars('Unfunded “Pay As You Go” with Special Levies Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('– this is the default current model in place in most strata today. In this case, the strata corporation typically does not have reserve fund balances sufficient to cover expected replacement costs and the only recourse is to schedule special levies to cover these costs on a reactive basis. Lack of information about needed special levies is a real problem for some strata lot owners. These costs impose an additional financial burden on owners who often have chosen condominium living for perceived reduced cost reasons. This is the riskiest of the financial models, and can jeopardize the financial viability of the development if special levies cannot be raised when needed. 
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('regularHaning3');    
$textrun->addText(htmlspecialchars('Statutory Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('– setting a reserve funding goal based on the Strata Property Act and part 6.3 of the Strata Act Regulations that states that a strata corporation must contribute the lessor of 10% of a current year’s operating budget, or what is required to bring the reserve fund to 25% of the previous year’s operating budget. This calculation method amounts to the greatest disservice the legislation has done to strata lot owners, as this ratio does not reflect the true cost of repairs and replacements. Moreover, as buildings get older, the funding requirements get larger, and add more strain on the reserve fund. This method is not based on the real needs for the strata, and therefore is usually not recommended.'), 'styleRegular','paragraphRegular'); 



$this->section->addText(htmlspecialchars('Each of these models is dependent on an analysis of cash flows into and out of the reserve fund over a thirty years period, as mandated by the Strata Property Act. Calculations are then made to reach strata councils’ and strata corporations’ funding goals.  
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Obviously, the choice of the funding goal or strategy will have a direct impact on the reserve fund contributions required from each individual owner. The strategy and the degree to which the strata corporation has funded its reserves should affect property value as well'), 'styleRegular','paragraphRegular'); 


$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('British Columbia law currently does not specify which funding model strata corporations are required to use. The model that reduces or eliminates the reserve fund Deficit provides the most stability and is the most conservative. The legislation does require that three (3) models be shown in the report.  Strata councils should carefully consider and document their choice of a funding plan and make the details of the plan available to owners in the reserve fund study portion of the '), 'styleRegular','paragraphRegular');
$textrun->addText(htmlspecialchars('pro forma'), 'styleRegularItalic','paragraphRegular');
$textrun->addText(htmlspecialchars(' operating budget. If the information is adequate and clearly presented, present owners and future buyers should be in a better position to evaluate the value of units and developments.'), 'styleRegular','paragraphRegular');




$this->section->addTextBreak(1);

  $this->section->addText(htmlspecialchars('What Is The Funding Method?'), 'styleRegularBoldItalic','paragraphRegular');    


$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular');
$textrun->addText(htmlspecialchars(' funding models are based upon the Cash Flow Funding Method. This cash flow method develops a reserve funding plan where contributions to the reserve fund are designed to offset the variable annual expenditures incurred in the reserve fund. Different reserve funding plans are tested against the actual anticipated schedule of reserve expenditures until the desired funding goal is achieved. This method sets up a “window” in which all future anticipated replacement costs are computed, based upon the individual life spans of the components of a strata development under consideration. 
'), 'styleRegular','paragraphRegular'); 


$this->section->addPageBreak();
//funding options
$this->section->addText(htmlspecialchars('Funding Options'), 'styleRegularTitle','paragraphRegular'); 


   $textrun = $this->section->createTextRun('paragraphRegular');

    $imageStyle = array(
    'width' => 220,
    'height' => 137,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$textrun->addImage(TEMPLATE_IMG_PATH.'executiveTable3.jpg', $imageStyle);
  
$textrun->addText(htmlspecialchars('There are four Funding Principles that we use to balance your reserve funding plan. Our first objective is to design a funding plan that provides you with ')
, 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('sufficient cash reserves '), 'styleRegularUnderline','paragraphRegular'); 
$textrun->addText(htmlspecialchars('to perform your reserve fund projects when required. A '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('stable contribution rate '), 'styleRegularUnderline','paragraphRegular'); 
$textrun->addText(htmlspecialchars('is desirable as it is the hallmark of a proactive plan.'), 'styleRegular','paragraphRegular'); 


$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('Reserve fund contributions that are '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('evenly distributed'), 'styleRegularUnderline','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' across all the owners over the years enable each owner to pay their “fair share” of the strata corporation’s reserve expenditures (this means that we recommend special levies only when all other options have been exhausted). And finally, we develop a financial plan that is '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('fiscally responsible'), 'styleRegularUnderline','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' and “safe” for Council members to recommend to their strata corporation membership. Why?'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Because when a major repair or replacement is required in a development, a strata corporation has essentially four options available to address the expenditure:
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('The first, and most logical way that the strata council has to ensure its ability to maintain the assets for which it is obligated, is by '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('assessing an adequate level of reserves '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('as part of the regular owner’s monthly contributions, thereby distributing the cost of the replacements uniformly over the entire ownership and life of the complex. The development is not only comprised of present members, but also future members. Any decision by the strata council to adopt a calculation method or funding plan which would disproportionately burden future members in order to make up for past reserve deficits, would be a breach of its fiduciary responsibility to those future members.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Unlike individuals determining their own course of action, the strata council is responsible to the “community” as a whole. If the strata corporation had set aside reserves for this purpose, using regularly assessed contributions, it would have had the full term of the life of the roof, for example, to accumulate the necessary reserves. Additionally, these contributions would have been evenly distributed over the entire life of the development from construction while earning interest as well.
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('The second option is for the strata corporation to '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('acquire a loan '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('from a lending institution in order to cover the required repairs. In many cases, banks or third party providers will lend to a strata corporation using “future homeowner assessments” as collateral for the loan. With this method, the current strata council is pledging the future assets of a strata corporation. They are also incurring the additional expense of interest fees on top of the original principal amount. In the case of a $250,000 roof replacement, for example, the strata corporation may be required to pay back the loan over a three to eight year period, with interest.
'), 'styleRegular','paragraphRegular'); 



$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('The third option, too often used, is simply to '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('defer the required repair or replacement. '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('This option, which is not recommended, can create an environment of declining property values due to expanding lists of deferred maintenance items and the strata corporation’s financial inability to keep pace with the normal aging process of the common area components. This, in turn, can have a seriously negative impact on sellers in the strata corporation by making it difficult, or even impossible, for potential buyers to obtain financing from lenders. Increasingly, lending institutions are requesting copies of the strata corporation’s most recent depreciation report before granting loans, either for the strata corporation itself, a prospective purchaser or for an individual within such a strata corporation.
'), 'styleRegular','paragraphRegular'); 


$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('The fourth option is to pass a '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('Special Levy '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('to the ownership in an amount required to cover the expenditure. When a special levy is passed, the strata corporation has the authority and responsibility to collect the special levies, even by means of foreclosure, if necessary. However, a strata corporation considering a special levy cannot guarantee that a levy, when needed, will be passed. Consequently, the strata corporation cannot guarantee its ability to perform the required repairs or replacements to those major components for which it is obligated when the need arises. '), 'styleRegular','paragraphRegular'); 


$this->section->addText(htmlspecialchars('Additionally, while relatively new developments require very little in the way of major reserve expenditures, strata corporations reaching 12 to 15 years of age and older, find many components reaching the end of their effective lifespans. These required expenditures, all accruing at the same time, could have a devastating effect on a strata corporation’s overall budget.
'), 'styleRegular','paragraphRegular'); 

$this->section->addPageBreak();




 $this->section->addText(htmlspecialchars('How much Reserves are enough?'), 'styleRegularBoldItalic','paragraphRegular');                                
 
   
   $this->section->addText(htmlspecialchars('Your reserve fund cash balance can measure reserves, but the true measure is whether the funds are adequate to meet the strata corporation’s needs, measured by what is otherwise known as the reserve adequacy.
'), 'styleRegular','paragraphRegular'); 
   
      $this->section->addText(htmlspecialchars('The reserve adequacy is measured in a two-step process that involves:
'), 'styleRegular','paragraphRegular'); 
      
      
      

    $table = $this->section->addTable('myOwnTableStyle');
    $table->addRow();
    $table->addCell(1000)->addText('1.  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Calculating the strata corporation’s Fully Funded Balance (FFB) or what would be required in the bank today to cover all future obligations, given that the contribution rate was maximized.
', 'regular12', 'myParaStyle');

    $table->addRow();
    $table->addCell(1000)->addText('2.  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Comparing the current year’s reserve fund balance to the current year’s FFB and expressing it as a percentage.
', 'regular12', 'myParaStyle');
  

    $textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('The FFB grows as components and other assets age and the financial needs of the strata corporation increase, but shrinks when projects are accomplished and the expenditures of strata corporation decrease. The fully funded balance changes each year, and is a moving but predictable target. '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('Buyers should be aware of this important disclosure – section 6.4 of the report has more details.
'), 'styleRegularBold','paragraphRegular'); 
    
       $this->section->addText(htmlspecialchars('Measuring your reserve adequacy in terms of a percentage of a fully funded reserve fund reflects how well prepared your strata corporation is for upcoming reserve fund expenditures (see the graph below for an explanation).
'), 'styleRegular','paragraphRegular');    
    
    
    
$this->section->addTextBreak(2); 



    $styleTable = array('cellMargin'=>0, 'align'=>'center');
    $styleFirstRow = array('borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
    $this->PHPWord->addTableStyle('myOwnTableStyleRI', $styleTable, $styleFirstRow);

    //begin structure of component page
    $table = $this->section->addTable('myOwnTableStyleRI');


        $imageStyle = array(
    'width' => 180,
    'height' => 84,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);

$tableStyleA = array(
    'bgColor' => '00B050',
    'borderSize' => 0,
    'borderColor' => 'FFFFFF',
    'cellMargin' => 100,
    'valign'=>'center'
);

$tableStyleB = array(
    'bgColor' => 'FFC000',
    'borderSize' => 0,
    'borderColor' => 'FFFFFF',
    'cellMargin' => 100,
    'valign'=>'center'
);

$tableStyleC = array(
    'bgColor' => 'FF0000',
    'borderSize' => 0,
    'borderColor' => 'FFFFFF',
    'cellMargin' => 100,
    'valign'=>'center'
);
$tableStyleCell = array(
    'borderSize' => 0,
    'borderColor' => 'FFFFFF',
    'valign'=>'center'
);

    $table->addRow(1500,$tableStyleA);
    $table->addCell(4500,$tableStyleCell)->addText('While a reserve adequacy of 100% is ideal, a reserve fund anywhere in the 70% to 130% is considered “Strong” as in this range, cash flow problems  are rare.', 'regular12', 'paragraphRegularJ');
    $table->addCell(1500,$tableStyleCell)->addText('70 to 130% or more', 'styleRegularBold', 'coverTextB12P');
    //$table->addCell(2500)->addImage(TEMPLATE_IMG_PATH.'executiveTableG.png', $imageStyle);
//$table->addCell(2500,$tableStyle22)->addText('70 to 130% or more', 'styleRegularBold', 'coverTextB12P');
   
$cell1 = $table->addCell(4000,$tableStyleA);
$cell1->addTextBreak();
$cell1->addText('STRONG', 'styleRegularTitleWhite', 'coverTextB12P');
$cell1->addTextBreak();
$cell1->addText('Low Probability of Special Levies', 'styleRegularWhite','coverTextB12P');
$cell1->addText('Highly Desirable Developments', 'styleRegularWhite','coverTextB12P');
$cell1->addTextBreak();

    $table->addRow(1500,$tableStyleA);
    $table->addCell(4500,$tableStyleCell)->addText('A strata corporation with a reserve adequacy in the 35% to 70% range is considered “Average” and can expect a series of periodical special levies to meet its  financial obligations.', 'regular12', 'paragraphRegularJ');
    $table->addCell(1500,$tableStyleCell)->addText('35 to 70%', 'styleRegularBold', 'coverTextB12P');
   // $table->addCell(2500)->addImage(TEMPLATE_IMG_PATH.'executiveTableY.png', $imageStyle);
  $cell1 = $table->addCell(4000,$tableStyleB);
  $cell1->addTextBreak();
$cell1->addText('AVERAGE', 'styleRegularTitleWhite', 'coverTextB12P');
$cell1->addTextBreak();
$cell1->addText('Probability of Occasional Special Levies', 'styleRegularWhite','coverTextB12P');  

    $table->addRow(1500,$tableStyleA);
    $table->addCell(4500,$tableStyleCell)->addText('When a strata corporation’s reserve adequacy is below 35%, it is considered “Critical” can expect borrowings, loans or multiple special levies. ', 'regular12', 'paragraphRegularJ');
    $table->addCell(1500,$tableStyleCell)->addText('0% to 35%', 'styleRegularBold', 'coverTextB12P');
   // $table->addCell(2500)->addImage(TEMPLATE_IMG_PATH.'executiveTableR.png', $imageStyle);
$cell1 = $table->addCell(4000,$tableStyleC);
$cell1->addTextBreak();
$cell1->addText('CRITICAL', 'styleRegularTitleWhite', 'coverTextB12P');
$cell1->addTextBreak();
$cell1->addText('Probability of Special Levies on a Regular Basis', 'styleRegularWhite','coverTextB12P');



$this->section->addPageBreak();
//How Should I Compare One Property to Another?

 $this->section->addText(htmlspecialchars('How Should I Compare One Property to Another?'), 'styleRegularBoldItalic','paragraphRegular');                                
 
   
   $this->section->addText(htmlspecialchars('Comparing one property to another is a personal choice and it is not our place to determine which is a better property for you or your family. Given this, please keep the following in mind:
'), 'styleRegular','paragraphRegular'); 

    $table = $this->section->addTable('myOwnTableStyle');
    $table->addRow();
    $table->addCell(1000)->addText('1.  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' Lenders may be charging more interest on mortgages for properties without depreciation reports, when reserve adequacy ratios do not improve in the long term, due to a lack of certainty regarding strata finances. If a strata corporation has a reserve fund with increasing strength as illustrated by Reserve Adequacy indicators, you should get lower mortgage rates.', 'regular12', 'myParaStyle');

    $table->addRow();
    $table->addCell(1000)->addText('2.  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(9000);
    $cell->addText(' We believe that understanding how the complex is reserved for in the next 5 to 10 years is important to understand, as this will have a major impact on your personal cash flow and borrowing capacity. Understanding and planning for increases in monthly reserve fund contributions and special levies may lead you to discuss your Loan to Value with your Lender to ensure that you get the appropriate mortgage.', 'regular12', 'myParaStyle');
  
$this->section->addTextBreak(1);  
     $this->section->addText(htmlspecialchars('Is A High Reserve Fund Adequacy Required In Older Buildings?'), 'styleRegularBoldItalic','paragraphRegular');                                

   
   $this->section->addText(htmlspecialchars('In many buildings, the life expectancy of the structure is 60 – 70 years, so the need for large reserve fund balances to pay for items like a third or fourth roof replacement is not reasonable in most scenarios. However, the strata may have had a poor quality roof or poor installation, which shortens the lifespan of the roof. As funding models assume a perpetual life for the structure, assistance from Bramwell and Associates can help the strata work towards a more realistic reserve strength target based on the age of the complex and the condition of its assets or components, as well as changes in the allowable density or uses in the surrounding area. This should be kept in mind by readers of this report.
'), 'styleRegular','paragraphRegular'); 
   
      $this->section->addText(htmlspecialchars('Determination of the point when termination of the strata corporation – and distribution of the proceeds of sale and the contingency reserve fund savings – becomes a better choice than continuing investment in the development is outside the scope of this report, but as Bramwell & Associates Realty Advisors Inc. has a valuation division, we can provide these consulting services when required.
'), 'styleRegular','paragraphRegular'); 


      $this->section->addTextBreak(1);  
      $this->section->addText(htmlspecialchars('Reserve Fund Status of '.$this->arrPlan["StrataNumber"]), 'styleRegularTitle','paragraphRegular'); 
      
       $this->section->addText(htmlspecialchars('The starting point for our financial analysis is your reserve fund balance, projected to be '.'$'.number_format($this->arrAux['ReserveFundClosingBalance'][0]['value']).' as of the start of the strata corporation’s fiscal year end on '.Util::formatDate($this->arrAux['ReserveFundClosingBalance'][0]['date']).'. This is based on your actual balance date with anticipated reserve contributions and expenditures projected through the end of your fiscal year, with year 1 contributions to the Reserve Fund estimated at '.'$'.number_format($this->arrAux['RecommendedAnnualRFContr']).'. 
'), 'styleRegular','paragraphRegular');      
      
      
      
$this->section->addPageBreak();
       
       
  $this->section->addText(htmlspecialchars('What Is This Property’s Current Reserve Fund Adequacy?'), 'styleRegularBoldItalic','paragraphRegular');                                

   
   $this->section->addText(htmlspecialchars('Based on the reserve funding patterns from the strata corporation’s financial history it is clear on the graph below – the dark red bars – that your reserve fund would have a declining and/or static reserve adequacy over the next 30 years.
'), 'styleRegular','paragraphRegular'); 
      
   $this->section->addText(htmlspecialchars('Our recommended reserve fund plan – the light green bars – indicates that your reserve fund would increase from the current reserve adequacy of 37% to 60% in the same 30 years.
'), 'styleRegular','paragraphRegular'); 
            
      
   
//GRAPH 1            
    $imageStyle = array(
//    'width' => 629,
//    'height' => 325,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$this->section->addImage(TEMPLATE_IMG_PATH.'chart1.jpg', $imageStyle);



$this->section->addText(htmlspecialchars('Is This An Unusual Reserve Adequacy?'), 'styleRegularBoldItalic','paragraphRegular');                                
   
$this->section->addText(htmlspecialchars('Our research indicates that the majority of strata developments in British Columbia have a reserve adequacy between 10% to 25% of fully funded in this first mandated cycle of depreciation reports (years 2012–2017). In addition, most strata are currently underfunded and do not have a depreciation report.
'), 'styleRegular','paragraphRegular'); 
      
$this->section->addText(htmlspecialchars('It would be unwise to assume that similar aged complexes of similar design are in the same condition, based on the number of variables that factor into the well-being of a strata corporation’s common assets. As this development’s existing reserve adequacy is at '.$this->arrAux['ReserveAdequacy'].'%, it is technically above the norm for most strata corporations. By following the plan laid out in this report, the strata corporation will have its reserve fund reach a stronger state over and at the end of 30 years.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Many owners have no idea of the costs and no financial plan to deal with them. This development has taken the proactive approach and engaged a professional firm to conduct their depreciation report.
'), 'styleRegular','paragraphRegular'); 
   
   

$this->section->addText(htmlspecialchars('What is Our Recommended Funding Plan?'), 'styleRegularBoldItalic','paragraphRegular');                                
   
$this->section->addText(htmlspecialchars('With this approach, we have produced three (3) scenarios for Strata Corporation LMS 667: the fully funded model – which sets the benchmark from which two other scenarios are constructed – the recommended model and the existing model. The existing model describes the outlook if the current planning approach is maintained while the recommended model presents a financial plan in between the existing and the fully funded models.
'), 'styleRegular','paragraphRegular'); 
      
$this->section->addText(htmlspecialchars('The reserve fund scenarios are based on the hypothetical condition that the increase in reserve fund contributions is approved at a strata corporation annual general meeting (AGM) or at a special general meeting (SGM) during the current fiscal-year cycle.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('The graph below summarizes the reserve fund plans. The total expenditures are the same for each plan. The graph illustrates the cumulative implications of each funding plan over 30 years with respect to the total reserve fund contributions, the possible special levies and the total interest income. The graph also depicts the closing reserve fund balance and related closing reserve fund cash shortage at the end of 30 years.
'), 'styleRegular','paragraphRegular');   
   
  //GRAPH 2
$this->section->addImage(TEMPLATE_IMG_PATH.'chart2.jpg', $imageStyle);    
   
$this->section->addText(htmlspecialchars('Based on your current reserve adequacy and your projected cash flow requirements – as illustrated in the graph on the following page – we are recommending that your annual reserve fund contributions of $'.number_format($this->arrAux['AnnualReserveFundContributions'][0]['value']).' in '.$this->arrAux['AnnualReserveFundContributions'][0]['year'].' be increased to $'.number_format($this->arrAux['AnnualReserveFundContributions'][1]['value']).' in '.$this->arrAux['AnnualReserveFundContributions'][1]['year'].'. Further increases will be required as per Schedule C.1 cover the expenditures in a manner that ensures an adequate reserve fund closing balance over the 30 years. 
'), 'styleRegular','paragraphRegular');   
   
  //GRAPH 3
$this->section->addImage(TEMPLATE_IMG_PATH.'chart3.jpg', $imageStyle);  

$this->section->addPageBreak();
//How will the Recommended Funding Plan Impact your Strata Lot Over the Next 5 to 10 Years?
$this->section->addText(htmlspecialchars('How will the Recommended Funding Plan Impact your Strata Lot Over the Next 5 to 10 Years?'), 'styleRegularBoldItalic','paragraphRegular');                                
   
$this->section->addText(htmlspecialchars('The recommended plan has been designed to ensure that annual Average Strata Lot (ASL) contributions increase in a manner that maximizes annual interest income and minimizes possible special levies as illustrated in the graph below in terms of the annual ASL reserve fund contributions, interest income and with no possible special levies. 
'), 'styleRegular','paragraphRegular'); 

  //GRAPH 4
$this->section->addImage(TEMPLATE_IMG_PATH.'chart4.jpg', $imageStyle);  

$this->section->addText(htmlspecialchars('The table below provides a snapshot of what current owners and future buyers may look forward to for their required budgeting during the next ten years, to ensure that the strata corporation maximizes the condition, attractiveness and usefulness of its common assets and components.
'), 'styleRegular','paragraphRegular'); 
  //GRAPH 5
$this->section->addImage(TEMPLATE_IMG_PATH.'table1.jpg', $imageStyle);
$this->section->addText(htmlspecialchars('Based on the recommended plan, the ASL monthly contributions to the reserve fund would increase from $'.number_format($this->arrAux['MonthlyASLContributions'][0]['value'],2).' monthly in '.$this->arrAux['MonthlyASLContributions'][0]['year'].' to $'.number_format($this->arrAux['MonthlyASLContributions'][1]['value'],2).' per month after 5 years in '.$this->arrAux['MonthlyASLContributions'][1]['year'].'. No special levies are proposed during the first 10 years. 
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Please see the cash flow table on the following page for more details.'), 'styleRegular','paragraphRegular'); 

$this->section->addTextBreak(2);  
$this->section->addLine(['weight' => 1, 'width' => 100, 'height' => 0]);
$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('1 '), array('superScript'=>true)); 
$textrun->addText(htmlspecialchars('The Average Strata Lot (ASL) divides the respective reserve fund variables by the total number of strata lots in a strata corporation. A supplementary report may be produced that provides the information in terms of each strata lot’s number of entitlement units.'), null,'paragraphRegular'); 

$this->section->addPageBreak();

$this->section = $this->PHPWord->createSection($this->sectionStyleLandscape);

$this->section->addImage(TEMPLATE_IMG_PATH.'table2.jpg', $imageStyle);


//$this->section->addPageBreak();
 
    
}


function depreciationReport() {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $this->section->addTextBreak(15); 
    $this->section->addText(htmlspecialchars('Depreciation Report'), array('color'=>'000000', 'size'=>22, 'bold'=>true),'coverTextP');   
    $this->section->addPageBreak();
    
    $this->section->addTextBreak(1);
    $this->section->addTitle('DEPRECIATION REPORT', 'H1');
    $this->section->addTextBreak(1);
    $this->section->addTitle('Purpose of a Depreciation Report', 'H2');
    $this->section->addText(htmlspecialchars('The purpose of this Depreciation Report is to provide cost estimates of various reserve components in a manner that provides a viable financial model for the strata corporation to meet its fiduciary responsibility. The development is subject to major repairs and/or replacement over the lifetime of the property. The function of this report is to estimate the funding required for such major repairs and replacements in accordance with the reserve fund report standards established by the Real Estate Institute of Canada (see REIC’s Technical Bulletin No.1). This report also conforms to the Consulting Standards as set out by the Appraisal Institute of Canada found in the Canadian Uniform Standards of Professional Appraisal Practice. 
'), 'styleRegular','paragraphRegular'); 

    
    $this->section->addTextBreak(1);
    $this->section->addTitle('Effective Date of the Report', 'H2');
    $this->section->addText(htmlspecialchars('This Depreciation Report (and Reserve Fund Study) applies as of '.Util::formatDate($this->arrPlan["EffectiveDate"]).'. The second year reserve fund contribution assumption is based on ratification at a future AGM or SGM, and this report may change if this assumption is inaccurate.
'), 'styleRegular','paragraphRegular'); 

    
        $this->section->addTextBreak(1);
    $this->section->addTitle('Definition of Depreciation Report', 'H2');
    $this->section->addText(htmlspecialchars('A Depreciation Report inclusive of a Reserve Fund Study is a detailed financial document for a strata’s common assets. It is not a structural analysis of the buildings nor does it involve destructive testing. It does include cost estimates of major repairs and replacement of components and assets. It provides financial information, estimates and projections all used for making the right decision about funding the major repairs and replacement of those components and assets for the future.
'), 'styleRegular','paragraphRegular'); 
    
    
        $this->section->addTextBreak(1);
    $this->section->addTitle('British Columbia’s Strata Property Act (1998) – Reserve Fund', 'H2');
    $this->section->addText(htmlspecialchars('Part 6 (Finances) of the Strata Property Act, Division 1 covers the operating fund and contingency reserve fund, and establishes the legal responsibility to have a reserve fund, particularly sections 92 and 93, while the requirements of the depreciation report are laid out in the Strata Property Regulations.
'), 'styleRegular','paragraphRegular'); 
 
    $this->section->addPageBreak();
}

function methodology() {

    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTitle('Methodology', 'H1');
    $this->section->addTitle('Depreciation Report', 'H2');
    $this->section->addText(htmlspecialchars('The depreciation report provides a financial tool which is the basis for funding major repairs and replacement of the common components and assets of the strata corporation. It is a practical guide for budget planning and maintenance programs, and unlike a technical or structural audit, it deals not in detailed technical matters, but rather takes a business approach to reserve fund management. 
'), 'styleRegular','paragraphRegular');     
    
     $this->section->addText(htmlspecialchars('This depreciation report is comprised of the following elements: 
'), 'styleRegular','paragraphRegular');      
    
    
    
    $table = $this->section->addTable('myOwnTableStyle');
    

$text = array();    
$text[] = 'It identifies the reserve components and assesses their quality, expected lifespan, and present condition.';
$text[] = 'It estimates the remaining serviceable years for each of the reserve components and proposes a time schedule for repairs and/or replacement.';
$text[] = 'It provides current replacement cost estimates including the cost of removing worn-out items and special safety provisions.';
$text[] = 'It projects the future value of current replacement costs at an appropriate and compounded inflation rate.';
$text[] = 'It projects the future value of current reserve funds compounded at a long term interest rate and';
$text[] = 'It calculates current reserve fund contributions required and to be invested in interest bearing securities in order to fund future reserve fund expenditures.';

    for($i = 0; $i < count($text); $i++) {
    $table->addRow();
    $table->addCell(1200)->addText(($i+1).')  ', 'regular12', 'regular12PR');
    $cell = $table->addCell(200);
    $cell = $table->addCell(8600);
    $cell->addText(htmlspecialchars($text[$i]), 'regular12', 'myParaStyle');  
    
    }
 
    $this->section->addTextBreak(1);
         $this->section->addText(htmlspecialchars('The salient estimates and conclusions of this depreciation report are contained in the various schedules thereinafter. The depreciation report is a practical guide to assist the strata council in planning reserve fund budgets and maintenance programs. 
'), 'styleRegular','paragraphRegular'); 
         
$this->section->addTitle('Real Estate Institute of Canada Reserve Fund Planning Standards', 'H2');

         $this->section->addText(htmlspecialchars('Regulations under the Strata Property Act (1998) require that a depreciation report consist of a physical analysis and a financial analysis.
'), 'styleRegular','paragraphRegular'); 

         $this->section->addText(htmlspecialchars('The Real Estate Institute of Canada has established reserve fund and depreciation report planning standards that exceed the regulatory requirements and are now recognized and emulated across Canada. These standards, presented throughout this report, consist of investigations, analyses and calculations that provide realistic and supportable reserve fund estimates.
'), 'styleRegular','paragraphRegular'); 

 
$this->section->addTitle('General Conditions and Assumptions', 'H2');
         
          $this->section->addText(htmlspecialchars('Reserve fund Life Cycle Estimates are subjective, and they are based on an understanding of the life cycle of building components and our experience gained from observing buildings over a 30 year period. It must be appreciated that reserve fund budgeting and projections are not exact sciences. They are, at best, prudent provisions for all possible non-routine repairs and replacements, if, and when they arise. Reserve fund requirements are subject to change and must be reviewed and modified over time, not less than every three years.
'), 'styleRegular','paragraphRegular');         
         
 $this->section->addTitle('Funding Strategies', 'H2'); 
 

 
         
$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('Once a strata corporation has established its funding goals, the strata corporation can select an appropriate funding plan. '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('Strata Reserve Planning'), 'styleRegularSC','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' has identified the five (5) primary funding models strata corporations use in British Columbia. Depending on current strata corporations’ finances and financial health, one of these models may be currently operating. 
'), 'styleRegular','paragraphRegular'); 





$textrun = $this->section->createTextRun('paragraphRegular');    
$textrun->addText(htmlspecialchars('Benchmark or Fully Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – setting a reserve funding goal of keeping the contingency reserve fund balance at or near 100% funded on a year to year basis. This means the strata corporation is following the simple and responsible principle that you “replace what you use up”.  Believing this to be the responsible choice, our funding plan will direct you towards full reserve funding. Members of fully funded strata corporations enjoy low exposure to the risk of possible special levies or deferred maintenance. Strong interest earnings will minimize their reserve fund contributions. Strata corporation members enjoy peace of mind that the association’s physical and financial assets are in balance, and therefore a degree of insulation from claims of fiscal irresponsibility.
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');    
$textrun->addText(htmlspecialchars('Threshold Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – setting a reserve funding goal of keeping the contingency reserve fund balance at a percentage of the fully funded status. Strata corporations choosing threshold funding select this option to customize their risk exposure. Depending on the mix of common area major components, this model may be more or less conservative than the fully funded model. The only way to tell is to compare the threshold and fully funded models.'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');    
$textrun->addText(htmlspecialchars('Baseline Funded Model or Minimum Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – setting a reserve funding goal of keeping the contingency reserve fund balance at or above a dollar amount arbitrarily chosen by the Strata Council. This is a risker situation for strata corporation owners, as experience has shown that this method tends to not meet requirements, and as such is usually not recommended.'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');    
$textrun->addText(htmlspecialchars('Unfunded “Pay As You Go” with Special Levies Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – this is the default current model in place in most strata corporations today. In this case, the strata corporation typically does not have reserve fund balances sufficient to cover expected replacement costs and the only recourse is to schedule special levies to cover these costs on a reactive basis. Lack of information about needed special levies is a real problem for some strata lot owners. These costs impose an additional financial burden on owners who often have chosen condominium living for perceived reduced cost reasons. This is the riskiest of the financial models, and could jeopardize the financial viability of the development if special levies cannot be raised when needed. 
'), 'styleRegular','paragraphRegular'); 

$textrun = $this->section->createTextRun('paragraphRegular');    
$textrun->addText(htmlspecialchars('Statutory Funded Model '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' – setting a reserve funding goal based on the Strata Property Act. Part 6.3 of the Strata Act Regulations states that a strata corporation must contribute the lessor of 10% of a current year’s operating budget, or what is required to bring the reserve fund to 25% of the previous year’s operating budget. This calculation method amounts to the greatest disservice the legislation has done to strata lot owners, as this ratio does not reflect the true cost of major repairs and replacements. Moreover, as buildings get older, the funding requirements get larger, and add more strain on the reserve fund. This method is not based on the real needs for the strata corporation and therefore is usually not recommended.'), 'styleRegular','paragraphRegular'); 
            
            
        $this->section->addText(htmlspecialchars('Each of these models depends on an analysis of cash flows into and out of the reserve fund over a thirty-year cycle. Special levy calculations are then made so that these are sufficient to reach strata councils’ and strata corporations’ stated funding goals.  
'), 'styleRegular','paragraphRegular');
        
        $this->section->addText(htmlspecialchars('Obviously, the choice of the funding goal or strategy will have a direct impact on the cash required of each individual owner. The benchmark or fully funded model is the ideal goal, while the unfunded “Pay as you Go” model is less than adequate. The threshold model is the most reasonable model for most developments and their owners.
'), 'styleRegular','paragraphRegular');              
 

$textrun = $this->section->createTextRun('paragraphRegular');
$textrun->addText(htmlspecialchars('Columbia law currently does not specify one model for funding, but obviously, the model that reduces or eliminates the reserve deficit provides the most stability and is the most conservative.  The legislation does demand 3 models be provided.  Strata councils should carefully consider and document the choice of a funding plan and make the details of the plan available to owners in the depreciation report portion of the '), 'styleRegular','paragraphRegular');
$textrun->addText(htmlspecialchars('pro forma'), 'styleRegularItalic','paragraphRegular');
$textrun->addText(htmlspecialchars(' operating budget. If the information is adequate and clearly presented, present owners and future buyers should be in a better position to evaluate the value of units and developments.'), 'styleRegular','paragraphRegular');
        
        //
            
   
$this->section->addTitle('Reserve Fund Projection Factors', 'H2');

      $this->section->addText(htmlspecialchars('The Regulation 938/2011 under the Strata Property Act, 1998, requires that the financial analysis include the following:
'), 'styleRegular','paragraphRegular');    

$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_SQUARE_FILLED, 'spaceAfter'=>60,'spaceBefore'=>60);
$this->section->addListItem('the estimated cost of major repair or replacement of the common elements and assets of the corporation at the estimated time of the repair or replacement based on an assumed annual inflation rate,
', 0, 'styleRegular', $listStyle);
$this->section->addTextBreak(1);
$this->section->addListItem('the annual inflation rate described above,', 0, 'styleRegular', $listStyle);
$this->section->addTextBreak(1);
$this->section->addListItem('the estimated interest that will be earned on the reserve fund based on an assumed annual interest rate and', 0, 'styleRegular', $listStyle);
$this->section->addTextBreak(1);
$this->section->addListItem('the annual interest rate described above.', 0, 'styleRegular', $listStyle);
$this->section->addTextBreak(1);


$this->section->addText(htmlspecialchars('In our opinion, the notion of an “assumed” annual inflation rate and an “assumed” interest rate in the Regulation is not realistic, as assumptions are personal perceptions or judgments, and therefore, subjective.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('What is required is an objective basis for any estimates of inflation factors and interest rates. Inflation factors and interest rates must be derived from an economic analysis of the marketplace.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('The estimated inflation factor and the selected interest rate are powerful factors in projecting reserve fund contributions and requirements. They can vary dramatically over time and must be periodically reviewed to ensure their relevance and accuracy.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Although the Strata Property Regulations require a reserve fund plan to be projected over a period of at least 30 consecutive years, a long-term horizon in every respect, reserve fund projection factors can only be based on short-term economic conditions because of their volatility over time.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('The reserve fund projection factors must be periodically reviewed and adjusted in accordance  with changing economic conditions as part of the reserve fund updating process, as mandated by the Strata Property Regulations.
'), 'styleRegular','paragraphRegular'); 
  
$this->section->addTextBreak(1);

$this->section->addTitle('Inflation Factors', 'H2');

$this->section->addText(htmlspecialchars('Inflation in reserve fund projections are estimates of future costs for the repair and renovation of building components, and are projected based on history. These construction cost inflation forecasts must be based on construction indices rather than the widely quoted consumer price index (CPI). The latter measures the cost of a basket of consumer goods, not construction costs.  The most widely recognized construction cost services providing periodic cost indices are R.S. Means and Marshall & Swift / Boeckh. Indices are typically supplemented with information from local contractors.
'), 'styleRegular','paragraphRegular'); 
  

$this->section->addText(htmlspecialchars('Means Historical Cost Index'), 'styleRegularBold','paragraphRegular'); 

$this->section->addText(htmlspecialchars('The Means Historical Index, used to calculate annual inflation rates, is based on the computed value as of January 1, 2012, for an average North American construction rate of inflation. While useful as an overall indication of the construction inflation trend in North America, these rates are too broadly based, and as such, they do not accurately reflect the inflationary impact on local construction costs.
'), 'styleRegular','paragraphRegular'); 


$this->section->addText(htmlspecialchars('Marshall & Swift / Boeckh (MSB) Time-Location Multiplier'), 'styleRegularBold','paragraphRegular');             
   
$this->section->addText(htmlspecialchars('MSB publishes its Time-Location Multipliers quarterly for principal Canadian cities (markets).
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('These multipliers are computer-compiled by combining currently researched wage rates and material prices with “weighted schedules” that specify how much of each basic cost is in the models.”
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Each building has its own unique combination of basic costs. MSB uses 83 basic types of costs necessary to build workable weighted schedules, comprising 19 building trades and 64 material types.
'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('The following are the percentage changes of MSB Time-Location Multipliers for Greater Vancouver for the past 10 years:
'), 'styleRegular','paragraphRegular'); 




$this->section->addText(htmlspecialchars('Marshall & Swift / Boeckh Index'), 'styleRegularBold','paragraphRegular'); 




    $styleTable = array('cellMarginTop'=>225, 'align'=>'center');
    $styleFirstRow = array('align'=>'center','borderTopColor'=>'FFFFFF', 'borderLeftColor'=>'FFFFFF', 'borderRightColor'=>'FFFFFF', 'borderBottomColor'=>'FFFFFF');
    $this->PHPWord->addTableStyle('myOwnTableStyle6', $styleTable, $styleFirstRow);

    $table = $this->section->addTable('myOwnTableStyle6');
    $table->addRow(0);
    //'guider' cells that define the width of a column all the way down
    $table->addCell(4000)->addText('', 'regular12', 'paragraphRegularC');
    $table->addCell(1500)->addText('High Rises (Class A):', 'styleRegularBold', 'paragraphRegularC');
    $table->addCell(1500)->addText('Office Buildings (Class B):', 'styleRegularBold', 'paragraphRegularC');
    $table->addCell(1500)->addText('Tilt-Up concrete (Class C):', 'styleRegularBold', 'paragraphRegularC');
    $table->addCell(1500)->addText('Low Rise Condos (Class D):', 'styleRegularBold', 'paragraphRegularC');


    $col1 = array(
    'Index January 2013', 'Index January 2012', 'Index January 2011', 'Index January 2010', 'Index October 2009', 
    'Index October 2008', 'Index October 2003', '10 Year Cost Increase', 'Annual Increase', 
    '5 Year Cost Increase', 'Annual Increase');

    $col2 = array('1.000','1.043','1.083','1.063','1.114','1.114','1.342','34.20%','3.42%','11.40%','2.28%');
    $col3 = array('1.000','1.048','1.080','1.084','1.119','1.119','1.310','31.00%','3.10%','11.90%','2.38%');
    $col4 = array('1.000','1.042','1.077','1.091','1.131','1.131','1.306','30.60%','3.06%','13.10%','2.62%');
    $col5 = array('1.000','1.048','1.081','1.103','1.137','1.137','1.256','25.60%','2.56%','13.70%','2.74%');

    for($i = 0; $i < count($col1); $i++) {
        $style = ($i == 7 or $i == 9) ? "styleRegularBold" : "regular12";
        $table->addRow(0);
        $table->addCell(4000)->addText($col1[$i], 'styleRegularBold', 'regular12P');
        $table->addCell(1500)->addText($col2[$i], $style, 'paragraphRegularC');
        $table->addCell(1500)->addText($col3[$i], $style, 'paragraphRegularC');
        $table->addCell(1500)->addText($col4[$i], $style, 'paragraphRegularC');
        $table->addCell(1500)->addText($col5[$i], $style, 'paragraphRegularC');
    }
    
    
    $this->section->addText(htmlspecialchars('The significant cost increases over the past five years are primarily due to large increases in steel, oil and concrete prices and higher wage costs. Price fluctuations due to the 2008 economic turmoil are now settling.
'), 'styleRegular','paragraphRegular'); 
    
    
    $this->section->addText(htmlspecialchars('Judging by the overall construction cost trends, one may conclude that the longer term rate of inflation in construction will continue to increase over the foreseeable future.  
'), 'styleRegular','paragraphRegular'); 
    
    
    
 $textrun = $this->section->createTextRun('paragraphRegular');  
 $textrun->addText(htmlspecialchars('We have adopted the median rate of '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars($this->arrPlan['ConstructionInflationRate'].'%'), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' for annual inflation in calculating the future replacement costs hereinafter.
'), 'styleRegular','paragraphRegular');    
    

$this->section->addTextBreak(1);
$this->section->addTitle('Interest Rates', 'H2');

$this->section->addText(htmlspecialchars('Investment income can be a significant and increasing source of revenue for reserve funds, and therefore, it is imperative that reserve funds are continuously and prudently invested.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Under Regulation 6.11 of the Strata Property Act, a Strata Corporation may invest money from the Contingency Reserve Fund in a large selection of investments. Most Reserve Fund investments are directly or indirectly guaranteed by governments. Bank deposits and various investment instruments are insured by the Canada Deposit Insurance Corporation up to a maximum of $100,000, covering principal and interest. In BC, the provincial government covers credit unions.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Reserve fund investments must be directly or indirectly guaranteed by governments. Bank deposits and various investment instruments are insured by the Canada Deposit Insurance Corporation (CDIC) up to a maximum of $100,000, covering principal and interest.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The ability of strata corporations to earn the highest rate of interest available in the marketplace, given the restricted conditions of investments, depends on the expertise of financial management and the amount of available funds for investment. 
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Therefore, the reserve fund planner must consider management policies, the historical investment performance and the size of the reserve fund available for investment.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('In selecting an appropriate interest rate for reserve fund investments for a particular strata corporation, the reserve fund balance is the most critical consideration as it dictates investment options and their corresponding interest rates.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Investment opportunities are widely advertised, ranging from bank deposits, term deposits and guaranteed investment certificates (GICs) to money market instruments and government bonds.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Prudent reserve fund investment requires that investments be reasonably matched with anticipated reserve fund expenditures, ensuring reserve fund liquidity. Therefore, funds should be in a laddered investment portfolio, which ensures that reserve funds are available when needed.  This would result in a blended return between short term and longer-term rates.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Some management firms use their “purchasing power” by directing business to a particular financial institution to negotiate favourable interest rates for all their clients. This approach may benefit the smaller corporations and is an important consideration when selecting an appropriate interest rate.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The benchmark calculations and the reserve fund projections are based on the assumption that reserve fund contributions are constantly and continuously invested.
'), 'styleRegular','paragraphRegular');



$this->section->addText(htmlspecialchars('Strata Corporation '.$this->arrPlan["StrataNumber"].' has provided financials complete with the interest income on the reserve fund bank account, which we have analysed as averaging 1.50% over the last two years. Typically, strata corporations keep some cash, some short-term investments – typically at less than 1.00% and longer term GIC investments presently at 1.00 to 2.00%. Due to the bare land nature of the site, and the large CRF in relation to past needs, laddering has been maximized.
'), 'styleRegular','paragraphRegular');



$textrun = $this->section->createTextRun('paragraphRegular');  
$textrun->addText(htmlspecialchars('Considering the investment opportunities available in the subject instance, and a recommended management policy of investing in secured guaranteed investments for the longer term savings, following discussion with the strata council, we have selected a  '), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('1.50%'), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars(' interest rate in calculating the future investment performance of the strata corporation’s contingency reserve fund'), 'styleRegular','paragraphRegular'); 


$this->section->addTextBreak(1);
$this->section->addTitle('Canadian Uniform Standards of Professional Appraisal Practice', 'H2');

$this->section->addText(htmlspecialchars('Your depreciation report was prepared in conformity with the Canadian Uniform Standards of Professional Appraisal Practice (CUSPAP), with the following comments as clarification. This report summarizes the “Depreciation Report” or “Contingency Reserve Plan” estimate of the subject property only. No analysis has been undertaken for any market value consideration. There are no hypothetical assumptions, other than those listed within this Report. No highest and best use analysis was completed, as the purpose of the report is to determine the value of replacing the improvements only. This report was completed as a Reserve Planning assignment, and therefore falls under the Reserve Planning Rules and Standards.
'), 'styleRegular','paragraphRegular');

$this->section->addPageBreak();

}
function propertyInformation() {
    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
 
    
    $this->section->addTitle('Property Information', 'H1');
  
    
    $this->section->addTitle('Property Description', 'H2');
    
    
    
    $styleTable = array('cellMarginTop'=>125, 'align'=>'left');

    $table = $this->section->addTable($styleTable);
    
    $table->addRow(0);
    $table->addCell(3000)->addText('Address:', 'regular12', 'paragraphRegular');
    $table->addCell(7000)->addText($this->arrPlan["Street"].', '.$this->arrPlan["City"].', BC', 'styleRegular', 'paragraphRegular');
   
    $table->addRow(0);
    $table->addCell(4000)->addText('Location:', 'regular12', 'paragraphRegular');
    $table->addCell(6000)->addText($this->arrPlan["Location"], 'styleRegular', 'paragraphRegular');

    $table->addRow(0);
    $table->addCell(4000)->addText('Construction Type:', 'styleRegular', 'paragraphRegular');
    $table->addCell(6000)->addText($this->arrPlan["ConstructionType"], 'styleRegular', 'paragraphRegular');
   
    $table->addRow(0);
    $table->addCell(4000)->addText('Construction Year:', 'regular12', 'paragraphRegular');
    $table->addCell(6000)->addText($this->arrPlan["ConstructionYear"], 'styleRegular', 'paragraphRegular');

    $table->addRow(0);
    $table->addCell(4000)->addText('Registered as a strata on:', 'regular12', 'paragraphRegular');
    $table->addCell(6000)->addText(Util::formatDate($this->arrPlan["StrataRegistrationDate"]), 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(4000)->addText('Number of Strata Lots:', 'regular12', 'paragraphRegular');
    $table->addCell(6000)->addText($this->arrPlan["NumResidentialStrataLots"]+$this->arrPlan["NumCommercialStrataLots"], 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(4000)->addText('Management:', 'regular12', 'paragraphRegular');
    $table->addCell(6000)->addText($this->arrPlan["Management"], 'styleRegular', 'paragraphRegular');

    $this->section->addTextBreak(1);
    $this->section->addTitle('Building Plans', 'H2');

$this->section->addText(htmlspecialchars('The following plans were examined in the performance of this Depreciation Report:
'), 'styleRegular','paragraphRegular');



    $table = $this->section->addTable($styleTable);
    
    $table->addRow(0);
    $table->addCell(5000)->addText('Strata Plans:', 'regular12', 'paragraphRegular');
    $table->addCell(5000)->addText($this->arrPlan["StrataPlans"], 'styleRegular', 'paragraphRegular');
   
    $table->addRow(0);
    $table->addCell(5000)->addText('Building Plans, Schedules and Details:', 'regular12', 'paragraphRegular');
    $table->addCell(5000)->addText($this->arrPlan["BuildingPlans"], 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(5000)->addText('Site Plans:', 'regular12', 'paragraphRegular');
    $table->addCell(5000)->addText($this->arrPlan["SitePlans"], 'styleRegular', 'paragraphRegular');

$this->section->addText(htmlspecialchars('Plans were used for quantifying site components and other improvements. We only reviewed strata plans for the development as well as some drawing from the City of Surrey. Most quantities were estimated or measured on site or from the plans as well as from strata plans and are to be considered as estimates. 
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The bare land site improvements were inspected on March 29, 2014. Various construction details and improvements have been noted for consideration in the cost estimates herein.'), 'styleRegular','paragraphRegular');



 $this->section->addTextBreak(1);
    $this->section->addTitle('Property Data, Site Plan and Basic Construction', 'H2');
    
   
    
    $table = $this->section->addTable($styleTable);
    
    $table->addRow(0);
    $table->addCell(3100)->addText('', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('Property Statistics', 'styleRegularB', 'paragraphRegular');
    $table->addCell(700);
    $table->addCell(3100)->addText('', 'styleRegular', 'paragraphRegular');

    $table->addRow(0);
    $table->addCell(3100)->addText('Site Area:', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText(number_format($this->arrPlan["SiteArea"]), 'styleRegular', 'paragraphRegularR');
    $table->addCell(700);
    $table->addCell(3100)->addText('Square Feet', 'styleRegular', 'paragraphRegular');
 
    $table->addRow(0);
    $table->addCell(3100)->addText('', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('Site Improvements', 'styleRegularB', 'paragraphRegular');
    $table->addCell(700);
    $table->addCell(3100)->addText('', 'styleRegular', 'paragraphRegular');
 
    $table->addRow(0);
    $table->addCell(3100)->addText('Site Services:', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('2', 'styleRegular', 'paragraphRegularR');
    $table->addCell(700);
    $table->addCell(3100)->addText('System(s)', 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(3100)->addText('Roadways:', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('7,674', 'styleRegular', 'paragraphRegularR');
    $table->addCell(700);
    $table->addCell(3100)->addText('Square Feet', 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(3100)->addText('Curbs:', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('569', 'styleRegular', 'paragraphRegularR');
    $table->addCell(700);
    $table->addCell(3100)->addText('Linear Feet', 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(3100)->addText('Exterior Lighting:', 'regular12', 'paragraphRegular');
    $table->addCell(3100)->addText('4   ', 'styleRegular', 'paragraphRegularR');
    $table->addCell(700);
    $table->addCell(3100)->addText('Street Lights', 'styleRegular', 'paragraphRegular');

    
    
$this->section->addPageBreak();

$this->section->addText(htmlspecialchars('Site Plan'), 'styleRegularBold','paragraphRegular');


    $imageStyle = array(
//    'width' => 261,
//    'height' => 133,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$this->section->addImage(TEMPLATE_IMG_PATH.'sitePlan.jpg', $imageStyle);


$this->section->addPageBreak();

$this->section->addText(htmlspecialchars('Basic Construction'), 'styleRegularBold','paragraphRegular');
    



    $table = $this->section->addTable($styleTable);
    
    $table->addRow(0);
    $table->addCell(3000)->addText('Overview', 'styleRegularBold', 'paragraphRegularL');
    $table->addCell(1000);
    $table->addCell(6000)->addText($this->arrPlan["Overview"], 'styleRegular', 'paragraphRegular');
    
      $table->addRow(0);
    $table->addCell(3000)->addText('', 'regular12', 'paragraphRegular');
    $table->addCell(1000);
    $table->addCell(6000)->addText('The quality of construction, materials and workmanship are considered to be average at the time of the site-visit.', 'styleRegular', 'paragraphRegular');  
    
    
    
   
    $table->addRow(0);
    $table->addCell(3000)->addText('Electrical Systems (House Panel etc.)', 'styleRegularBold', 'paragraphRegularL');
    $table->addCell(1000);
    $table->addCell(6000)->addText($this->arrPlan["Electrical"], 'styleRegular', 'paragraphRegular');
    
    $table->addRow(0);
    $table->addCell(3000)->addText('Services (Water, Sewer and Sanitary Drainage etc.)', 'styleRegularBold', 'paragraphRegularL');
    $table->addCell(1000);
    $table->addCell(6000)->addText($this->arrPlan["Services"], 'styleRegular', 'paragraphRegular');
    
    
 
 
   
  $this->section->addTextBreak(1);
  $this->section->addTitle('Bylaws and Charges', 'H2');   
    
    
  $this->section->addText(htmlspecialchars('For the subject property, we noted that the strata council was using in the Disclosure Statement the Standard Bylaws in the Condominium Act (now Strata Property Act) and understand that this has no impact on this depreciation report.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('We reviewed the strata corporation’s bylaws to take into consideration any potential items or arrangements that might affect the common assets’ reserve planning. 
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The disclosure statement states that the fence upkeep is the responsibility of the abutting strata lot owner, but Standard Bylaws exceptions in Appendix D are not explicit on determining which strata corporation is responsible for the care and replacement of the fence.  
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Therefore, in the Standard Bylaws, under the section concerning obtaining approval before altering a strata lot, 
'), 'styleRegular','paragraphRegular');
    
    
 

$textrun = $this->section->createTextRun('paragraphRegularCit');  
$textrun->addText(htmlspecialchars('5 (1) An owner must obtain the written approval of the strata corporation before making an alteration to a strata lot that involves any of the following:
'), 'styleRegularCit','paragraphRegularCit'); 
$textrun = $this->section->createTextRun('paragraphRegularCit2');  

$textrun->addText(htmlspecialchars('(e) fences, railings or similar structures that enclose a patio, balcony or yard;'), 'styleRegularCit','paragraphRegularCit2'); 
$textrun->addTextBreak(1);
$textrun->addText(htmlspecialchars('(f) common property located within the boundaries of a strata lot;'), 'styleRegularCit','paragraphRegularCit2'); 
    

$this->section->addText(htmlspecialchars('As the responsibility of the care and replacement of the fences is presently not ascribed to a particular strata corporation, the strata corporation has indicated that the fences are to be excluded from the reserve requirements.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The above is not a legal opinion, it is only the Depreciation Report / Reserve Study Provider’s opinion on what we believe the documents state and is limited to the purpose of completing this depreciation report. If a reader wishes a legal interpretation of the above documents or the title, they are encouraged to seek legal counsel.
'), 'styleRegular','paragraphRegular');
    
     $this->section->addPageBreak();
}






function reserveComponentAnalysis() {
     
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTitle('Reserve Component Analysis', 'H1');
    
    $this->section->addTitle('Property Inspection', 'H2');
    $this->section->addText(htmlspecialchars('The property was inspected for the purposes of preparing this report on March 29, 2014, by Jeremy Bramwell, AACI, P.App., RI (BC), CRP.
'), 'styleRegular','paragraphRegular');
    
    
    
    $this->section->addTitle('Depreciation Reports or Reserve Fund Studies', 'H2');
    $this->section->addText(htmlspecialchars('To our knowledge no other depreciation reports or reserve fund studies have been completed for this strata corporation.
'), 'styleRegular','paragraphRegular');    
    
    
    
      $this->section->addTitle('Component Classification', 'H2');
    $this->section->addText(htmlspecialchars('Reserve fund components are conveniently classified in terms of building groups, common element facilities and site improvements. The component inventory consists of the reserve components, described and analyzed hereinafter, and shown in Schedules A, B, C.1, C.2 and C.3.
'), 'styleRegular','paragraphRegular');    
    
     $this->section->addText(htmlspecialchars('The complex has 11 reserve components, comprising of 10 site improvement components and 1 Studies component.
'), 'styleRegular','paragraphRegular');    
  

      $this->section->addTitle('Lifespan Analysis', 'H2');
    $this->section->addText(htmlspecialchars('Each reserve component has been analyzed in terms of life cycle condition and expected remaining useful life. The lifespan analysis considers the following factors:
'), 'styleRegular','paragraphRegular');        
    
    
$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_SQUARE_FILLED, 'spaceAfter'=>60,'spaceBefore'=>60);
    
$item = array('Type of Component', 'Utilization', 'Material', 'Workmanship', 'Quality', 'Exposure to Weather Conditions', 'Functional Obsolescence', 
'Environmental Factors', 'Regular Maintenance', 'Preventive Maintenance and', 'Observed Condition.');
    
    for($i = 0; $i < count($item); $i++) {
        $this->section->addListItem($item[$i], 3, 'styleRegular', $listStyle); 
    }
    
    
      $this->section->addText(htmlspecialchars('The critical aspect in a lifespan analysis is the observed condition of each reserve component, which is based on:
'), 'styleRegular','paragraphRegular');    
     
    
$item2 = array(     
'Actual age of the component', 
'Maintenance of the component', 
'Observed deficiencies of the component', 
'Repair and replacement experience and', 
'Probability of hidden conditions.');
    for($i = 0; $i < count($item2); $i++) {
        $this->section->addListItem($item2[$i], 3, 'styleRegular', $listStyle); 
    }      
      
    

      
      
    $this->section->addText(htmlspecialchars('The lifespan analysis culminates in component lifespan estimates, as follows:'), 'styleRegular','paragraphRegular');  
      
    $this->section->addText(htmlspecialchars('1. Expected Lifespan'), 'styleRegularBold','paragraphRegularIdent1'); 
    $this->section->addTextBreak(1);
    $this->section->addText(htmlspecialchars('Each reserve component is analyzed in terms of component type, quality of construction, statistical records and normal life experience.
'), 'styleRegular','paragraphRegularIdent2'); 
    $this->section->addTextBreak(1);
      
    $this->section->addText(htmlspecialchars('2. Effective Age Condition Analysis'), 'styleRegularBold','paragraphRegularIdent1'); 
    $this->section->addTextBreak(1);
    $this->section->addText(htmlspecialchars('This is the critical analysis of a reserve component and consists of determining the effective age of the reserve component within its normal life cycle based on the observed condition of the reserve component. The validity of this Condition Analysis depends on the experience of the depreciation report / reserve study planner or analyst, as this is a subjective estimate rather than an objective assessment.
'), 'styleRegular','paragraphRegularIdent2'); 
    $this->section->addTextBreak(1);
    
    
    $this->section->addText(htmlspecialchars('3. Remaining Lifespan'), 'styleRegularBold','paragraphRegularIdent1'); 
    $this->section->addTextBreak(1);
    $this->section->addText(htmlspecialchars('Given an expected lifespan estimate and a sound estimate of the effective age, the remaining lifespan of a reserve component is determined by subtracting the effective age estimate from the expected life span estimate. This does not mean that reserve expenditures should only be made at the end of the remaining life. Reserve expenditures should and must be made during the remaining life span to maintain building components and facilities in good condition.
'), 'styleRegular','paragraphRegularIdent2'); 
    $this->section->addTextBreak(1);    
    
  $this->section->addText(htmlspecialchars('A lifespan analysis is a subjective, or empirical, assessment of the life cycle status of a reserve component, and as such, it is only as good as the considered opinion of the depreciation report / reserve study planner. Furthermore, the life span of a reserve component is subject to change due to numerous factors.
'), 'styleRegular','paragraphRegular');       

 $this->section->addTitle('Current Cost Estimates', 'H2');
 
   $this->section->addText(htmlspecialchars('Reserve fund component assessments and current cost estimates are based on our investigation, observation, analyses and our extensive experience in performing depreciation reports.  
'), 'styleRegular','paragraphRegular');  
   
     $this->section->addText(htmlspecialchars('Cost data have been calculated using construction cost services, including Marshall & Swift/Boeckh Commercial Building Valuation System, and the R.S. Means Repair & Remodelling Cost Data, modified as to time, location and quality of construction. We also verified some estimates by seeking quotations from contractors, fabricators and suppliers. Moreover, we have used our own computer programs and extensive cost compilations and databases'), 'styleRegular','paragraphRegular');  
     
     
       $this->section->addText(htmlspecialchars('All costs are strictly estimates and are subject to confirmation at the time competitive bids are obtained from contractors specializing in the repair or replacement work required.
'), 'styleRegular','paragraphRegular');  
 
        $this->section->addText(htmlspecialchars('The following factors have been considered in calculating the major repair and replacement costs estimates:
'), 'styleRegular','paragraphRegular');  
 

        
        
$this->section->addText(htmlspecialchars('1. Quality of Construction'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('Replacement cost estimates are based on the assumption of using quality materials, as specified or built, or in the case of older developments, as required under current building code regulations, at contractors\' prices, using union labour rates and current construction techniques, and including contractors\' overhead and profit.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addText(htmlspecialchars('The costs of repairs and/or replacements of many reserve components are invariably higher than original building costs when contractors have considerable latitude in planning their work and can utilize economies of scale to keep costs within construction budgets.  In contrast, repair work must frequently be performed in an expedient manner with proper safety precautions and within certain constraints.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addText(htmlspecialchars('Cost estimates take into account such additional costs as special construction, safety installations, limited access, noise abatements, and the convenience of the occupants.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1); 

$this->section->addText(htmlspecialchars('2. Demolition and Disposal Costs'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('The estimates herein include provisions for demolition and disposal costs including dumping fees. These costs have been rising in recent years. Particularly, dumping of certain materials has become problematic and very costly. It appears that certain codes and environmental regulations will become more stringent in future years, all of which will further increase disposal costs.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1); 

$this->section->addText(htmlspecialchars('3. Goods and Services Tax'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('Goods and Services Tax (GST) applies to all repairs and replacements including disposal costs. Therefore, these costs are included in the reserve fund estimates hereinafter. '), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1); 

$this->section->addText(htmlspecialchars('4. Nature of Contingency'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('It is frequently impossible to forecast the incidence of repairs or replacements of various reserve components, particularly, major components, such as road pavement, sewer and water systems. Therefore, reserve estimates are of a contingency nature, and as such, they are subject to changing conditions and repair experience over time.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1); 



$this->section->addTitle('Reserve Component Descriptions and Analyses', 'H2');
   $this->section->addText(htmlspecialchars('The following pages list each reserve fund component and provides the following information:
'), 'styleRegular','paragraphRegular'); 



   
        
$this->section->addListItem('A Physical Description of the component and its elements', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('A reserve fund expenditure financial history', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('A review of the potential deterioration for the component', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('A condition analysis of the component and its elements', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('A life cycle analysis of the expected lifespan, effective age and remaining lifespan of the component', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('The estimated year of replacement for the component', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('Unit quantity and cost estimates', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('The current repair or replacement costs for the component and', 3, 'styleRegular', $listStyle); 
$this->section->addListItem('A deficiency analysis of findings and issues if any.', 3, 'styleRegular', $listStyle); 

   $this->section->addText(htmlspecialchars('The component boxes typically include images of the components and their elements as well as some images of conditions that may require further review.
'), 'styleRegular','paragraphRegular'); 
      
    
   /* components are part of this section. */
   $this->section->addPageBreak();
   $this->component();
   $this->section->addPageBreak();
}


function reserveFundComponentEstimates() {
    
    
    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTitle('Reserve Fund Component Estimates', 'H1');
    
    $this->section->addTitle('Strata Reserve Planning Benchmark Analysis', 'H2');    
    
   $this->section->addText(htmlspecialchars('Strata Reserve Planning’s’ benchmark analysis presents the various reserve components in light of their physical aspects, including their life cycle analysis and the cost estimates, all on a single sheet, for convenient examination and easy reference. 
'), 'styleRegular','paragraphRegular');     
   
   $this->section->addText(htmlspecialchars('The cost estimates are pursuant to prudent reserve fund planning practices, which provide for inflationary cost increases over time and take in consideration interest income from reserve fund investments (see Section 5.2 below).
'), 'styleRegular','paragraphRegular');     
   
   $this->section->addText(htmlspecialchars('The reserve fund estimates have been prepared without regard to the current financial position of the strata corporation or the current reserve fund contributions by unit owners, and as such, they represent the optimal reserve fund operation, which assumes that the corporation has continuously assessed adequate reserve funding from the beginning of the development.
'), 'styleRegular','paragraphRegular');     
   
   $this->section->addText(htmlspecialchars('This benchmark analysis is the foundation of the Strata Reserve Planning reserve fund planning system, as it provides the basis for comparison of models to the optimal reserve fund operation. The benchmark analysis provides the standard for reserve fund planning and property maintenance, and as such, it is a valuable management and maintenance resource.
'), 'styleRegular','paragraphRegular');     
   
   $this->section->addText(htmlspecialchars('The foregoing program represents the practical application of reserve fund budget planning and management. When applied, as outlined, the reserve fund will cover anticipated reserve fund expenditures and any contingencies. 
'), 'styleRegular','paragraphRegular');     
    
    
    
    
    
    
    
    
    $this->section->addTitle('Schedule A – Reserve Fund Component Estimates Benchmark', 'H2');  
    
    
    $this->section->addText(htmlspecialchars('The following schedule of reserve fund component estimates presents detailed computations for the reserve items using the projection factors explained in Section 2.4 of this Report. For the purpose of this depreciation report, the following values are used in the projections:
'), 'styleRegular','paragraphRegular');  
    
    $styleTable = array('cellMarginTop'=>125, 'align'=>'left');
    
    $table = $this->section->addTable($styleTable);
    
    $table->addRow(0);
    $table->addCell(2000);
    $table->addCell(4000)->addText('Long-term Inflation Rate:', 'styleRegular', 'paragraphRegularL');
    $table->addCell(4000)->addText('3.50%', 'styleRegular', 'paragraphRegular');    
    
    $table->addRow(0);
    $table->addCell(2000);
    $table->addCell(4000)->addText('Long-term Interest Rate::', 'styleRegular', 'paragraphRegularL');
    $table->addCell(4000)->addText('1.50%', 'styleRegular', 'paragraphRegular');     
    
    
    $this->section->addPageBreak();

$this->section = $this->PHPWord->createSection($this->sectionStyleLandscape);

    $imageStyle = array(
//    'width' => 629,
//    'height' => 325,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$this->section->addImage(TEMPLATE_IMG_PATH.'table3.jpg', $imageStyle);
 

$this->section = $this->PHPWord->createSection($this->sectionStyle);
    



$this->section->addTitle('Summary of Reserve Fund Estimates', 'H2');  


    $styleTable2 = array('cellMarginTop'=>0,'cellMarginBottom'=>0, 'align'=>'left');
    
    $table = $this->section->addTable($styleTable2);
    
    
$item = array(
'Current Replacement Reserves or Costs',
'Future Replacement Reserves or Costs',
'Current Reserve Fund Requirements',
'Future Reserve Fund Accumulations',
'Future Reserve Fund Requirements',
'Annual Reserve Fund Contributions'        
);

$itemText = array(
'Which are provisions for all major repairs and replacements costs at current prices', 
'Which are provisions for all major repair and replacement costs in the future at the  end of the expected lifespan',
'Which are reserve fund estimates based on the notion of effective age and should have been contributed by unit owners',
'Which are the current reserve fund requirements together with interest compounded over the remaining lifespan',
'Which are to be funded by unit owners\' payments to the reserve fund plus any interest earned',
'Which are the annual reserve fund payments to be made by unit owners'
);

$itemValue = array(
'$'.number_format($this->arrAux['CurrentReplacementCost']), 
'$'.number_format($this->arrAux['FutureReplacementCost']),
'$'.number_format($this->arrAux['CurrentReserveFundCostReq']),
'$'.number_format($this->arrAux['FutureReserveFundAcc']), 
'$'.number_format($this->arrAux['FutureReserveFundReq']),
'$'.number_format($this->arrAux['ReserveFundAnnualCon'])
);    

//'$162,713',
//'$501,654',
//'$63,643',
//'$95,298',
//'$406,356',
//'$10,218'

    
    for($i = 0; $i < count($item); $i++) {
    $table->addRow(0);
    $table->addCell(8000)->addText($item[$i], 'styleRegularBold', '');
    $table->addCell(2000);  
    $table->addRow(0);
    $table->addCell(8000);
    $table->addCell(2000)->addText($itemValue[$i], array('size'=>10, 'bold'=>true)); 
    $table->addRow(0);
    $table->addCell(8000)->addText($itemText[$i], null);
    $table->addCell(2000);
    $table->addRow(0);
    $table->addCell(2000);
    }
$this->section->addTextBreak(1);
    $this->section->addText(htmlspecialchars('In accordance with these estimates, under a Fully Funded scenario the strata corporation should have '.'$'.number_format($this->arrAux['CurrentReserveFundCostReq']).' in its contingency reserve fund at the end of its current fiscal year, and the assessed annual payments or contributions to the reserve fund by strata lot owners should be '.'$'.number_format($this->arrAux['ReserveFundAnnualCon']).' in the following year based on the stated assumptions. 
'), 'styleRegular','paragraphRegular');  

    $this->section->addText(htmlspecialchars('While these figures provide the basis of all reserve adequacy calculations in this report, the benchmark fully funded model may not necessarily be the recommended model. Please read on to review our recommended strategy for this development.
'), 'styleRegularBold','paragraphRegular'); 

    
    
    
    $this->section->addPageBreak();
}






function analysisOfReserveFundOperations() {

    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTextBreak(1);
    $this->section->addTitle('Analysis of Reserve Fund Operations', 'H1');
    $this->section->addText(htmlspecialchars('Reviewing and analyzing the reserve fund operation of Strata Corporation LMS 667, we were provided operating budget and reserve fund information for the past decade (1993-2013). Balance sheets and summaries of income and expenses were provided for the periods of 1993 to the end of the fiscal year 2013. No other financial documents (audited statements) were made available.
'), 'styleRegular','paragraphRegular');     
    
    
    
    $this->section->addTextBreak(1);
    $this->section->addTitle('Strata Corporation Financial Assumptions', 'H2');
    
         $this->section->addText(htmlspecialchars('Based on the data provided, we determined the reserve fund status and we used the values provided in the financials as the basis for the reserve fund opening balance.
'), 'styleRegular','paragraphRegular'); 
         
 $this->section->addTextBreak(1);
         $this->section->addTitle('Schedule B – Statement of Reserve Fund Operations', 'H2');
         
         $this->section->addText(htmlspecialchars('On the following page is illustrated the Component Financial Analysis as a breakdown of expenditures for the reserve fund for the last three years as per the financial information provided and based on the strata corporation’s fiscal year-end cycle.
'), 'styleRegular','paragraphRegular');          
         
         $this->section->addText(htmlspecialchars('Based on the data provided it appears that there are no expenditures from the reserve fund. It is our belief that most expenditures are coming out of the operating fund, which is not abnormal.  We recommend that in the future, expenditures on the items included in this depreciation report – the components and their items – be paid out of the reserve fund.
'), 'styleRegular','paragraphRegular');          
         
         $this->section->addText(htmlspecialchars('Component descriptions in section 4 provide spending details with regards to expenditures undertaken from the operating fund or the reserve fund, based on historical financial and other documents provided by the strata council and or their representatives.
'), 'styleRegular','paragraphRegular');          

$this->section->addPageBreak();

$this->section->addTitle('Benchmark Analysis', 'H2');
         
 $this->section->addText(htmlspecialchars('The current reserve fund requirement is an estimate of what a strata corporation would have if it was fully funded based on the benchmark analysis (Schedule A). The benchmark analysis calculates the difference between the current reserve fund requirement and the actual reserve fund balance. 
'), 'styleRegular','paragraphRegular');
 
 $this->section->addText(htmlspecialchars('The benchmark analysis has been developed by Strata Reserve Planning as a guide for the strata council to ensure that the reserve fund is adequately funded, neither under-funded nor over-funded. The reserve fund of Strata Corporation '.$this->arrPlan["StrataNumber"].' is showing a shortfall at the end of the '.Util::getYear($this->arrAux['ProjectedReserveFundBalanceDate']).' fiscal year as described below, based on the fully funded model:
'), 'styleRegular','paragraphRegular');


$styleTable = array('cellMarginTop'=>1, 'align'=>'left');
$table = $this->section->addTable($styleTable);

$table->addRow(0);
$table->addCell(7000)->addText('Opening Balance as of:   '.Util::formatDate($this->arrAux["OpeningBalanceDate"]), 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['OpeningBalanceValue']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Current Budgeted Annual Reserve Fund Contributions:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['CurrentBudgetedAnnualRFC']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Authorized Special Levies:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['AuthorizedSpecialLeveies']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Borrowings:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['Borrowings']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Loan Refinancing:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LoanRefinance']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Reserve Fund Tax Free Annual Interest Income:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['ReserveFundTaxFreeAnnualIntIncome']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Less Repayment of Financing Loan:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LessRepaymentOfFinancingLoan']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Less Budgeted Reserve Fund Expenditures for Current Fiscal Year:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LessReserveFundBudgetCurrentFYear']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Projected Reserve Fund Balance as of:   '.Util::formatDate($this->arrAux["ProjectedReserveFundBalanceDate"]), 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['ProjectedReserveFundBalanceValue']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Estimated Reserve Fund Requirements after Expenditures(Shortfall):', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['EstimatedReserveFund_Shortfall']), 'regular11', 'paragraphRegularR');




$table->addRow(0);
$table->addCell(7000)->addText('Budgeted Transfer from Operating Fund, '.Util::formatDate($this->arrAux["BudgetTransferFromDate"]).':', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['BudgetTransferFromValue']), 'regular11', 'paragraphRegularR');
  
$table->addRow(0);
$table->addCell(7000)->addText('Proposed Special Levies in '.Util::getYear($this->arrAux['ProposedSpecialLeveiesDate']).' required to meet Cash Shortage:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['ProposedSpecialLeveiesValue']), 'regular11', 'paragraphRegularR');
  
 $table->addRow(0);
$table->addCell(7000)->addText('Estimated Reserve Fund Adequacy after transfer from operating and special levy:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['EstimatedReserveFundAdequacy']), 'regular11', 'paragraphRegularR');
 
  

 $this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('paragraphRegular');

$textrun->addText(htmlspecialchars('In the fully funded scenario, the reserve fund deficiency would be eliminated in the next fiscal year with future contributions at a high enough level to keep the reserve strength at 100% as illustrated in Schedule C.2. '), 'styleRegular','paragraphRegular');
$textrun->addText(htmlspecialchars('This fully funded scenario is rarely recommended due to the inherent economic strain on the strata corporation, and is presented as the basis for determining reserve adequacies for other funding scenarios and for comparisons to other strata corporations.'), 'styleRegularBold','paragraphRegular');  
 
 
 
 
 
 




 $this->section->addTitle('Discussion on the Adequacy of a Reserve Fund', 'H2');         
         
$this->section->addText(htmlspecialchars('The adequacy of reserve fund may be defined as the reserve fund balance together with regular contributions and investment income, which constitutes sufficient cash resources available for all possible and potential reserve fund expenditures required for repairing or replacing common elements or assets of the strata corporation when needed.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The most direct and stringent measure of the adequacy of reserve fund is the reserve fund deficiency analysis, whereby the actual closing reserve fund balance is compared with the currently required reserve fund balance, as estimated by a competent depreciation report / reserve study planner.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Any significant difference between the actual reserve fund balance and the required reserve fund balance will indicate the amount of a reserve fund Surplus or reserve fund deficiency (shortfall).
A reserve fund surplus, particularly when such surplus is increased by excessive reserve fund contributions, means that unit owners have contributed too much to the reserve fund, a situation which should be corrected to eliminate over-contributions.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('A reserve fund deficit or shortfall indicates that unit owners have not contributed enough to the reserve fund, resulting in a discrepancy between a fully funded reserve fund and the actual reserve fund balance.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('There is currently no regulation in the Strata Property Act that requires that strata corporations run tests to ensure that their reserve fund is adequate for the purpose for which it has been established, only that a reserve fund be in place with minimum payments based on a percentage of the last year’s operating budget.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('The adequacy of a reserve fund does not require the test of an estimated fully funded reserve fund. The test as to the adequacy of a reserve fund is that sufficient cash resources to fund all potential repairs and replacements, including unforeseen events, are available.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('Therefore, a reserve fund deficiency or shortfall does not automatically mean that the reserve fund is not adequate. It is up to the judgment of the depreciation report / reserve study planner in concert with the strata council to determine whether the reserve fund is adequate to meet the strata council’s fiduciary responsibility to the strata corporation.
'), 'styleRegular','paragraphRegular');

$this->section->addText(htmlspecialchars('In our opinion, the current reserve fund and proposed contributions, special levies and loans for Strata Corporation '.$this->arrPlan["StrataNumber"].' require adherence to the recommendations listed in this report to be adequate for future reserve fund expenditures.
'), 'styleRegular','paragraphRegular');
         
         
$this->section->addPageBreak();

  $this->section->addTitle('Strata Reserve Planning’s Recommended Funding Model', 'H2');         
 
  $this->section->addText(htmlspecialchars('The reserve fund deficiency should be eliminated over time, as stated above. However, this is not always possible for financial or other reasons and/or the strata council may make a decision based on their risk analysis with the depreciation report / reserve study planner.
'), 'styleRegular','paragraphRegular');
  
$this->section->addText(htmlspecialchars('The threshold funding model is a percentage based funding scenario with the reserve fund expenditures taken from the benchmark analysis. The goal is to achieve a suitable reserve fund at a level less than being fully funded. The use of the threshold funding model will result in a reserve fund liability at the end of year 1 as indicated below:
'), 'styleRegular','paragraphRegular');
  
  

  
  
  
  
  
  
  
//pg62
$styleTable = array('cellMarginTop'=>1, 'align'=>'left');
$table = $this->section->addTable($styleTable);

$table->addRow(0);
$table->addCell(7000)->addText('Opening Balance as of:   '.Util::formatDate($this->arrAux["OpeningBalanceDate"]), 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['OpeningBalanceValue']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Current Budgeted Annual Reserve Fund Contributions:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['CurrentBudgetedAnnualRFC']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Authorized Special Levies:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['AuthorizedSpecialLeveies']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Borrowings:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['Borrowings']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Loan Refinancing:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LoanRefinance']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Reserve Fund Tax Free Annual Interest Income:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['ReserveFundTaxFreeAnnualIntIncome']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Less Repayment of Financing Loan:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LessRepaymentOfFinancingLoan']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Less Budgeted Reserve Fund Expenditures for Current Fiscal Year:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['LessReserveFundBudgetCurrentFYear']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Projected Reserve Fund Balance as of:   '.Util::formatDate($this->arrAux["ProjectedReserveFundBalanceDate"]), 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['ProjectedReserveFundBalanceValue']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Estimated Reserve Fund Requirements after Expenditures:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['EstimatedReserveFund_Shortfall']), 'regular11', 'paragraphRegularR');


$table->addRow(0);
$table->addCell(7000)->addText('Estimated Reserve Fund Deficiency / Surplus:', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText('$'.number_format($this->arrAux['EstimatedReserveFundDeficiency']), 'regular11', 'paragraphRegularR');

$table->addRow(0);
$table->addCell(7000)->addText('Reserve Adequacy, '.Util::formatDate($this->arrAux["ReserveAdequacyDate"]).':', 'regular11', 'paragraphRegularL');
$table->addCell(3000)->addText($this->arrAux['ReserveAdequacyValue'].'%', 'regular11', 'paragraphRegularR');
    
  
  $this->section->addText(htmlspecialchars('The reserved fund deficiency calculated above will not be eliminated over the thirty years, but our recommended plan to increase the reserve adequacy over time, using the threshold scenario has been determined as the best strategy by the strata council. The reserve adequacy established above is for the 2014 fiscal-year only. It will change over the 30 year period, getting stronger throughout the years.
'), 'styleRegular','paragraphRegular');
  
    $this->section->addText(htmlspecialchars('At this time, we are recommending that Strata Corporation '.$this->arrPlan["StrataNumber"].' work to improve their reserve adequacy from '.$this->arrAux['Year1ReserveAdequacy'].'% towards '.$this->arrAux['Year30ReserveAdequacy'].'% at the end of the 30 year period. 
'), 'styleRegularBold','paragraphRegular');

   $this->section->addText(htmlspecialchars('The chosen model’s projected cash flows are illustrated in Schedule C.1 – Threshold Model – 30 Year Reserve Fund Cash Flow Projections and Analysis. The reserved fund deficiency established above is for the current year only. It will change over the 30 year period, getting stronger throughout the years.
'), 'styleRegular','paragraphRegular');
   
     $this->section->addText(htmlspecialchars('The existing unfunded “Pay as you Go” reserve fund model was not recommended. The projected cash flows for this scenario are provided in Schedule C.3 – Unfunded ‘Pay as you Go’ Model – 30 years Reserve Fund Cash Flow Projection and Analysis.
'), 'styleRegular','paragraphRegular');
 
$this->section->addPageBreak();
    
    
    
}

    
function reserveFundManagement() {
    
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTitle('Reserve Fund Management – 30 Year Projection Scenarios', 'H1');
    $this->section->addTitle('Schedules C.1, C.2 and C.3 – 30 Year Cash Flow Projections and Analysis', 'H2');
    
    $this->section->addText(htmlspecialchars('The reserve fund projected cash flow and deficiency analysis that follow presents a 30 year projection for each funding model including cash positions, cash flows and cash expenditures in a form and detail which conforms to the financial statement presentation of reserve fund operations.
'), 'styleRegular','paragraphRegular');    
   
    $this->section->addText(htmlspecialchars('What follows are definitions of the terms used in the three schedules:
'), 'styleRegular','paragraphRegular');      
    
 
$this->section->addText(htmlspecialchars('Reserve Fund Opening Balance'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('This is the reserve fund position at the beginning of each and every fiscal year showing the cash resources available, that consist of 1) bank deposits, 2) qualified investments, and 3) accrued interest earned.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Cash Flows'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('These are the regular reserve fund inflows that consist of 1) contributions, 2) interest income, 3) special levies or 4) loans based on the reserve fund opening balance.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);
    
$this->section->addText(htmlspecialchars('Total Cash Resources'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('These represent the total cash resources available in any fiscal-year and include the current year’s cash flow.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Current Reserve Fund Requirements'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('The current reserve fund requirement is an estimate of what a strata corporation would have if it was fully funded based on the benchmark analysis (Schedule A).
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Reserve Fund Expenditures'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('These are annual expenditures listed in the categories established by the depreciation report / reserve study planner and include all component related expenditures during one fiscal-year. They will be the same in all three scenarios.  Records or ledger accounts of these expenditures should be kept showing reserve fund allocations and charges in a chronological order for control and reference purposes.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Reserve Fund Closing Balance'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('This is the reserve fund position at the end of each and every fiscal year and is carried forward to the next year as the reserve fund opening balance.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Reserve Surplus (Deficiency) Analysis'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('The management of the reserve fund is the responsibility of the strata council and this is the amount the strata corporation should have in the bank to meet its fully funded reserve requirements. This figure is projected by formula taking into account the inflation factor, interest rates and reserve fund expenditures. As reserve fund expenditures do not change, the only variable that will change the deficiency level is the level of cash inflows (contributions, special levies, loans, and interest income).
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Reserve Adequacy'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('This is the true measure of the strength of the contingency reserve fund and is represented as a percentage of the fully funded scenario projections – a more detailed explanation is found in section 6.4.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);

$this->section->addText(htmlspecialchars('Per Strata Lot Unit Analyses – Average Strata Lot (ASL)'), 'styleRegularBold','paragraphRegularIdent1'); 
$this->section->addTextBreak(1);
$this->section->addText(htmlspecialchars('Strata Reserve Planning provides contributions, interest, special levies and other variables per Average Strata Lot (ASL). The Average Strata Lot (ASL) is achieved by dividing the respective reserve fund variables by the total number of strata lots in a Strata Corporation. A supplementary report may be produced that provides the information in terms of each strata lot’s number of entitlement units.
'), 'styleRegular','paragraphRegularIdent2'); 
$this->section->addTextBreak(1);
    



    $this->section->addTitle('Projected Reserve Fund Scenarios', 'H2');
    
    $this->section->addText(htmlspecialchars('The reserve fund scenarios’ 30 year cash flow projection and analysis presented on the next pages – both in table form and graphically – are mere guidelines in terms of the timing of expenditures based on the lifespan analysis.
'), 'styleRegular','paragraphRegular');    
   
    $this->section->addText(htmlspecialchars('The reserve funding scenarios are based on the hypothetical condition that the increase from $'.$this->arrAux['MonthlyASLContributions'][0]['value'].' per ASL to $'.$this->arrAux['MonthlyASLContributions'][2]['value'].' per ASL per month is approved at a strata corporation Special General Meeting (SGM) or Annual General Meeting (AGM) in the current fiscal-year cycle. 
'), 'styleRegular','paragraphRegular'); 




$this->section = $this->PHPWord->createSection($this->sectionStyleLandscape);

    $imageStyle = array(
//    'width' => 629,
//    'height' => 325,
    'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontalRel' => 'margin',
    'posVerticalRel' => 'line',
);
$this->section->addImage(TEMPLATE_IMG_PATH.'table4.jpg', $imageStyle);
$this->section->addImage(TEMPLATE_IMG_PATH.'table5.jpg', $imageStyle);
$this->section->addImage(TEMPLATE_IMG_PATH.'table8.jpg', $imageStyle);
$this->section->addImage(TEMPLATE_IMG_PATH.'table9.jpg', $imageStyle);


$this->section = $this->PHPWord->createSection($this->sectionStyle);

$this->section->addText(htmlspecialchars('Schedule C.1 – THRESHOLD MODEL – SCENARIO SUMMARY'), 'regular10Bold','paragraphRegular');
$this->section->addImage(TEMPLATE_IMG_PATH.'chart5.jpg', $imageStyle);

$this->section->addText(htmlspecialchars('Schedule C.2 – FULL FUNDING MODEL – SCENARIO SUMMARY'), 'regular10Bold','paragraphRegular');
$this->section->addImage(TEMPLATE_IMG_PATH.'chart6.jpg', $imageStyle);

$this->section->addText(htmlspecialchars('Schedule C.3 – UNFUNDED \'PAY AS YOU GO\' MODEL – SCENARIO SUMMARY'), 'regular10Bold','paragraphRegular');
$this->section->addImage(TEMPLATE_IMG_PATH.'chart7.jpg', $imageStyle);

$this->section->addText(htmlspecialchars('RESERVE ADEQUACIES OVER 30 YEARS'), 'regular10Bold','paragraphRegular');
$this->section->addImage(TEMPLATE_IMG_PATH.'chart8.jpg', $imageStyle);

$this->section->addText(htmlspecialchars('RESERVE FUNDING PLAN FINANCIAL SUMMARIES'), 'regular10Bold','paragraphRegular');
$this->section->addImage(TEMPLATE_IMG_PATH.'chart9.jpg', $imageStyle);



    $this->section->addTitle('Reserve Fund Management', 'H2');
    
    $this->section->addText(htmlspecialchars('The Strata Property Act and Regulations provides that the strata council prepare their own plan for future funding of the reserve fund, and they are not bound by the recommendations of the depreciation report / reserve study planner.
'), 'styleRegular','paragraphRegular');    
   
    $this->section->addText(htmlspecialchars('The Province of British Columbia has passed the Strata Property Act, Statutes of British Columbia 1998, Chapter C 43, with amendments in force as of November 18, 2009. Part 6 mandates that strata corporations must establish and maintain reserve funds, to wit:
'), 'styleRegular','paragraphRegular'); 
    
    

$textrun = $this->section->createTextRun('paragraphRegularCit');  
$textrun->addText(htmlspecialchars('6.1 For the purposes of section 93 of the Act, the amount of the annual contribution to the contingency reserve fund for a fiscal year, other than the fiscal year following the first annual general meeting, must be determined as follows: 
'), 'styleRegularCit','paragraphRegularCit'); 
$textrun = $this->section->createTextRun('paragraphRegularCit2');  

$textrun->addText(htmlspecialchars('(a) if the amount of money in the contingency reserve fund at the end of any fiscal year after the first annual general meeting is less than 25% of the total amount budgeted for the contribution to the operating fund for the fiscal year that has just ended, the annual contribution to the contingency reserve fund for the current fiscal year must be at least the lesser of 
'), 'styleRegularCit','paragraphRegularCit2'); 
$textrun->addTextBreak(1);
$textrun->addText(htmlspecialchars('(i)  10% of the total amount budgeted for the contribution to the operating fund for the current fiscal year, and
'), 'styleRegularCit','paragraphRegularCit2'); 
$textrun->addTextBreak(1);
$textrun->addText(htmlspecialchars('(ii)  the amount required to bring the contingency reserve fund to at least 25% of the total amount budgeted for the contribution to the operating fund for the current fiscal year; 
'), 'styleRegularCit','paragraphRegularCit2'); 
$textrun->addTextBreak(1);
$textrun->addText(htmlspecialchars('(b) if the amount of money in the contingency reserve fund at the end of any fiscal year after the first annual general meeting is equal to or greater than 25% of the total amount budgeted for the contribution to the operating fund for the fiscal year that has just ended, additional contributions to the contingency reserve fund may be made as part of the annual budget approval process after consideration of the depreciation report, if any, obtained under section 94 of the Act.
'), 'styleRegularCit','paragraphRegularCit2'); 

$this->section->addTextBreak(1);

    $this->section->addText(htmlspecialchars('This means that the strata council can vary the recommended funding. The strata council must only take into consideration the depreciation report if they wish to have contributions over 25% of the annual operation budget. In effect, the strata council does have a fiduciary duty to ensure that the reserve fund is adequate for financing all future major repairs and replacements. 
'), 'styleRegular','paragraphRegular'); 
    
    $this->section->addText(htmlspecialchars('In the subject instance, other than increasing reserve fund contributions, the strata council may elect to have the strata corporation membership pass a special levy or several levies to eliminate the reserve fund shortfall.
'), 'styleRegular','paragraphRegular');     




   $this->section->addPageBreak(); 
}
    
  
function recommendations() {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    
    $this->section->addTitle('Recommendations', 'H1');
    $this->section->addText(htmlspecialchars('There is no legislated rule to eliminated or run a test for the adequacy of the reserve fund in B.C. As per the fiduciary duty of the strata council members, as well as the intent of the Strata Property Act and Regulations, the deficiency should be managed and eliminated over time in order to be adequate to repair, replace and restore common area assets as required. 
'), 'styleRegular','paragraphRegular');
    
    $this->section->addText(htmlspecialchars('Strata Reserve Planning recommendations, set out below and detailed in this report, will assist the strata corporation in achieve and maintaining an adequate reserve fund. In our opinion, the current reserve fund balance, recommended annual contributions and earned investment income will adequately fund immediate and future reserve fund expenditures
'), 'styleRegular','paragraphRegular');      
    
    $listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER, 'spaceAfter'=>60,'spaceBefore'=>60);

    $this->section->addListItem('The corporation should prepare and implement a long-term reserve fund strategy including creating a CRF line item in the annual budget, and opening a separate CRF account. Major repairs and replacements should be recorded in, and funded from, the reserve fund account. The use of the operating budget should be limited to yearly expenses. 
,', 0, 'styleRegular',$listStyle);
    $this->section->addTextBreak(1);
    $this->section->addListItem('The reserve fund contribution of $'.  number_format($this->arrAux['AnnualReserveFundContributions'][0]['value']).' per annum in '.$this->arrAux['AnnualReserveFundContributions'][0]['year'].' should be increased to $'.number_format($this->arrAux['AnnualReserveFundContributions'][1]['value']).' per annum in '.$this->arrAux['AnnualReserveFundContributions'][1]['year'].' and thereafter by the amounts detailed in Schedule C.1 – Threshold Model – 30 year Reserve Fund Cash Flow table for each subsequent year.
,', 0, 'styleRegular',$listStyle);
    $this->section->addTextBreak(1);
    $listItemRun = $this->section->addListItemRun(0, $listStyle,'styleRegular');
$listItemRun->addText('The reserve fund will require no special levies to insure reserve adequacy targets set out by the strata council. ','regular12Red');
$listItemRun->addText('A $'.number_format($this->arrAux['AnnualReserveFundContributions'][2]['value']).' special levy is also recommended in '.$this->arrAux['AnnualReserveFundContributions'][2]['year'].', with periodic additional special levies required to maintain an adequate contingency reserve fund.
', 'styleRegular');
$this->section->addTextBreak(1);
    $this->section->addListItem('The reserve fund should be fully invested in guaranteed securities, yielding at least 1.50% per annum.
,', 0, 'styleRegular',$listStyle);
    $this->section->addTextBreak(1);
    $this->section->addListItem('The strata corporation should make such expenditures, as necessary to maintain the property in optimum condition.
,', 0, 'styleRegular',$listStyle);
    $this->section->addTextBreak(1);
    $this->section->addListItem('The reserve fund should be reviewed every year to ensure that the underlying assumptions are still valid and that the estimates remain current.
,', 0, 'styleRegular',$listStyle);
    $this->section->addTextBreak(1);
    $this->section->addListItem('The strata corporation should update the depreciation report every three (3) years. 
,', 0, 'styleRegular',$listStyle);

    
$this->section->addPageBreak();

}

    function appendices() {
        $this->section = $this->PHPWord->createSection($this->sectionStyle);
        $this->section->addTextBreak(15); 
        $this->section->addText(htmlspecialchars('Appendices'), array('color'=>'000000', 'size'=>18, 'bold'=>true),'coverTextP');   
        $this->section->addPageBreak();
    }
    
    
    function appendiceA() {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $this->section->addTitle('APPENDIX A – CORPORATE AND WRITER CREDENTIALS', 1);


    
        $this->section->addPageBreak();
    }    
    
    
    function appendiceB() { 
        $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $this->section->addTitle('APPENDIX B – TERMS AND DEFINITIONS', 1);
    
$this->section->addText('ALLOWANCES: some of the individual line items in the depreciation report physical analysis are for a type of component category that account for events that may or may not happen. It is prudent to have some savings in the reserve fund for these events, so that if they do happen, funds are available. Allowances typically are:
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('1) A strata corporation responsibility,', 'styleRegular', 'regularHaning2');
$this->section->addText('2) With an expected lifespan normally equal to, or beyond that of the physical life of the complex,', 'styleRegular', 'regularHaning2');

$this->section->addText('3) Set aside based on a small probability that unforeseen deterioration will occur thus reducing the remaining lifespan of the component so that it requires maintenance during the life of the development, 
', 'styleRegular', 'regularHaning2');
$this->section->addText('4) For a component with a cost above a minimum threshold; and  ', 'styleRegular', 'regularHaning2');
$this->section->addText('5) For a portion of the full component replacement cost.', 'styleRegular', 'regularHaning2');


$this->section->addTextBreak(1);
$this->section->addText('CASH FLOW FUNDING METHOD: a method of developing a reserve funding plan where contributions to the reserve fund are designed to offset the variable annual expenditures from the reserve fund. Different reserve funding plans are tested against the anticipated schedule of reserve expenditures until the desired funding goal is achieved.
', 'styleRegular', 'regularHaning');



$this->section->addTextBreak(1);

$this->section->addText('COMPONENT: the individual line items in the depreciation report developed or updated in the physical analysis. These items form the building blocks for the depreciation report. Components typically are:
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('1) A strata corporation responsibility, ', 'styleRegular', 'regularHaning2');
$this->section->addText('2) With limited expected lifespan expectancies,', 'styleRegular', 'regularHaning2');

$this->section->addText('3) With predictable remaining lifespan expectancies; and ', 'styleRegular', 'regularHaning2');
$this->section->addText('4) With a cost above a minimum threshold.', 'styleRegular', 'regularHaning2');



$this->section->addTextBreak(1);
$this->section->addText('COMPONENT FINANCIAL ANALYSIS: the portion of the component inventory where the component being reported on has its’ financial history described. Part of the overall physical analysis.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('COMPONENT INVENTORY: the task of selecting and quantifying reserve components. This task can be accomplished through on-site visual observations, review of strata corporation design and organizational documents, a review of established strata corporation precedents, and discussion with appropriate strata corporation representative(s).
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('CONDITION ANALYSIS: the portion of the component inventory where the current condition of the component being reported on is evaluated based on observed or reported characteristics.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('CONTINGENCY: see ALLOWANCES.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('CONTINGENCY RESERVE FUND (CRF): the fund required under the British Columbia Strata Property Act and Regulations for strata corporations’ long-term major repair and replacement of common property assets.
', 'styleRegular', 'regularHaning');



$this->section->addTextBreak(1);
$this->section->addText('CURRENT REPAIR OR REPLACEMENT COST: the cost of replacing, repairing, or restoring a reserve component to its original functional condition. The current repair or replacement cost would be the cost to replace, repair, or restore the component during that particular year.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('DEFICIT: an actual (or projected) reserve balance less than the fully funded balance. The opposite would be a surplus.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('DEPRECIATION REPORT / RESERVE STUDY PLANNER: an individual or firm which prepares depreciation reports.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('DEPRECIATION REPORT / RESERVE STUDY PROVIDER: see DEPRECIATION REPORT / RESERVE STUDY PLANNER.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('DEPRECIATION REPORT: a budget-planning tool which identifies the current status of the reserve fund and a stable and equitable funding plan to offset the anticipated future major common area expenditures. The depreciation report consists of two parts: the physical analysis and the financial analysis. 
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('EFFECTIVE AGE: the difference between expected lifespan and remaining lifespan. It is not always equivalent to chronological age, since some components age irregularly. Used primarily in computations.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('EXPECTED LIFESPAN: total useful life or depreciable life. The estimated time, in years, that a reserve component can be expected to serve its intended function if properly constructed in its present application or installation. Sometime called useful life (UL).
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('FINANCIAL ANALYSIS: the portion of a Depreciation Report where the current status of the reserves (measured as cash or reserve adequacy) and a recommended reserve contribution rate (reserve funding plan) are derived, and the projected reserve income and expense over time is presented. The financial analysis is one of the two parts of a depreciation report.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('FULLY FUNDED: 100% funded. When the actual (or projected) reserve balance is equal to the fully funded balance.
', 'styleRegular', 'regularHaning');


$this->section->addTextBreak(1);
$this->section->addText('FULLY FUNDED BALANCE (FFB): refers to total accrued depreciation. An indicator against which actual (or projected) reserve fund balances can be compared. The reserve balance that is in direct proportion to the fraction of life “used up” of the current repair or replacement cost. This number is calculated for each component and then summed together for a strata corporation total. Two formulas can be utilized, depending on the provider’s sensitivity to interest and inflation effects – both yield identical results when interest and inflation are equivalent:
', 'styleRegular', 'regularHaning');

//$this->section->addText('FFB1 = Current Cost X Effective Age / Useful Life', 'styleRegular', 'regularHaning2');
$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('FFB'), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('1'), array('size'=>12,'superScript'=>true)); 
$textrun->addText(htmlspecialchars(' = Current Cost X Effective Age / Useful Life'), 'styleRegular','paragraphRegular'); 

$this->section->addText(htmlspecialchars('Or'), 'styleRegular','paragraphRegularC');

$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('FFB'), 'styleRegular','paragraphRegular'); 
$textrun->addText(htmlspecialchars('2'), array('size'=>12,'superScript'=>true)); 
$textrun->addText(htmlspecialchars(' = (Current Cost X Effective Age / Useful Life) + [(Current Cost X Effective Age / Useful Life) / (1 + Interest Rate) ^ Remaining Life] - [(Current Cost X Effective Age / Useful Life) / (1 + Inflation Rate) ^ Remaining Life].'), 'styleRegular','paragraphRegular'); 


$this->section->addTextBreak(1);
$this->section->addText('FUND STATUS: the status of the reserve fund as compared to an established benchmark such as percent funding.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('FUNDING GOALS: independent of methodology utilized, the following represent the basic categories of funding plan goals:
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('Full Funding: '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('setting a reserve funding goal of attaining and maintaining reserves at or near 100% funded.'), 'styleRegular','paragraphRegular');



$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('Threshold Funding: '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('establishing a reserve funding goal of keeping the reserve balance above a specified dollar or percent funded amount. Depending on the threshold, this may be more or less conservative than fully funded.
'), 'styleRegular','paragraphRegular');

$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('Baseline Funding: '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('establishing a reserve funding goal of keeping the reserve cash balance above zero at a specified dollar amount. '), 'styleRegular','paragraphRegular');

$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning2');
$textrun->addText(htmlspecialchars('Statutory Funding: '), 'styleRegularBold','paragraphRegular'); 
$textrun->addText(htmlspecialchars('establishing a reserve funding goal of setting aside the specific minimum amount of reserves required by local statutes.'), 'styleRegular','paragraphRegular');


$this->section->addTextBreak(1);
$this->section->addText('FUNDING PLAN: a strata corporation’s plan to provide income to a reserve fund to offset anticipated expenditures from that fund.
', 'styleRegular', 'regularHaning');

$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_SQUARE_FILLED, 'spaceAfter'=>60,'spaceBefore'=>60);
 

$this->section->addTextBreak(1);
$textrun = $this->section->createTextRun('regularHaning');
$textrun->addText(htmlspecialchars('FUNDING PRINCIPLES:'), 'styleRegular','paragraphRegular'); 

$this->section->addListItem('Sufficient funds when required,', 0, 'styleRegular', $listStyle);
$this->section->addListItem('Stable contribution rate over the years,', 0, 'styleRegular', $listStyle);
$this->section->addListItem('Evenly distributed contributions over the years, and', 0, 'styleRegular', $listStyle);
$this->section->addListItem('Fiscally responsible.', 0, 'styleRegular', $listStyle);
   
$this->section->addTextBreak(1);
$this->section->addText('LIFE CYCLE ESTIMATES: the task of estimating the expected lifespan or useful life, the effective age, the remaining lifespan and the repair or replacement costs for the reserve components.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('L.F.: refers to linear feet measurements in the component boxes and in the schedules.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('PHYSICAL ANALYSIS: the portion of the depreciation report where the component inventory, condition analysis, and life estimate tasks are performed. This represents one of the two parts of the depreciation report.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('PHYSICAL DESCRIPTION: the portion of the component inventory where the component being reported on is described.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('REMAINING LIFESPAN (RLS): also referred to as remaining life (RL). The estimated time, in years, that a reserve component can be expected to continue to serve its intended function. 
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('REPLACEMENT COST: the cost of replacing, repairing, or restoring a reserve component to its original functional condition. The current replacement cost would be the cost to replace, repair, or restore the component during that particular year.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('RESERVE ADEQUACY: the ratio, at a particular point of time (typically the end of the fiscal year), of the actual (or projected) reserve balance to the fully funded balance, expressed as a percentage. The ratio indicates the ability of the strata corporation to adequately cover its expenditures in any given time.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('RESERVE BALANCE: actual or projected funds as of a particular point in time that the strata corporation has identified for use to defray the future repair or replacement of those major components which the strata corporation is obligated to maintain. Also known as reserves, reserve accounts or cash reserves. Based upon information provided and not typically audited.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('SPECIAL LEVY: a cash amount collected from the members of a strata corporation in addition to regular contributions. Special levies are governed by the Strata Property Act and Regulations.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('S.F.: refers to square feet measurements in the component descriptions and the schedules.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('SURPLUS: an actual (or projected) reserve balance greater than the fully funded balance. See DEFICIT.
', 'styleRegular', 'regularHaning');

$this->section->addTextBreak(1);
$this->section->addText('USEFUL LIFE (UL): See EXPECTED LIFESPAN.', 'styleRegular', 'regularHaning');


    }
    
function appendiceC() {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $this->section->addTitle('APPENDIX C – ASSUMPTIONS AND LIMITING CONDITIONS', 1);
    
    $this->section->addText(htmlspecialchars('It is understood throughout this whole report that Strata Reserve Planning is a division of Bramwell & Associates Realty Advisors Inc. Acceptance of and/or use of this report constitutes acceptance of the following assumptions and limiting conditions. These can only be modified by written documents executed by both parties.
'), 'styleRegular','paragraphRegular');    
    
    
$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER, 'spaceAfter'=>60,'spaceBefore'=>60);

$this->PHPWord->addNumberingStyle(
    'multilevelC',
    array('size'=>12,'type' => 'multilevel', 'levels' => array(
        array('format' => 'decimal', 'text' => '%1.', 'left' => 820, 'size'=>12,'hanging' => 360),
        array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        ), 'listType'=>PHPWord_Style_ListItem::TYPE_NUMBER, 'spaceAfter'=>60,'spaceBefore'=>60
     )
);


$this->section->addListItem(htmlspecialchars('The depreciation report planner assumes no responsibility for matters of a legal nature affecting the property appraised or the title thereto, nor does the depreciation report planner render any opinion as to the title, which is assumed to be good and marketable. The property is reviewed as though under responsible ownership.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The depreciation report planner has made no survey of the property. The building plan and/or sketches in this report are included to assist the reader to visualize the subject property and the depreciation report planner assumes no responsibility for their accuracy. Unless otherwise stated in this report the depreciation report planner has assumed the utilization of the land and improvements is within the boundaries or property lines of the property described and there is no encroachment or trespass.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The depreciation report planner is not required to give testimony or appear in court because of having made the depreciation report with reference to the property in question, unless arrangements have been previously made. If this report is entered in as expert evidence, and the writer is required to appear, the client agrees upon submission of the report to court, to compensate the writer, or indemnify the writer in the case of opposition counsel, at the rate of $150.00 per hour for court research, preparation, travel and availability time.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The depreciation report planner has not inspected or tested the soil or subsoil and the depreciation report planner is unable to report any such part of the subject property is free from defect or in such condition as to render the subject property less valuable. For the purpose of this report, the depreciation report planner has assumed there are no inadequacies, insufficiencies, or faults in the subject property, which are not easily detectable and assume no responsibility for such conditions or any inspection, which might be required to discover such conditions.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Information, estimates and opinions furnished to the depreciation report planner contained in the report were obtained from sources considered reliable and believed to be true and correct. However, the depreciation report planner does not assume responsibility for the accuracy of such items furnished to the depreciation report planner. The depreciation report planner reserves the right to make adjustments to the depreciation report herein reported, as may be required by the consideration of additional data or more data made available. Such amendment may be subject to an additional charge if the data was not available until after the report is presented for review to the strata council. 
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('In order to arrive at supportable replacement cost estimates, it was found necessary to utilize both documented and other cost data. A concerted effort has been put forth to verify the accuracy of the information contained herein. Accordingly, the information is believed to be reliable and correct, and it has been gathered to standard professional procedures, but no guarantee as to the accuracy of the data is implied.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Disclosure of the contents of the appraisal report is governed by the by-laws and regulations of professional appraisal, depreciation report and reserve study organizations with which the depreciation report planner is affiliated.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('No liens or encumbrances were considered unless otherwise stated in this report.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('This report is for use by the strata council of Strata Corporation '.$this->arrPlan['StrataNumber'].' for depreciation report – reserve planning purposes only, as required under British Columbia legislation. This report was ordered by the addressed client, and prepared to meet their content expectations. In accepting this report, the client expressly agrees not to use or rely on the report for any other purposes.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Neither all, nor any part of the contents of the report or copy thereof (including conclusions as to the list of component, their condition or the related values, the identity of the depreciation report planner, professional designations, reference to any professional organizations, or the firm with which the depreciation report planner is connected) shall be used for any purposes by anyone but the client specified in the report. This includes any strata lot mortgagee or its successors and assigns, mortgage insurers, consultants, professional reserve planner or appraisal organizations, agency or instrumentality of the Canadian government or any province without the previous written consent of the depreciation report planner; nor shall it be conveyed by anyone to the public through advertising, public relations, news, sales, or other media without written consent and approval of the depreciation report planner. Notwithstanding the foregoing, the applicant herein has permission to reproduce the report in whole or in part for the legitimate purposes of providing information to the strata council, unit owners and others, who have an interest in the project. Specifically, the applicant has permission to provide depreciation report – reserve study information in disclosure documents, such as a Form B.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Unless otherwise stated in this report, the existence of hazardous substances, including, but without limitation to, mould and mildew, asbestos, polychlorinated biphenyls, petroleum leakage, or agricultural chemicals, which may be present on the property, or other environmental conditions, were not called to the attention of nor did the depreciation report planner become aware of such during the depreciation report planner’s inspection. The depreciation report planner has no knowledge of the existence of such materials on or in the property unless otherwise stated. The reserve expenditures estimated is predicated on the assumption there is no such condition on or in the property or in such proximity thereto that it would cause an unexpected loss in the reserve fund, resulting in increased possible special levies. The depreciation report planner is not qualified to test such substances or conditions. No responsibility is assumed for any such conditions, or for any expertise or engineering knowledge required to discover them. The depreciation report planner urges the client to retain an expert if desired.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Further, the depreciation report planner has not carried out any investigation into the past or present uses of either the subject property or of any adjacent properties to establish whether there is any potential for contamination from any uses on any sites adjacent to the subject and therefore assume that none exists.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The depreciation report planner has assumed the subject property is and has been constructed, occupied and used in full compliance with, and without contravention of, all federal, provincial and municipal laws and regulations, including, but not limited to, all zoning bylaws, building codes and regulations, environmental laws and regulations, health regulations and fire regulations, except only where otherwise stated. The depreciation report planner has further assumed, for any use of the subject property upon which this report is based, any and all required licenses, permits, certificates, and authorizations have been or can be obtained and renewed, except only where otherwise stated.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The reported analyses, opinions and conclusions were developed, and this report has been prepared, in conformity with the requirements of the Code of Professional Ethics and the Standards of Professional Appraisal Practice of the Appraisal Institute of Canada, as well as the Real Estate Institute of Canada’s codes. This depreciation report was completed in conformity with the Canadian Uniform Standards of Professional Appraisal Practice (CUSPAP) as regulated by the Appraisal Institute of Canada.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Component quantities indicated in this report were developed by Strata Reserve Planning – a division of Bramwell & Associates Realty Advisors Inc., unless otherwise noted in our site inspection notes comments. No destructive or intrusive testing was performed, nor should the site inspection be assumed to be anything other than for budget purposes. We are not responsible for identifying latent construction defects.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Because we have no control over future events, we cannot claim that all the events we anticipate will occur as planned. We expect that inflationary trends will continue, and we expect that financial institutions will provide interest earnings on funds on deposit as they have historically.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('We believe that reasonable estimates for these figures are much more accurate than ignoring these economic realities. The things we can control are measurements, which we attempt to establish within 5% accuracy. The starting reserve balance and current reserve interest earnings are also numbers that can be identified with a high degree of certainty. These figures have been provided to us, and were not confirmed by our independent research. In addition, we have considered the strata corporation’s representation of current and historical Reserve projects reliable, and we have considered the representations made by its vendors and suppliers to also be accurate and reliable. Our projections assume a stable economic environment and lack of natural disasters.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Reserve fund expenditures may be varied to conform to actual management and maintenance plans, and therefore, they should not be dogmatically interpreted. In essence, reserve fund expenditures are the responsibility of the strata council and any targeted expenditure found herewith is a guideline.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Because both the physical status and financial status of the association change each year, this depreciation report is by nature a “one-year” document. This information can and should be adjusted annually as part of the depreciation report update process so that more accurate estimates can be reflected in the reserve fund plan. Reality often differs from even the best assumptions due to changing economic factors, physical factors, or ownership expectations. Because many years of financial preparation help the preparation for large expenses, this Report projects expenditures for the next 30 years. We fully expect a number of adjustments will be necessary through the interim years to both the cost and timing of distant expense projections. It is our recommendation that your depreciation report be updated annually.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Unless otherwise noted, all estimates are expressed in Canadian currency.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Strata Reserve Planning – Bramwell & Associates Realty Advisors Inc. and its employees have no ownership, management, or other business relationships with the client other than this depreciation report engagement.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Liability of Strata Reserve Planning – Bramwell & Associates Realty Advisors Inc. to your company or organization, you individually or affiliated parties for any claim related to professional services provided pursuant to this engagement, including the partners, officers, employees or contractors of Strata Reserve Planning – Bramwell & Associates Realty Advisors Inc. shall be strictly limited to the amount of any professional liability insurance the firm may have available at the time such claim is made.  Current liability insurance carried by the firm is $2,000,000 per claim (as an extension of the Professional Liability Program of the Appraisal Institute of Canada). No claim shall be brought against the firm, or its partners more than three years after the services were completed.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The Personal Information Protection Act (PIPA) of British Columbia sets out requirements for how organizations may collect, use, disclose and secure personal information. The preparation of this report and/or retention of records are subject to the requirements of PIPA, and restricts the use of this report to only the intended use and user(s) outlined within the report. Written authorization in advance must be requested for any proposed use in aggregated data model development, which must be done in conformity with PIPA and the Privacy Policy. For further information on the Act, contact the office of the Information & Privacy Commissioner for British Columbia, or access through the website:  http://www.oipc.bc.ca/.
'), 0, 'styleRegular','multilevelC','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('Bramwell & Associates Realty Advisors Inc. takes privacy very seriously. We collect personal information to better serve our customers, for security reasons, and to provide customers and potential customers with information about our services. We would like to have a lifelong relationship of good service with our clients, and for that reason we may retain personal information provided for as long as necessary to provide our services and respect our obligations to governmental agencies and other third parties. The information will remain confidential to Bramwell & Associates Realty Advisors Inc., to businesses working for us, and to any organization that acquires part or all of our business, provided that they agree to comply with our privacy policy. By accepting this report, you are agreeing to maintain the confidentiality and privacy of any personal information contained herein and to comply in all material respects with the contents of our Privacy Policy. As stated above, written authorization in advance must be requested for any proposed use in aggregated data model development, which must be done in conformity with PIPA and the Privacy Policy. If you wish to see a copy of our Privacy Policy, or have privacy questions or concerns, please contact the Privacy Officer by e-mail at: PIPA@VancouverAppraisal.Com.
'), 0, 'styleRegular','multilevelC','paragraphRegular');

   
   $this->section->addPageBreak();
}
    






function appendiceD() {
    $this->section = $this->PHPWord->createSection($this->sectionStyle);
    $this->section->addTitle('APPENDIX D – CERTIFICATION', 1);
    
    $this->section->addText(htmlspecialchars('I certify to the best of my knowledge and belief that:
'), 'styleRegular','paragraphRegular');    
    
    
//$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER, 'spaceAfter'=>60,'spaceBefore'=>60);


$this->PHPWord->addNumberingStyle(
    'multilevel',
    array('size'=>12,'type' => 'multilevel', 'levels' => array(
        array('format' => 'decimal', 'text' => '%1.', 'left' => 820, 'size'=>12,'hanging' => 360),
        array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        ), 'listType'=>PHPWord_Style_ListItem::TYPE_NUMBER, 'spaceAfter'=>60,'spaceBefore'=>60
     )
);


$this->section->addListItem(htmlspecialchars('The statements of fact contained in this report are true and correct,
'), 0, 'regular12','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('The reported analyses, opinions, and conclusions are limited only by the reported assumptions and limiting conditions, and are my personal impartial, and unbiased professional analyses, opinions, and conclusions,
'), 0, 'regular12','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('I have no present or prospective interest in the property that is the subject of this report, and no personal interest with respect to the parties involved,
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('My engagement in and compensation for this assignment were not contingent upon developing or reporting predetermined results, the amount of the value estimate, or a conclusion favouring the client,
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('My analyses, opinions, and conclusions in this depreciation report were developed in conformity with the Reserve Fund Study Standards, published by the Real Estate Institute of Canada, and in conformity with the Canadian Uniform Standards of Professional Appraisal Practice, and complies with the BC Strata Property Act 1998, and Regulations, as amended up to date,
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('I have the knowledge and experience to complete the assignment competently,
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('No one provided significant professional assistance to the person signing this report,
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('As of the date of this report the undersigned has fulfilled the requirements of The Appraisal Institute of Canada Mandatory Recertification Program for designated members and
'), 0, 'styleRegular','multilevel','paragraphRegular');  
$this->section->addListItem(htmlspecialchars('I made a personal inspection of the subject property on March 29, 2014 and personally examined the building plans and/or documents as identified herein.
'), 0, 'styleRegular','multilevel','paragraphRegular');  

$this->section->addTextBreak(1);

    $styleTable = array('cellMarginTop'=>0,'cellMarginBottom'=>0, 'align'=>'left','valign'=>'bottom');
    
    $table = $this->section->addTable($styleTable);

$table->addRow(0);
$table->addCell(1000);
$table->addCell(3000, $styleTable)->addText('June 20, 2014', 'regular11', array('valign'=>'bottom', "borderBottomSize" => 10, "borderColor" => "000000"));
$table->addCell(2000);
$cell2 = $table->addCell(3000, array('cellMarginLeft'=>300,'valign'=>'bottom', "borderBottomSize" => 10, "borderColor" => "000000"));
$cell2->addImage(TEMPLATE_IMG_PATH.'sign.jpg', array("width" => 250, "height" => 50, "align" => "center"));   
$table->addCell(1000);

$table->addRow(0);
$table->addCell(1000);
$table->addCell(3000)->addText('Date', 'regular11', '');
$table->addCell(2000);
$table->addCell(3000)->addText('Jeremy Bramwell, AACI, P.App. RI, CRP', 'regular11', '');
$table->addCell(1000);

}

//setters
function setPlan($plan) {    
    $this->arrPlan = $plan;
}

function setComponents($components) {   

    $this->arrComponent = $components;
}

function setAux($aux) {   

    $this->arrAux = $aux;
}


function setInspectionDates($inspectionDates) {   

    $this->arrInspectionDates = $inspectionDates;
}


    function __destruct() {
        
       print "descruct";
    }
}