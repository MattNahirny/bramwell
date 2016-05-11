<?php

/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 2016-05-03
 * Time: 11:15 AM
 */
class GenerateXlsx
{
    function __construct($conn, $planID)
    {
        require_once '../script/PHPExcel-1.8/Classes/PHPExcel.php';
        $this->conn = $conn;
        $this->planID = $planID;
        $this->PHPExcel = new PHPExcel();
        $this->tallyQuery = 'SELECT levelone.Name AS "l1Name", levelfour.Name AS "l4Name", plancomponent.YearAcquired, levelfour.ExpectedLifespan, plancomponent.NumUnits, plancomponent.UnitOfMeasure, levelfour.Cost  
    FROM plan 
    INNER JOIN plancomponent
    ON plan.PlanId = plancomponent.PlanId
    INNER JOIN levelfour
    ON plancomponent.LevelFourId = levelfour.LevelFourId
    INNER JOIN levelthree
    ON levelfour.LevelThreeId = levelthree.LevelThreeId
    INNER JOIN leveltwo
    ON levelthree.LevelTwoId = leveltwo.LevelTwoId
	INNER JOIN levelone
    ON leveltwo.LevelOneId = levelone.LevelOneId
    WHERE plan.PlanId = :planID';

    }

    function dummp($data) {
        echo '<pre>' . var_export($data, true) . '</pre>';exit;
    }

    function run()
    {
        $this->setup();
        $this->getTally();
        $this->save();
    }

    function setup()
    {
        //CREATE all sheets
        $this->TallySheet = $this->PHPExcel->createSheet();
        $this->TallySheet->setTitle("Tally");

        $this->SchedASheet = $this->PHPExcel->createSheet();
        $this->SchedASheet->setTitle("Schedule A");

        $this->SchedBSheet = $this->PHPExcel->createSheet();
        $this->SchedBSheet->setTitle("Schedule B");

        $this->SchedC1THSheet = $this->PHPExcel->createSheet();
        $this->SchedC1THSheet->setTitle("Schedule C.1 TH");

        $this->SchedC1CTFSheet = $this->PHPExcel->createSheet();
        $this->SchedC1CTFSheet->setTitle("Schedule C.1 CTF");

        $this->SchedC2FFSheet = $this->PHPExcel->createSheet();
        $this->SchedC2FFSheet->setTitle("Schedule C.2 FF");

        $this->SchedC2CFTSheet = $this->PHPExcel->createSheet();
        $this->SchedC2CFTSheet->setTitle("Schedule C.2 CTF");

        $this->SchedC3UNSheet = $this->PHPExcel->createSheet();
        $this->SchedC3UNSheet->setTitle("Schedule C.3 UN");

        $this->SchedC3CFTSheet = $this->PHPExcel->createSheet();
        $this->SchedC3CFTSheet->setTitle("Schedule C.3 CFT");

        $this->PHPExcel->removeSheetByIndex(0);
    }

    function getTally()
    {
        self::setupTally();

        $data = $this->conn->prepare($this->tallyQuery);
        $data->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
        $data->execute();

        while($r = $data->fetch(PDO::FETCH_ASSOC))
        {
            $dataRows[] = $r;
        }
        //$this->dummp($dataRows);
        $levelone = '';
        $currentExcelRow = '3';
        foreach ($dataRows as $index => $array) {
            if ($levelone != $array['l1Name']) {
                $levelone = $array['l1Name'];
                $this->TallySheet->SetCellValue('A' . $currentExcelRow, $levelone);
                $this->TallySheet->mergeCells('A'.$currentExcelRow . ':B' . $currentExcelRow);
                $currentExcelRow++;
            } else {
                $this->TallySheet->SetCellValue('B' . $currentExcelRow, $array['l4Name']);
                $this->TallySheet->SetCellValue('C' . $currentExcelRow, $array['YearAcquired']);
                $this->TallySheet->SetCellValue('E' . $currentExcelRow, $array['ExpectedLifespan']);
                $this->TallySheet->SetCellValue('W' . $currentExcelRow, $array['NumUnits']);
                $this->TallySheet->SetCellValue('X' . $currentExcelRow, $array['UnitOfMeasure']);
                $this->TallySheet->SetCellValue('Y' . $currentExcelRow, $array['Cost']);

                $currentExcelRow++;
            }
            $this->TallySheet->calculateColumnWidths();
        }

        //$this->dummp($dataRows);
        if (count($dataRows) < 1) {
            echo "0 data";
            exit;
        }
    }

    function setupTally()
    {
        //SETUP Tally Sheet style
        $this->TallySheet->getStyle('C2:AF2')->getAlignment()->setTextRotation(90);
        $this->TallySheet->getStyle('C2:AF2')->getAlignment()->setWrapText(true);
        $this->TallySheet->getStyle('C2:AF2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
        $this->TallySheet->getStyle('C2:AF2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->TallySheet->getStyle('B2')->applyFromArray(array('font' => array(
            'bold' => true,
            'name' => 'Calibri',
            'size' => 18)));
        $this->TallySheet->getStyle('C2:AF2')->applyFromArray(array('font' => array(
            'bold' => true,
            'name' => 'Calibri',
            'size' => 11)));

        //SETUP COLUMN WIDTHS
        $this->TallySheet->getColumnDimension("A")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("B")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("C")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("D")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("E")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("F")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("G")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("H")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("I")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("J")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("K")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("L")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("M")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("N")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("O")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("P")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("Q")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("R")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("S")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("T")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("U")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("V")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("W")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("X")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("Y")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("Z")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AA")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AB")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AC")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AD")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AE")->setAutoSize(true);
        $this->TallySheet->getColumnDimension("AF")->setAutoSize(true);

        //SETUP Tally Sheet Titles
        $this->TallySheet->SetCellValue('A2', '');
        $this->TallySheet->SetCellValue('B2', 'Reserve Components');
        $this->TallySheet->SetCellValue('C2', 'Year of Acquisition');
        $this->TallySheet->SetCellValue('D2', 'Work Last Conducted');
        $this->TallySheet->SetCellValue('E2', 'Expected Lifespan Repeat Every');
        $this->TallySheet->SetCellValue('F2', 'Observed Condition Current Age');
        $this->TallySheet->SetCellValue('G2', 'Remaining Lifespan');
        $this->TallySheet->SetCellValue('H2', 'First Instance of Work Pending');
        $this->TallySheet->SetCellValue('I2', 'First Repair');
        $this->TallySheet->SetCellValue('J2', 'Second Repair');
        $this->TallySheet->SetCellValue('K2', 'Third Repair');
        $this->TallySheet->SetCellValue('L2', 'Fourth Repair');
        $this->TallySheet->SetCellValue('M2', 'Fifth Repair');
        $this->TallySheet->SetCellValue('N2', 'First Replacement');
        $this->TallySheet->SetCellValue('O2', 'Second Replacement');
        $this->TallySheet->SetCellValue('P2', 'Third Replacement');
        $this->TallySheet->SetCellValue('Q2', 'Fourth Replacement');
        $this->TallySheet->SetCellValue('R2', 'Fifth Replacement');
        $this->TallySheet->SetCellValue('S2', 'Replacement Phased - A');
        $this->TallySheet->SetCellValue('T2', 'Replacement Phased - B');
        $this->TallySheet->SetCellValue('U2', 'Replacment Phased - C');
        $this->TallySheet->SetCellValue('V2', 'Replacement Phased - D');
        $this->TallySheet->SetCellValue('W2', 'Unit Quantity');
        $this->TallySheet->SetCellValue('X2', 'Unit Measure');
        $this->TallySheet->SetCellValue('Y2', 'Unit Cost');
        $this->TallySheet->SetCellValue('Z2', 'First instance as % of Major Repair or Replacement');
        $this->TallySheet->SetCellValue('AA2', 'Current Replacement Cost');
        $this->TallySheet->SetCellValue('AB2', 'Future Replacement Cost');
        $this->TallySheet->SetCellValue('AC2', 'Current Reserve Fund Requirements');
        $this->TallySheet->SetCellValue('AD2', 'Future Reserve Fund Accumulation');
        $this->TallySheet->SetCellValue('AE2', 'Future Reserve Fund Requirements');
        $this->TallySheet->SetCellValue('AF2', 'Annual Reserve Fund Requirements');

    }

    function getScheduleA()
    {

    }

    function getScheduleB()
    {

    }

    function getScheduleC1TH()
    {

    }

    function getScheduleC1TF()
    {

    }

    function getScheduleC2FF()
    {

    }

    function getScheduleC2CFT()
    {

    }

    function getScheduleC3UN()
    {

    }

    function getScheduleC3CFT()
    {

    }


    function save()
    {
        $fileName = ( 'Docs/testExcel.xlsx');
        $objWriter = new PHPExcel_Writer_Excel2007($this->PHPExcel);
        $objWriter->save($fileName);
        
        return $fileName;
    }
    
}