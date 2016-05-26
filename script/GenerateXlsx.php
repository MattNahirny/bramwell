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
        $this->allTallyData = array();
        $this->numComponents = 0;
        $this->allComponents = array();
        //QUERIES TO DB
        $this->basicInfoQuery = '';
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
        //ROWS OF IMPORTANT DATA CELLS
        $this->schedATotalsRow = 0;
        $this->totalExpendituresRow = 0;
        $this->RFClosingBalanceRow = 0;
        $this->RFRequirementseRow = 0;
        $this->reserveSurplusDeficiencyRow = 0;
        $this->reserveAdequacyRow = 0;
        $this->monthlyASLContributionsRow = 0;
        $this->annualASLContributionsRow = 0;
        $this->annualPossibleSpecialLeviesRow = 0;
        $this->totalASLContributionsSpecialLeviesRow = 0;
        $this->sceduleALevelOneRows = array();
        $this->largeTable1To15Start = 0;
        $this->largeTable16To30Start = 0;
        $this->smallTable16To30Start = 0;
        $this->smallTable1To15Start = 0;
        $this->largeTable1To15End = 0;
        $this->largeTable16To30End = 0;
        $this->smallTable16To30End = 0;
        $this->smallTable1To15End = 0;
        
        
        
        
        //REPORT ARRAY WITH ALL INFO TO GO TO DOCX
        $this->reportValues = array();

        // sched c1 th,   F15
        $this->reportValues['AnnualReserveFundContributions'][0] = array('year'=>2014, 'value'=>2400);
        // sched c1 th,   G15
        $this->reportValues['AnnualReserveFundContributions'][1] = array('year'=>2015, 'value'=>2640);
        // sched c1 th,   L19
        $this->reportValues['AnnualReserveFundContributions'][2] = array('year'=>2020, 'value'=>51961);
//pg 6
        // i think it on the loop to get the compoenents.. u have a cout for it if iremember.
        $this->reportValues['ReserveFundGroups'][0] = array('name'=>'Site Improvements Reserve Components', 'total'=>10);
        // can be any number of level1 total, name. COUNT for each level1 id
        $this->reportValues['ReserveFundGroups'][1] = array('name'=>'Consultant Report', 'total'=>1);
        // basic info,    C17
        $this->reportValues['Year1ReserveAdequacy']        = "37";
        // basic info,    C18
        $this->reportValues['Year30ReserveAdequacy']       = "60";
        // sched A,    J16
        $this->reportValues['CurrentReplacementCost']      = "162713";
        // sched A,    K16
        $this->reportValues['FutureReplacementCost']       = "501654";
        // sched A,    L16
        $this->reportValues['CurrentReserveFundCostReq']   = "63643";
        // sched A,    M16
        $this->reportValues['FutureReserveFundAcc']        = "95298";
        // sched A,    N16
        $this->reportValues['FutureReserveFundReq']        = "406356";
        // sched A,    O16
        $this->reportValues['ReserveFundAnnualCon']        = "10218";
//pg 16
        // sched c1 th,     F15
        $this->reportValues['ReserveFundClosingBalance'][0] = array('date'=>'2013-12-31', 'value'=>22027);
        // sched C1 CFT,    C4
        $this->reportValues['RecommendedAnnualRFContr'] = "1500";
        // sched C1 CFT,    F41
        $this->reportValues['ReserveAdequacy'] = "37";
        // ? C1 TH
        $this->reportValues['MonthlyASLContributions'][0] = array('year'=>'2014', 'value'=>20.00);
        // ?
        $this->reportValues['MonthlyASLContributions'][1] = array('year'=>'2019', 'value'=>32.21);
//pg65
        // ?
        $this->reportValues['MonthlyASLContributions'][2] = array('year'=>'2015', 'value'=>22.00);
//pg 60

        $this->reportValues['OpeningBalanceDate'] = "2014-01-01";

        $this->reportValues['OpeningBalanceValue'] = "22027";

        $this->reportValues['CurrentBudgetedAnnualRFC'] = "2400";

        $this->reportValues['AuthorizedSpecialLeveies'] = "0";

        $this->reportValues['Borrowings'] = "0";

        $this->reportValues['LoanRefinance'] = "0";

        $this->reportValues['ReserveFundTaxFreeAnnualIntIncome'] = "330";

        $this->reportValues['LessRepaymentOfFinancingLoan'] = "0";

        $this->reportValues['LessReserveFundBudgetCurrentFYear'] = "-1575";

        $this->reportValues['ProjectedReserveFundBalanceDate'] = "2014-12-31";

        $this->reportValues['ProjectedReserveFundBalanceValue'] = "23182";

        $this->reportValues['EstimatedReserveFund_Shortfall'] = "62068";

        $this->reportValues['BudgetTransferFromDate'] = "2015-01-01";

        $this->reportValues['BudgetTransferFromValue'] = "6000";

        $this->reportValues['ProposedSpecialLeveiesDate'] = "2015-01-01";

        $this->reportValues['ProposedSpecialLeveiesValue'] = "33468";

        $this->reportValues['EstimatedReserveFundAdequacy'] = "100";
//pg62

        $this->reportValues['EstimatedReserveFundDeficiency'] = "38885";

        $this->reportValues['ReserveAdequacyDate'] = "2014-12-31";

        $this->reportValues['ReserveAdequacyValue'] = "37";

        $this->BasicInfoSheet = $this->PHPExcel->createSheet();
        $this->TallySheet = $this->PHPExcel->createSheet();
        $this->ComparisonSheet = $this->PHPExcel->createSheet();
        $this->TenYearsSheet = $this->PHPExcel->createSheet();
        $this->SchedASheet = $this->PHPExcel->createSheet();
        $this->SchedBSheet = $this->PHPExcel->createSheet();
        $this->SchedC1THSheet = $this->PHPExcel->createSheet();
        $this->SchedC1CTFSheet = $this->PHPExcel->createSheet();
        $this->SchedC2FFSheet = $this->PHPExcel->createSheet();
        $this->SchedC2CFTSheet = $this->PHPExcel->createSheet();
        $this->SchedC3UNSheet = $this->PHPExcel->createSheet();
        $this->SchedC3CFTSheet = $this->PHPExcel->createSheet();
    }

    function dummp($data) {
        echo '<pre>' . var_export($data, true) . '</pre>';exit;
    }

    function cellColor($sheet,$cells,$color){
        $sheet->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    function getReportValues()
    {
        return $this->reportValues;
    }

    function run()
    {
        //SHEET GENERATION
        $this->setup();
        $this->getTally();
        $this->getScheduleA();
        $this->getScheduleC1TH();
        $this->getScheduleC1CTF();
        $this->getScheduleC2FF();
        $this->getScheduleC2CFT();
        $this->getScheduleC3UN();
        $this->getScheduleC3CFT();
        $this->getBasicInfo();
        $this->getComparison();
        //STYLES
        $this->styleBasicInfo();
        $this->styleScheduleA();
        $this->styleScheduleC1TH();
        $this->styleScheduleC2FF();
        $this->styleScheduleC3UN();


        $this->save();
    }

    function setup()
    {
        //CREATE all sheets
        $this->BasicInfoSheet->setTitle("Basic Info");
        $this->TallySheet->setTitle("Tally");
        $this->ComparisonSheet->setTitle("Comparison");
        $this->TenYearsSheet->setTitle("10 Years");
        $this->SchedASheet->setTitle("Sched. A");
        $this->SchedBSheet->setTitle("Sched. B");
        $this->SchedC1THSheet->setTitle("Sched. C.1 TH");
        $this->SchedC1CTFSheet->setTitle("Sched. C.1 CTF");
        $this->SchedC2FFSheet->setTitle("Sched. C.2 FF");
        $this->SchedC2CFTSheet->setTitle("Sched. C.2 CTF");
        $this->SchedC3UNSheet->setTitle("Sched. C.3 UN");
        $this->SchedC3CFTSheet->setTitle("Sched. C.3 CFT");
        $this->PHPExcel->removeSheetByIndex(0);
    }

    function getBasicInfo()
    {
        self::setupBasicInfo();
        //$data = $this->conn->prepare($this->BasicInfoQuery);
        //$data->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
        //$data->execute();
        //$dataRows = array();
        //while($r = $data->fetch(PDO::FETCH_ASSOC))
        //{
            $r['strata'] = 'Strata ID';
            $r['numLots'] = 20;
            $r['entUnits'] = 0;
            $r['amenities'] = 0;
            $r['yearAcquired'] = 1970;
            $r['currentYear'] = date('Y');
            $r['fiscalYearEnd'] = '28-feb';
            $r['THCurApprovedIncrease'] = 0;
            $r['THYr1PropIncrease'] = 0;
            $r['C1C2proposedContribution'] = '5%';
            $r['C3proposedContribution'] = '1%';
            $r['C1C2PropRFConrtibution'] = 0;
            $r['constructionInflation'] = '3%';
            $r['SchedBInterestRate'] = 0;
            $r['invenstmentInterest'] = '1%';
            $r['operatingBudget'] = '12345';

            $dataRows = $r;
            //$this->dummp($dataRows);
            //}

        $this->BasicInfoSheet->SetCellValue('C2', $dataRows['strata']);
        $this->BasicInfoSheet->SetCellValue('C3', $dataRows['numLots']);
        $this->BasicInfoSheet->SetCellValue('C4', $dataRows['entUnits']);
        $this->BasicInfoSheet->SetCellValue('C5', $dataRows['amenities']);
        $this->BasicInfoSheet->SetCellValue('C6', $dataRows['yearAcquired']);
        $this->BasicInfoSheet->SetCellValue('C7', $dataRows['currentYear']);
        $this->BasicInfoSheet->SetCellValue('C8', $dataRows['fiscalYearEnd']);
        $this->BasicInfoSheet->SetCellValue('C9', $dataRows['THCurApprovedIncrease']);
        $this->BasicInfoSheet->SetCellValue('C10', $dataRows['THYr1PropIncrease']);
        $this->BasicInfoSheet->SetCellValue('C11', $dataRows['C1C2proposedContribution']);
        $this->BasicInfoSheet->SetCellValue('C12', $dataRows['C3proposedContribution']);
        $this->BasicInfoSheet->SetCellValue('C13', $dataRows['C1C2PropRFConrtibution']);
        $this->BasicInfoSheet->SetCellValue('C14', $dataRows['constructionInflation']);
        $this->BasicInfoSheet->SetCellValue('C15', $dataRows['SchedBInterestRate']);
        $this->BasicInfoSheet->SetCellValue('C16', $dataRows['invenstmentInterest']);
        $this->BasicInfoSheet->SetCellValue('C17', $dataRows['operatingBudget']);
    }

    function styleBasicInfo()
    {
        $a1Style = array(
            'font' => array(
                'bold' => true,
                'size'  => 20,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'EBF1DE'),
            )
        );

        $outlineStyle1 = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $outlineStyle2 = array(
            'borders' => array(
                'horizontal' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->BasicInfoSheet->getStyle('A1:C1')->applyFromArray($a1Style);
        $this->BasicInfoSheet->mergeCells('A1:C1');
        $this->BasicInfoSheet->getColumnDimension("A")->setWidth(50);
        $this->BasicInfoSheet->getColumnDimension("B")->setWidth(12);
        $this->BasicInfoSheet->getColumnDimension("C")->setWidth(15);
        $this->BasicInfoSheet->getRowDimension(1)->setRowHeight(26.25);
        $this->cellColor($this->BasicInfoSheet, 'C2:C17', 'daeef3');

        $this->BasicInfoSheet->getStyle('C2:C17')->applyFromArray($outlineStyle1);
        $this->BasicInfoSheet->getStyle('A1:B18')->applyFromArray($outlineStyle2);

        for ($i = 2; $i < 18; $i++)
        {
            $this->BasicInfoSheet->getRowDimension($i)->setRowHeight(18);
            $this->BasicInfoSheet->mergeCells('A'.$i.':B'.$i);
            $this->BasicInfoSheet->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }

        //NUMBER FORMATS
        $this->BasicInfoSheet->getStyle('C8')->getNumberFormat()->setFormatCode('d-mmm');
        $this->BasicInfoSheet->getStyle('C9')->getNumberFormat()->setFormatCode('"$"#,##0');
        $this->BasicInfoSheet->getStyle('C13')->getNumberFormat()->setFormatCode('"$"#,##0');
        $this->BasicInfoSheet->getStyle('C17')->getNumberFormat()->setFormatCode('"$"#,##0');
        $this->BasicInfoSheet->getStyle('C10:C12')->getNumberFormat()->setFormatCode('0.00%');
        $this->BasicInfoSheet->getStyle('C14:C16')->getNumberFormat()->setFormatCode('0.00%');

    }

    function setupBasicInfo()
    {
        $columnA_1 = array('Strata:',
            'Number of Lots:',
            'Number of Entitlements units:',
            'Amenity/ies:',
            'Year of acquistion:',
            'Current year:',
            'Fiscal Year End:',
            'Threshold Current Year Approved Annual RF Contributions Increase:',
            'Threshold Year 1 Proposed Annual RF Contributions Increase:',
            'C.1 and C.2 Year 1 Proposed Contributions Increase Rate:',
            'C.3 Year 1 Proposed Contributions Increase Rate:',
            'C.1 and C.2 Year 1 Proposed RF Contributions Amount:',
            'Construction Inflation rate:',
            'Strata Corporation Sched. B Average Interest Rate:',
            'Investment Interest Rate:',
            'Operating Budget'
            );

        $this->BasicInfoSheet->SetCellValue('A1', 'Property Details');
        for($i = 2, $j = 0; $j < count($columnA_1); $i++, $j++)
        {
            $this->BasicInfoSheet->SetCellValue('A'.$i, $columnA_1[$j]);
        }
    }

    function getTally()
    {
        self::setupTally();

        $data = $this->conn->prepare($this->tallyQuery);
        $data->bindParam(':planID', $this->planID, PDO::PARAM_INT, 11);
        $data->execute();

        while($r = $data->fetch(PDO::FETCH_ASSOC))
        {
            //Not in DB yet
            $r['lastConducted'] = 0;
            //Not in DB yet
            $r['observedCondition'] = 0;
            $r['remainingLifeSpan'] = 0;
            $r['firstWorkPending'] = 0;
            //All repairs not in DB
            $r['firstRepair'] = 0;
            $r['secondRepair'] = 0;
            $r['thirdRepair'] = 0;
            $r['fourRepair'] = 0;
            $r['fifthRepair'] = 0;
            //All replacement not in DB
            $r['firstReplacement'] = $r['firstWorkPending'];
            $r['secondReplacement'] = $r['firstReplacement'] + $r['ExpectedLifespan'];
            $r['thirdReplacement'] = $r['secondReplacement'] + $r['ExpectedLifespan'];
            $r['fourthReplacement'] = $r['thirdReplacement'] + $r['ExpectedLifespan'];
            $r['fifthReplacement'] = $r['fourthReplacement'] + $r['ExpectedLifespan'];
            //Phased info not in DB
            $r['replacementPhaseA'] = 0;
            $r['replacementPhaseB'] = 0;
            $r['replacementPhaseC'] = 0;
            $r['replacementPhaseD'] = 0;
            //Not in DB yet
            $r['investmentInterestRate'] = 0;
            $r['percentOfRepair'] = '100%';
            $r['currentReplacementCost'] = ($r['NumUnits'] * $r['Cost']) * $r['percentOfRepair'];
            $r['futureReplacementCost'] = $r['currentReplacementCost'] * pow((1 + $r['investmentInterestRate']), $r['remainingLifeSpan']);
            $r['currentReserveFundRequirements'] = $r['currentReplacementCost'] * ($r['observedCondition'] / $r['ExpectedLifespan']);
            $r['futureReserveFundAccumulation'] = $r['currentReserveFundRequirements'] * pow((1 + $r['investmentInterestRate']), $r['remainingLifeSpan']);
            $r['futureReserveFundRequirements'] = $r['futureReplacementCost'] - $r['futureReserveFundAccumulation'];
            $r['annualReserveFundRequirements'] = $r['futureReserveFundRequirements'] * ($r['investmentInterestRate'] / pow((1 + $r['investmentInterestRate']), ($r['remainingLifeSpan'] - 1)));



            $dataRows[] = $r;
        }
        $this->allTallyData = $dataRows;
        //$this->dummp($dataRows);
        $levelone = '';
        $curRow = '3';
        foreach ($dataRows as $index => $array) {
            if ($levelone != $array['l1Name']) {
                $levelone = $array['l1Name'];
                $this->TallySheet->SetCellValue('A' . $curRow, $levelone);
                $this->TallySheet->mergeCells('A' . $curRow . ':B' . $curRow);
                $curRow++;
            } else {
                $this->TallySheet->SetCellValue('B' . $curRow, $array['l4Name']);
                $this->TallySheet->SetCellValue('C' . $curRow, '=\'Basic Info\'!$C$6');
                $this->TallySheet->SetCellValue('D' . $curRow, $array['lastConducted']);
                $this->TallySheet->SetCellValue('E' . $curRow, $array['ExpectedLifespan']);
                $this->TallySheet->SetCellValue('F' . $curRow, $array['observedCondition']);
                $this->TallySheet->SetCellValue('G' . $curRow, '=E'. $curRow . '-F'. $curRow);
                $this->TallySheet->SetCellValue('H' . $curRow, '=\'Basic Info\'!$C$7+Tally!G'. $curRow);
                $this->TallySheet->SetCellValue('I' . $curRow, $array['firstRepair']);
                $this->TallySheet->SetCellValue('J' . $curRow, $array['secondRepair']);
                $this->TallySheet->SetCellValue('K' . $curRow, $array['thirdRepair']);
                $this->TallySheet->SetCellValue('L' . $curRow, $array['fourRepair']);
                $this->TallySheet->SetCellValue('M' . $curRow, $array['fifthRepair']);
                $this->TallySheet->SetCellValue('N' . $curRow, '=H'. $curRow);
                $this->TallySheet->SetCellValue('O' . $curRow, $array['secondReplacement']);
                $this->TallySheet->SetCellValue('P' . $curRow, $array['thirdReplacement']);
                $this->TallySheet->SetCellValue('Q' . $curRow, $array['fourthReplacement']);
                $this->TallySheet->SetCellValue('R' . $curRow, $array['fifthReplacement']);
                $this->TallySheet->SetCellValue('S' . $curRow, $array['replacementPhaseA']);
                $this->TallySheet->SetCellValue('T' . $curRow, $array['replacementPhaseB']);
                $this->TallySheet->SetCellValue('U' . $curRow, $array['replacementPhaseC']);
                $this->TallySheet->SetCellValue('V' . $curRow, $array['replacementPhaseD']);
                $this->TallySheet->SetCellValue('W' . $curRow, $array['NumUnits']);
                $this->TallySheet->SetCellValue('X' . $curRow, $array['UnitOfMeasure']);
                $this->TallySheet->SetCellValue('Y' . $curRow, $array['Cost']);
                $this->TallySheet->SetCellValue('Z' . $curRow, $array['percentOfRepair']);
                $this->TallySheet->SetCellValue('AA' . $curRow, '=(Tally!$W'. $curRow .
                    '*Tally!$Y' . $curRow . ')*Z4');
                $this->TallySheet->SetCellValue('AB' . $curRow, '=AA'. $curRow .
                    '*(1+\'Basic Info\'!$C$14)^G'. $curRow);
                $this->TallySheet->SetCellValue('AC' . $curRow, '=AA'. $curRow .
                    '*F'. $curRow . '/E'. $curRow . '');
                $this->TallySheet->SetCellValue('AD' . $curRow, '=AC'. $curRow .
                    '*(1+\'Basic Info\'!$C$16)^G'. $curRow);
                $this->TallySheet->SetCellValue('AE' . $curRow, '=AB'. $curRow .
                    '-AD'. $curRow);
                $this->TallySheet->SetCellValue('AF' . $curRow, '=AE'. $curRow .
                    '*\'Basic Info\'!$C$16/((1+\'Basic Info\'!$C$16)^G'. $curRow . '-1)');
                $this->numComponents++;
                $curRow++;
                array_push($this->allComponents, $array['l4Name']);
            }
            $this->TallySheet->calculateColumnWidths();
        }

        //$this->dummp($this->allComponents);
        if (count($dataRows) < 1) {
            echo "0 data";
            exit;
        }
    }

    function setupTally()
    {
        $cellRange1 = range('A', 'Z');
        $cellRange2 = range('A', 'F');

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
        foreach($cellRange1 as $letter)
        {
            $this->TallySheet->getColumnDimension($letter)->setAutoSize(true);
        }
        foreach($cellRange2 as $letter)
        {
            $this->TallySheet->getColumnDimension('A'. $letter)->setAutoSize(true);
        }

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
        $this->TallySheet->SetCellValue('U2', 'Replacement Phased - C');
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

    function getComparison()
    {
        self::setupComparison();
    }

    function setupComparison()
    {
        //COMPARISON IS TOTALLY BROKEN
        $columnA_1 = array(
            'Operating Budget',
            'Year 1 Reserve Fund Requirement',
            'Year 1 Reserve Fund Surplus/Deficit',
            'Reserve Fund Opening Balance',
            'Column 1 – Total Expenditures over 30 years',
            'Column 2 – Total Reserve Fund Contributions over 30 Years',
            'Column 3 – Possible Special Levies over 30 years	',
            'Column 4 – Total Interest Income over 30 years',
            'Column 5 – Year 30 Closing Reserve Fund Balance',
            'Column 6 – Year 30 Closing Reserve Fund Cash Shortage',
            'Annual Borrowings over 30 years',
            'Total RF inflow over 30 years (Contributions, Special Levies, Interest)',
            'Contributions as a percentage of Total Inflow',
            'Interest earned as a percentage of Total Inflow',
            'Special Levies as a percentage of Total Inflow',
            'Borrowings as a percentage of Total Inflow',
            'Number of Special Levies over 30 years',
            'Total Cash Resources at the end of 30 years',
            'Year 30 Reserve Fund Requirements',
            'Year 1 Reserve Adequacy',
            'Year 30 Reserve Adequacy'
        );
        $columnC_1 = array(
            'Schedule C.1 –  THRESHOLD MODEL',
            'RECOMMENDED MODEL',
            '=\'Basic Info\'!$C$17',
            '=\'Sched. A\'!$L$' . $this->schedATotalsRow,
            '=\'Sched. C.1 TH\'!F' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.1 TH\'!F14',
            '=\'Sched. C.1 TH\'!E' . ($this->totalExpendituresRow - 1),
            '=\'Sched. C.1 TH\'!E15',
            '=\'Sched. C.1 TH\'!E16',
            '=\'Sched. C.1 TH\'!E18',
            '=\'Sched. C.1 TH\'!AI' . $this->RFClosingBalanceRow,
            '=\'Sched. C.1 TH\'!AI' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.1 TH\'!E17',
            '=C9+C11+C10',
            '=C9/C15',
            '=C11/C15',
            '=C10/C15',
            '=C14/C15',
            '=COUNTIF(\'Sched. C.1 TH\'!F16:AI16,">0")',
            '=\'Sched. C.1 TH\'!AI19',
            '=\'Sched. C.1 TH\'!AI' . $this->RFRequirementseRow,
            '=\'Sched. C.1 TH\'!F' . $this->reserveAdequacyRow,
            '=\'Sched. C.1 TH\'!AI' . $this->reserveAdequacyRow,

        );

        $columnD_1 = array(
            'Schedule C.2  –  FULLY FUNDED MODEL',
            'FULLY FUNDED MODEL',
            '=\'Basic Info\'!$C$17',
            '=\'Sched. A\'!$L$' . $this->schedATotalsRow,
            '=\'Sched. C.2 FF\'!F' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.2 FF\'!F14',
            '=\'Sched. C.2 FF\'!E' . ($this->totalExpendituresRow - 1),
            '=\'Sched. C.2 FF\'!E15',
            '=\'Sched. C.2 FF\'!E16',
            '=\'Sched. C.2 FF\'!E18',
            '=\'Sched. C.2 FF\'!AI' . $this->RFClosingBalanceRow,
            '=\'Sched. C.2 FF\'!AI' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.2 FF\'!E17',
            '=D9+D11+D10',
            '=D9/D15',
            '=D11/D15',
            '=D10/D15',
            '=D14/D15',
            '=COUNTIF(\'Sched. C.2 FF\'!F16:AI16,">0")',
            '=\'Sched. C.2 FF\'!AI19',
            '=\'Sched. C.2 FF\'!R' . $this->RFRequirementseRow,
            '=\'Sched. C.2 FF\'!F' . $this->reserveAdequacyRow,
            '=\'Sched. C.2 FF\'!AI' . $this->reserveAdequacyRow,
        );

        $columnE_1 = array(
            'Schedule C.3 –  UNFUNDED \'PAY AS YOU GO\' MODEL',
            'EXISTING MODEL',
            '=\'Basic Info\'!$C$17',
            '=\'Sched. A\'!$L$' . $this->schedATotalsRow,
            '=\'Sched. C.3 UN\'!F' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.3 UN\'!F14',
            '=\'Sched. C.3 UN\'!E' . ($this->totalExpendituresRow - 1),
            '=\'Sched. C.3 UN\'!E15',
            '=\'Sched. C.3 UN\'!E16',
            '=\'Sched. C.3 UN\'!E18',
            '=\'Sched. C.3 UN\'!AI' . $this->RFClosingBalanceRow,
            '=\'Sched. C.3 UN\'!AI' . $this->reserveSurplusDeficiencyRow,
            '=\'Sched. C.3 UN\'!E17',
            '=E9+E11+E10',
            '=E9/E15',
            '=E11/E15',
            '=E10/E15',
            '=E14/E15',
            '=COUNTIF(\'Sched. C.3 UN\'!F16:AI16,">0")',
            '=\'Sched. C.3 UN\'!AI19',
            '=\'Sched. C.3 UN\'!AI' . $this->RFRequirementseRow,
            '=\'Sched. C.3 UN\'!F' . $this->reserveAdequacyRow,
            '=\'Sched. C.3 UN\'!AI' . $this->reserveAdequacyRow
        );

        $this->ComparisonSheet->SetCellValue('A1', 'Scenario Result Comparisons');
        for ($i = 4, $j = 0; $i < 25; $i++, $j++)
        {
            $this->ComparisonSheet->SetCellValue('A'. $i, $columnA_1[$j]);
        }

        for ($i = 2, $j = 0; $i < 25; $i++, $j++)
        {
            $this->ComparisonSheet->SetCellValue('C'. $i, $columnC_1[$j]);
        }

        for ($i = 2, $j = 0; $i < 25; $i++, $j++)
        {
            $this->ComparisonSheet->SetCellValue('D'. $i, $columnD_1[$j]);
        }

        for ($i = 2, $j = 0; $i < 25; $i++, $j++)
        {
            $this->ComparisonSheet->SetCellValue('E'. $i, $columnE_1[$j]);
        }

    }

    function getTenYears()
    {
        self::setupTenYears();
    }

    function setupTenYears()
    {
        $this->TenYearsSheet->SetCellValue('A2', '');
    }

    function getScheduleA()
    {
        self::setupScheduleA();
        $levelone = '';
        $curRow = 3;
        $count = 1;
        foreach ($this->allTallyData as $index => $array) {
            if ($levelone != $array['l1Name']) {
                $levelone = $array['l1Name'];
                $this->SchedASheet->SetCellValue('A' . $curRow, $levelone);
                array_push($this->sceduleALevelOneRows, $curRow);
                $curRow++;
            } else {
                $this->SchedASheet->SetCellValue('A' . $curRow, $count);
                $this->SchedASheet->SetCellValue('B' . $curRow, '=Tally!B' . $curRow);
                $this->SchedASheet->SetCellValue('C' . $curRow, '=+Tally!C' . $curRow);
                $this->SchedASheet->SetCellValue('D' . $curRow, '=+Tally!E' . $curRow);
                $this->SchedASheet->SetCellValue('E' . $curRow, '=+Tally!F' . $curRow);
                $this->SchedASheet->SetCellValue('F' . $curRow, '=+Tally!G' . $curRow);
                $this->SchedASheet->SetCellValue('G' . $curRow, '=+Tally!W' . $curRow);
                $this->SchedASheet->SetCellValue('H' . $curRow, '=+Tally!X' . $curRow);
                $this->SchedASheet->SetCellValue('I' . $curRow, '=+Tally!Y' . $curRow);
                $this->SchedASheet->SetCellValue('J' . $curRow, '=+Tally!AA' . $curRow);
                $this->SchedASheet->SetCellValue('K' . $curRow, '=+Tally!AB' . $curRow);
                $this->SchedASheet->SetCellValue('L' . $curRow, '=+Tally!AC' . $curRow);
                $this->SchedASheet->SetCellValue('M' . $curRow, '=+Tally!AD' . $curRow);
                $this->SchedASheet->SetCellValue('N' . $curRow, '=+Tally!AE' . $curRow);
                $this->SchedASheet->SetCellValue('O' . $curRow, '=+Tally!AF' . $curRow);
                $this->SchedASheet->SetCellValue('Q' . $curRow, '=IF(H' . $curRow . '="allowance",O' . $curRow . ',)');
                $this->SchedASheet->SetCellValue('R' . $curRow, '=IF(H' . $curRow . '="allowance",P' . $curRow . ',)');

                $count++;
                $curRow++;
            }
        }
        $this->schedATotalsRow = $curRow;
        $this->SchedASheet->SetCellValue('A' . $curRow, 'TOTAL RESERVES');
        $this->SchedASheet->SetCellValue('J' . $curRow, '=SUM(J3:J' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('K' . $curRow, '=SUM(K3:K' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('L' . $curRow, '=SUM(L3:L' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('M' . $curRow, '=SUM(M3:M' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('N' . $curRow, '=SUM(N3:N' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('O' . $curRow, '=SUM(O3:O' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('P' . $curRow, '=SUM(P3:P' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('Q' . $curRow, '=SUM(Q3:Q' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('R' . $curRow, '=SUM(R3:R' . ($curRow - 1) . ')');

        $levelone = '';
        $curRow = 3;
        foreach ($this->allTallyData as $index => $array) {
            if ($levelone != $array['l1Name']) {
                $levelone = $array['l1Name'];
                $curRow++;
            } else {
                $this->SchedASheet->SetCellValue('P' . $curRow, '=O'  . $curRow . '/$O$' . $this->schedATotalsRow);
                $curRow++;
            }
        }
    }

    function setupScheduleA()
    {
        $cellRange = range('A', 'R');

        //SETUP Schedule A Sheet Titles
        $this->SchedASheet->SetCellValue('A2', 'RESERVE COMPONENTS');
        $this->SchedASheet->SetCellValue('B2', '');
        $this->SchedASheet->SetCellValue('C2', 'YEAR OF ACQUISITION');
        $this->SchedASheet->SetCellValue('D2', 'EXPECTED LIFESPAN');
        $this->SchedASheet->SetCellValue('E2', 'EFFECTIVE AGE');
        $this->SchedASheet->SetCellValue('F2', 'REMAINING LIFESPAN');
        $this->SchedASheet->SetCellValue('G2', 'UNIT QUANTITY');
        $this->SchedASheet->SetCellValue('H2', 'UNIT MEASURE');
        $this->SchedASheet->SetCellValue('I2', 'UNIT COST');
        $this->SchedASheet->SetCellValue('J2', 'CURRENT REPLACEMENT COST');
        $this->SchedASheet->SetCellValue('K2', 'FUTURE REPLACEMENT COST');
        $this->SchedASheet->SetCellValue('L2', 'CURRENT RESERVE FUND REQUIREMENT');
        $this->SchedASheet->SetCellValue('M2', 'FUTURE RESERVE FUND ACCUMULATION');
        $this->SchedASheet->SetCellValue('N2', 'FUTURE RESERVE FUND REQUIREMENT');
        $this->SchedASheet->SetCellValue('O2', 'RESERVE FUND ANNUAL CONTRIBUTIONS');
        $this->SchedASheet->SetCellValue('P2', 'RESERVE FUND ANNUAL CONTRIBUTIONS PERCENT ALLOCATION');
        $this->SchedASheet->SetCellValue('Q2', 'INTERNAL USE YEAR 1 ALLOWANCE ANNUAL CONTRIBUTIONS ');
        $this->SchedASheet->SetCellValue('R2', 'INTERNAL USE CURRENT REPLACEMENT ALLOWANCE PERCENTAGE');
    }

    function styleScheduleA()
    {

        //STYLE ARRAYS
        $allBorders = array(
            'borders' => array(
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array( 'rgb' => 'BFBFBF')
                ),
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),

            )
        );
        $a2Style = array(
            'font' => array(
                'bold' => true,
                'size'  => 18,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            )
        );
        $ctoR2Style = array(
            'font' => array(
                'bold' => true,
                'size'  => 11,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            )
        );
        $l1Style = array(
            'font' => array(
                'bold' => true,
                'size'  => 12,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
            )
        );
        $compACDEFStyle = array(
            'font' => array(
                'size'  => 11,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );
        $compBStyle = array(
            'font' => array(
                'size'  => 11,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
            )
        );
        $compGtoRStyle = array(
            'font' => array(
                'size'  => 11,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            )
        );

        $this->SchedASheet->getRowDimension(1)->setRowHeight(1);
        for($i = 4; $i < $this->schedATotalsRow; $i++)
        {
            $this->SchedASheet->getStyle('A'.$i)->applyFromArray($compACDEFStyle);
            $this->SchedASheet->getStyle('B'.$i)->applyFromArray($compBStyle);
            $this->SchedASheet->getStyle('C'.$i)->applyFromArray($compACDEFStyle);
            $this->SchedASheet->getStyle('D'.$i)->applyFromArray($compACDEFStyle);
            $this->SchedASheet->getStyle('E'.$i)->applyFromArray($compACDEFStyle);
            $this->SchedASheet->getStyle('F'.$i)->applyFromArray($compACDEFStyle);
            $this->SchedASheet->getStyle('G'.$i.':R'.$i)->applyFromArray($compGtoRStyle);

            $this->SchedASheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* \\\\(#,##0\\\\);_(* "-"??_);_(@_)');
            $this->SchedASheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('_(* #,##0_);_(* \\\\(#,##0\\\\);_(* "-"??_);_(@_)');
            $this->SchedASheet->getStyle('J'.$i.':O'.$i)->getNumberFormat()->setFormatCode('"$"#,##0');
            $this->SchedASheet->getStyle('P'.$i)->getNumberFormat()->setFormatCode('0.00%');
            $this->SchedASheet->getStyle('Q'.$i)->getNumberFormat()->setFormatCode('"$"#,##0');
            $this->SchedASheet->getStyle('R'.$i)->getNumberFormat()->setFormatCode('0.00%');

            $this->SchedASheet->getRowDimension($i)->setRowHeight(17);
        }
        $this->SchedASheet->mergeCells('A2:B2');
        $this->SchedASheet->getColumnDimension("A")->setWidth(5);
        $this->SchedASheet->getColumnDimension("B")->setWidth(45);

        $this->SchedASheet->getStyle('A2')->applyFromArray($a2Style);
        $this->SchedASheet->getStyle('C2:R2')->applyFromArray($ctoR2Style);

        foreach(range("C", "R") as $letter)
        {
            $this->SchedASheet->getColumnDimension($letter)->setWidth(18);
        }
        $this->SchedASheet->getRowDimension(1)->setRowHeight(0);
        $this->SchedASheet->getRowDimension(2)->setRowHeight(72);
        $this->cellColor($this->SchedASheet, 'A2:R2', 'DCE6F1');
        foreach($this->sceduleALevelOneRows as $row)
        {
            $this->SchedASheet->getRowDimension($row)->setRowHeight(20);
            $this->SchedASheet->getStyle('A'.$row)->applyFromArray($l1Style);
            $this->cellColor($this->SchedASheet, 'A'.$row.':R'.$row, 'EBF1DE');
            $this->SchedASheet->mergeCells('A'.$row.':B'.$row);
        }
        $this->cellColor($this->SchedASheet, 'A'.$this->schedATotalsRow.':R'.$this->schedATotalsRow, 'DCE6F1');
        $this->SchedASheet->mergeCells('A'.$this->schedATotalsRow.':B'.$this->schedATotalsRow);
        $this->SchedASheet->getStyle('J'.$this->schedATotalsRow.':O'.$this->schedATotalsRow)->getNumberFormat()->setFormatCode('"$"#,##0');
        $this->SchedASheet->getStyle('P'.$this->schedATotalsRow)->getNumberFormat()->setFormatCode('0.00%');
        $this->SchedASheet->getStyle('Q'.$this->schedATotalsRow)->getNumberFormat()->setFormatCode('"$"#,##0');
        $this->SchedASheet->getStyle('R'.$this->schedATotalsRow)->getNumberFormat()->setFormatCode('0.00%');
        $this->SchedASheet->getStyle('A2:R'.$this->schedATotalsRow)->applyFromArray($allBorders);
    }

    function getScheduleB()
    {
        self::setupScheduleB();
    }

    function setupScheduleB()
    {
        $this->SchedBSheet->SetCellValue('A2', '');
    }

    function getScheduleC1TH()
    {
        self::setupScheduleC1TH();
    }

    function setupScheduleC1TH()
    {
        $cursor = 0;
        //setup Titles starting at row 12
        $this->SchedC1THSheet->SetCellValue('C12', 'First Occurrence');
        $this->SchedC1THSheet->SetCellValue('D12', 'Repeat Every');
        $this->SchedC1THSheet->SetCellValue('E12', 'Totals at Year 30 without Allowances');
        $cellRange1 = range('G', 'Z');
        $cellRange2 = range('B', 'I');
        $curLetter = 'F';
        $this->SchedC1THSheet->SetCellValue('F12', '=\'Basic Info\'!C7+1');

        foreach($cellRange1 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue($letter . 12, '=' . $curLetter . '12 + 1');

            $curLetter = $letter;
        }
        $this->SchedC1THSheet->SetCellValue('AA12', '=Z12 + 1');
        $curLetter = 'A';
        foreach($cellRange2 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 12, '=A' . $curLetter . '12 + 1');
            $curLetter = $letter;
        }

        //setup titles A14-A19
        $titles = array(
            'Reserve Fund Opening Balance',
            'Annual Reserve Fund Contributions',
            'Possible Special Levies',
            'Possible Borrowings',
            'Annual Reserve Fund Interest Income',
            'Total Cash Resources'
        );
        for ($i = 0; $i < 6; $i++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . ($i + 14), $titles[$i]);
        }
        $this->SchedC1THSheet->SetCellValue('A21', 'Reserve Expenditures');
        //list components by L4Name
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . (22 + $i), ($i + 1));
            $this->SchedC1THSheet->SetCellValue('B' . (22 + $i), $this->allComponents[$i]);
            $cursor = 22 + $i;

        }
        $lastComponentRow = $cursor;
        $cursor += 2;
        //the totals
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Total Expenditures');
        $this->totalExpendituresRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Fund Closing Balance');
        $this->RFClosingBalanceRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Fund Requirements');
        $this->RFRequirementseRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Surplus (Deficiency)');
        $this->reserveSurplusDeficiencyRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Adequacy');
        $this->reserveAdequacyRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Monthly ASL Contributions');
        $this->monthlyASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Annual ASL Contributions');
        $this->annualASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Annual ASL Possible Special Levies');
        $this->annualPossibleSpecialLeviesRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Total Annual ASL Contributions and Special Levies');
        $this->totalASLContributionsSpecialLeviesRow = $cursor;
//From E14 to AI19
        //COLUMN F
        $this->SchedC1THSheet->SetCellValue('E15', '=SUM(F15:AI15)');
        $this->SchedC1THSheet->SetCellValue('E16', '=SUM(F16:AI16)');
        $this->SchedC1THSheet->SetCellValue('E17', '=SUM(F17:AI17)');
        $this->SchedC1THSheet->SetCellValue('E18', '=SUM(F18:AI18)');

        $this->SchedC1THSheet->SetCellValue('F14', '36933'); //THESE HAVE TO BE ENTERED BY USER
        $this->SchedC1THSheet->SetCellValue('F15', '=\'Basic Info\'!$C$9'); // THESE HAVE TO BE ENTERED BY USER OR MOST LIKELY PUT INTO BASICINFO
        $this->SchedC1THSheet->SetCellValue('F18', '=+F14*\'Basic Info\'!$C$16');
        $this->SchedC1THSheet->SetCellValue('F19', '=+F14+F15+F16+F18');
        $curLetter = 'F';
        //COLUMN G-Z
        foreach($cellRange1 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue($letter . 14, '=+' . $curLetter . $this->RFClosingBalanceRow);
            //THERE IS A MAXIMUM ANNUAL RESERVE FUND CONTRIBUTION AMOUNT THAT MUST BE SET
            $this->SchedC1THSheet->SetCellValue($letter . 15, '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15'); // ANNUAL RESERVE FUND CONTRIBUTIONS MUST BE HERE
            $this->SchedC1THSheet->SetCellValue($letter . 18, '=+' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC1THSheet->SetCellValue($letter . 19, '=+' . $letter . '14+' . $letter . '15+' . $letter . '16+' . $letter . '18');

            $curLetter = $letter;
        }
        //COLUMN AA
        $this->SchedC1THSheet->SetCellValue('AA14', '=+Z' . $this->RFClosingBalanceRow);
        $this->SchedC1THSheet->SetCellValue('AA15', '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15');
        $this->SchedC1THSheet->SetCellValue('AA18', '=+AA14*\'Basic Info\'!$C$16');
        $this->SchedC1THSheet->SetCellValue('AA19', '=+AA14+AA15+AA16+AA18');
        $curLetter = 'A';
        //COLUMN AB-AI
        foreach($cellRange2 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 14, '=+A' . $curLetter . $this->RFClosingBalanceRow);
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 15, '=(100%+\'Basic Info\'!$C$11)*A' . $curLetter . '15');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 18, '=+A' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 19, '=+A' . $letter . '14+A' . $letter . '15+A' . $letter . '16+A' . $letter . '18');

            $curLetter = $letter;
        }
        $cellRange3 = range('F', 'Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($cellRange3, 'A' . $letter);
        }
        //CALCULATE TOTALS FOR EACH COMPONENT
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC1THSheet->SetCellValue('C' . (22 + $i), '=\'Basic Info\'!$C$7+\'Sched. A\'!F'. ($i + 4));
            $this->SchedC1THSheet->SetCellValue('D' . (22 + $i), '=\'Sched. A\'!D'. ($i + 4));
            $this->SchedC1THSheet->SetCellValue('E' . (22 + $i), '=SUM(F' . (22 + $i). ':AI' . (22 + $i) . ')');
            $replaceEvery = $this->SchedC1THSheet->getCell('D' . (22 + $i))->getCalculatedValue();
            $replaced = false;
            $yearsAfter = 0;
            $replaceLetter = '';
            foreach($cellRange3 as $letter)
            {

                if (!$replaced)
                {
                    $this->SchedC1THSheet->SetCellValue($letter . (22 + $i), '=IF(' . $letter . '$12=$C' . (22 + $i). ',\'Sched. A\'!$K4,0)');
                    if ($this->SchedC1THSheet->getCell($letter . '12')->getCalculatedValue() ==
                    $this->SchedC1THSheet->getCell('C' . (22 + $i))->getCalculatedValue())
                    {
                        $replaced = true;
                        $replaceLetter = $letter;
                    }
                }
                else
                {
                    $yearsAfter++;
                    if ($yearsAfter % $replaceEvery == 0)
                    {
                        $this->SchedC1THSheet->SetCellValue($letter . (22 + $i), '=' . $replaceLetter . (22 + $i) . '*(1+\'Basic Info\'!C14)^' . $replaceEvery);
                        $replaceLetter = $letter;
                    }
                    else
                    {
                        $this->SchedC1THSheet->SetCellValue($letter . (22 + $i), 0);
                    }
                }
            }
        }
        $this->SchedC1THSheet->SetCellValue('E' . ($this->totalExpendituresRow - 1), '=SUM(E22:E' . $lastComponentRow . ')');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->totalExpendituresRow, '=SUM('. $letter . 22 . ':' . $letter . $lastComponentRow . ')');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->RFClosingBalanceRow, '=+' . $letter . '19-' . $letter . $this->totalExpendituresRow);
        }
        //L59 needs to be set up properly for variable amounts of components
        $this->SchedC1THSheet->SetCellValue( 'F' . $this->RFRequirementseRow, '=\'Sched. A\'!L' . $this->schedATotalsRow . '-F' . $this->totalExpendituresRow);
        $lastCol = 'F';
        foreach($cellRange3 as $letter)
        {
            if ($letter == 'F')
                continue;
            $this->SchedC1THSheet->SetCellValue( $letter . $this->RFRequirementseRow, '=(+' . $lastCol . $this->RFRequirementseRow . '*(1+\'Basic Info\'!$C$16))+\'Sched. A\'!$O$' . $this->schedATotalsRow . '-' . $letter . $this->totalExpendituresRow);
            $lastCol = $letter;
        }

        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->reserveSurplusDeficiencyRow, '=+' . $letter . $this->RFClosingBalanceRow . '-' . $letter . $this->RFRequirementseRow);
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->reserveAdequacyRow, '=' . $letter . $this->RFClosingBalanceRow . '/' . $letter . $this->RFRequirementseRow);
        }
        $this->SchedC1THSheet->SetCellValue( $letter . $this->monthlyASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3/12');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->annualASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->annualPossibleSpecialLeviesRow, '=' . $letter . '16/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $this->totalASLContributionsSpecialLeviesRow, '=(' . $letter . '15+' . $letter . '16)/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . 11, '='.$letter . $this->reserveAdequacyRow);
        }
        
        
        
        
        //STYLED LARGE TABLE YEARS 1-15
        $this->largeTable1To15Start = $this->totalASLContributionsSpecialLeviesRow + 2;
        $cursor = $this->totalASLContributionsSpecialLeviesRow + 2;
        //TOP ROW WITH TITLES
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC1THSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC1THSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC1THSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        $largeTableArray1 = array(
        'Construction Inflation Rate:',
        'Investments Interest Rate:',
        'Fiscal Year End:',
        'RESERVE FUND OPENING BALANCE',
        'Annual Reserve Fund Contributions',
        'Possible Special Levies',
        'Possible Borrowings',
        'Annual Reserve Fund Interest Income',
        'Total Cash Resources',
        'RESERVE FUND EXPENDITURES');
        $componentsRowStart = 0;
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        $columnC = array(
            '=\'Basic Info\'!C14',
            '=\'Basic Info\'!$C$16',
            '=\'Basic Info\'!C8',
            'Current Reserve Fund Requirements',
            '',
            '',
            'Current Reserve Fund Requirements'
        );
        for ($i = ($this->largeTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        $nonComponent1to15 = array(
            12, 14, 15, 16, 17, 18, 19
        );
        $largeTable15YearsRange = range('D', 'R');

        for($i = ($this->largeTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        $schedAComponentRows = 4;
        $afterComponents = 0;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {

            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC1THSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }

        //YEARS 1-15 AFTER COMPONENTS
        $afterComponentsArray = array(
            'TOTAL RESERVE FUND EXPENDITURES',
            'Reserve Fund Closing Balance',
            'Reserve Fund Requirements',
            'Reserve Surplus (Deficiency)',
            'Reserve Adequacy',
            'Monthly ASL Contributions',
            'Annual ASL Contributions',
            'Annual ASL Possible Special Levies',
            'Total Annual ASL Contributions and Special Levies',
        );
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->largeTable1To15End = $i;
            $this->largeTable16To30Start = ($i + 2);
        }
        $this->SchedC1THSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION

        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }
        
        
        
        
        //STYLED LARGE TABLE YEARS 16-30
        $cursor = $this->largeTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC1THSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC1THSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC1THSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->largeTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->largeTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC1THSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                else
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->largeTable16To30End = $i;
            $this->smallTable1To15Start = ($i + 2);
        }
        $this->SchedC1THSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }

        
        
        
        //STYLED SMALL TABLE YEARS 1-15
        $cursor = $this->smallTable1To15Start;
        //TOP ROW WITH TITLES
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC1THSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC1THSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC1THSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        for ($i = ($this->smallTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        for($i = ($this->smallTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $schedAComponentRows = 4;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC1THSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }
        //YEARS 1-15 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable1To15End = $i;
            $this->smallTable16To30Start = ($i + 2);
        }
        $this->SchedC1THSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }

        
        
        
        //STYLED SMALL TABLE YEARS 16-30
        $cursor = $this->smallTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC1THSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC1THSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC1THSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->smallTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->smallTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC1THSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC1THSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable16To30End = $i;
        }
        $this->SchedC1THSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC1THSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }



        //CHART SETUP
        $dataSetLabel = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Sched. C.1 TH$A$' . $this->totalExpendituresRow, NULL, 1)
            //new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$E$1', NULL, 1),
        );

        $xAxis = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Sched. C.1 TH$F$12:$AI$12', NULL, 30),
        );

        $dataSetValues = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Sched. C.1 TH$F$' . $this->totalExpendituresRow . ':$AI$' . $this->totalExpendituresRow, NULL, 30)
            //new \PHPExcel_Chart_DataSeriesValues('Number', 'Sched. C.1 TH!$E$2:$E$91', NULL, 90),
        );

        $dataSet = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART,
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            range(0, count($dataSetValues)-1),
            $dataSetLabel,
            $xAxis,
            $dataSetValues
        );
        $title = new PHPExcel_Chart_Title('Sched. C.1 TH');
        $plotArea = new PHPExcel_Chart_PlotArea(NULL, array($dataSet));
        $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);
        $chart = new PHPExcel_Chart(
            'chart1',
            $title,
            $legend,
            $plotArea,
            true,
            0,
            NULL,
            NULL
        );
        $chart->setTopLeftPosition('A1');
        $chart->setBottomRightPosition('I11');
        $this->SchedC1CTFSheet->addChart($chart);

    }

    function styleScheduleC1TH()
    {
                $allBorders = array(
            'borders' => array(
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array( 'rgb' => 'BFBFBF')
                ),
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
            )
        );

        //OUTLINE
        $this->SchedC1THSheet->getStyle('A12:AI'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray($allBorders);
        $this->SchedC1THSheet->getStyle('A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15End)->applyFromArray($allBorders);
        $this->SchedC1THSheet->getStyle('A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30End)->applyFromArray($allBorders);
        $this->SchedC1THSheet->getStyle('A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15End)->applyFromArray($allBorders);
        $this->SchedC1THSheet->getStyle('A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30End)->applyFromArray($allBorders);

        //WIDTHS
        $r = range("I", "Z");
        foreach(range("A", "I") as $i)
        {
            array_push($r, "A".$i);
        }
        $this->SchedC1THSheet->getColumnDimension("A")->setWidth(5);
        $this->SchedC1THSheet->getColumnDimension("B")->setWidth(45);
        foreach($r as $i)
        {
            $this->SchedC1THSheet->getColumnDimension($i)->setWidth(10);
        }
        foreach(range('C', 'H') as $i)
        {
            $this->SchedC1THSheet->getColumnDimension($i)->setWidth(13);
        }

        //HEIGHTS
        foreach(range(1, 10) as $i)
        {
            $this->SchedC1THSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC1THSheet->getRowDimension(12)->setRowHeight(60);
        $this->SchedC1THSheet->getRowDimension($this->largeTable1To15Start)->setRowHeight(20);
        $this->SchedC1THSheet->getRowDimension($this->largeTable16To30Start)->setRowHeight(20);

        $this->SchedC1THSheet->getRowDimension($this->smallTable1To15Start)->setRowHeight(20);
        foreach(range(($this->smallTable1To15Start+11), ($this->smallTable1To15Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC1THSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC1THSheet->getRowDimension($this->smallTable16To30Start)->setRowHeight(20);
        foreach(range(($this->smallTable16To30Start+11), ($this->smallTable16To30Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC1THSheet->getRowDimension($i)->setRowHeight(0);
        }

        //COLOURING
        $this->cellColor($this->SchedC1THSheet, 'F15:AI16', 'DAEEF3');
        $this->cellColor($this->SchedC1THSheet, 'A'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow, 'EBF1DE');

        $this->cellColor($this->SchedC1THSheet, 'A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC1THSheet, 'A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4), 'DCE6F1');

        $this->cellColor($this->SchedC1THSheet, 'A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC1THSheet, 'A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC1THSheet, 'A'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4), 'DCE6F1');



        //FONT
        $this->SchedC1THSheet->getStyle('C12:E12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'bold' => true,
                'size' => 10
            )
        ));
        $this->SchedC1THSheet->getStyle('F12:AI12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('A14')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('A15:A19')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('A21')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('A22:B'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('C22:D'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('E22:E'.($this->numComponents+22))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.$this->totalExpendituresRow.':E'.$this->reserveAdequacyRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('F14:E'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC1THSheet->getStyle('A'.$this->largeTable1To15Start.':D'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.$this->largeTable16To30Start.':D'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.$this->smallTable1To15Start.':D'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.$this->smallTable16To30Start.':D'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('R'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC1THSheet->getStyle('R'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC1THSheet->getStyle('R'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC1THSheet->getStyle('R'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable1To15Start+1).':C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->largeTable16To30Start+1).':C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable1To15Start+1).':C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable16To30Start+1).':C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15Start+3).':R'.($this->largeTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30Start+3).':R'.($this->largeTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15Start+3).':R'.($this->smallTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30Start+3).':R'.($this->smallTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->largeTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('A'.($this->largeTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->largeTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->smallTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->smallTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('A'.($this->largeTable1To15End-8).':A'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->largeTable16To30End-8).':A'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->smallTable1To15End-8).':A'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('A'.($this->smallTable16To30End-8).':A'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        //MERGE
        $this->SchedC1THSheet->mergeCells('C'.($this->largeTable1To15Start+4).':C'.($this->largeTable1To15Start+5));
        $this->SchedC1THSheet->mergeCells('C'.($this->largeTable16To30Start+4).':C'.($this->largeTable16To30Start+5));
        $this->SchedC1THSheet->mergeCells('C'.($this->smallTable1To15Start+4).':C'.($this->smallTable1To15Start+5));
        $this->SchedC1THSheet->mergeCells('C'.($this->smallTable16To30Start+4).':C'.($this->smallTable16To30Start+5));

        $this->SchedC1THSheet->mergeCells('C'.($this->largeTable1To15Start+7).':C'.($this->largeTable1To15Start+9));
        $this->SchedC1THSheet->mergeCells('C'.($this->largeTable16To30Start+7).':C'.($this->largeTable16To30Start+9));
        $this->SchedC1THSheet->mergeCells('C'.($this->smallTable1To15Start+7).':C'.($this->smallTable1To15Start+9));
        $this->SchedC1THSheet->mergeCells('C'.($this->smallTable16To30Start+7).':C'.($this->smallTable16To30Start+9));

        $this->SchedC1THSheet->mergeCells('A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10));
        $this->SchedC1THSheet->mergeCells('A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10));
        $this->SchedC1THSheet->mergeCells('A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10));
        $this->SchedC1THSheet->mergeCells('A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10));

        //NUMBER FORMATS
        //$objReader = PHPExcel_IOFactory::createReader('Excel2007');
        //$objPHPExcel = $objReader->load('Docs/template.xlsx');
        //$this->dummp($objPHPExcel->getSheet(6)->getStyle('F142')->getNumberFormat()->getFormatCode());

        $this->SchedC1THSheet->getStyle('F11:AI11')->getNumberFormat()->setFormatCode('0%');
        $this->SchedC1THSheet->getStyle('F14:AI14')->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('E15:AI'.$this->totalASLContributionsSpecialLeviesRow)->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC1THSheet->getStyle('E'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow)->getNumberFormat()->setFormatCode('0%');

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15Start+4).':R'.($this->largeTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15Start+11).':R'.($this->largeTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15End-3).':R'.($this->largeTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC1THSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30Start+4).':R'.($this->largeTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30Start+11).':R'.($this->largeTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30End-3).':R'.($this->largeTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC1THSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15Start+4).':R'.($this->smallTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15Start+11).':R'.($this->smallTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15End-3).':R'.($this->smallTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC1THSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30Start+4).':R'.($this->smallTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30Start+11).':R'.($this->smallTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30End-3).':R'.($this->smallTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC1THSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->getNumberFormat()->setFormatCode('0%');


    }

    function getScheduleC1CTF()
    {
        self::setupScheduleC1CTF();

    }

    function setupScheduleC1CTF()
    {
        //TITLES OF TABLE 1
        $this->SchedC1CTFSheet->SetCellValue( 'A' . 1 , 'Strata:');
        $this->SchedC1CTFSheet->SetCellValue( 'C' . 1, '=\'Basic Info\'!$C$2');
        $this->SchedC1CTFSheet->SetCellValue( 'D' . 1, '  30 YEAR RESERVE FUND CASH FLOW TABLE');
        $this->SchedC1CTFSheet->SetCellValue( 'A' . 2, 'Fiscal Year End');
        $this->SchedC1CTFSheet->SetCellValue( 'A' . 3, '=\'Basic Info\'!C8');
        $this->SchedC1CTFSheet->SetCellValue( 'A' . 34, 'Totals over 30 years');
        $this->SchedC1CTFSheet->SetCellValue( 'A' . 35, 'Average per year over 30 years');
        $titlesArray = array(
            'Reserve Fund Opening Balance',
            'Recommended Annual RF Contributions',
            'Percentage Increase in RF Contributions',
            'Possible Special Levies',
            'Borrowings and Loan Financing',
            'Estimated Reserve Fund Interest Earned',
            'Estimated Inflation Adjusted Expenditures',
            'Reserve Fund Closing Balance',
            'Total Annual ASL Contributions and Special Levies',
            'Monthly ASL Contributions',
            'Annual ASL Possible Special Levies',
        );
        $i = 0;
        foreach( range('B', 'L') as $letter)
        {
            $this->SchedC1CTFSheet->SetCellValue( $letter . 2, $titlesArray[$i]);
            $i++;
        }

        //The INNER PORTION OF TABLE 1
        $columnsRange = range('A', 'G');
        $yearsRange = range('F','Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($yearsRange, 'A' . $letter);
        }
        $aids = array(12, 14, 15, 16, 17, 18);
        for ($i = 0; $i < 30; $i++)
        {
            if ($i != 0)
            {
                $this->SchedC1CTFSheet->SetCellValue('D' . ($i+4),'=(C' . ($i+4) . '/C' . ($i+3) . ')-100%');
            } else {
                $this->SchedC1CTFSheet->SetCellValue('D' . ($i+4),'Not Calculated');
            }
            $j = 0;
            foreach($columnsRange as $letter)
            {
                if($letter == 'D')
                    continue;
                $this->SchedC1CTFSheet->SetCellValue( $letter . ($i+4), '=\'Sched. C.1 TH\'!$' . $yearsRange[$i] . '$' .  $aids[$j]);
                $j++;
            }
            $this->SchedC1CTFSheet->SetCellValue( 'H' . ($i+4), '=\'Sched. C.1 TH\'!$' . $yearsRange[$i] . '$' . $this->totalExpendituresRow);
            $this->SchedC1CTFSheet->SetCellValue( 'I' . ($i+4), '=\'Sched. C.1 TH\'!$' . $yearsRange[$i] . '$' . ($this->totalExpendituresRow + 1));
            $this->SchedC1CTFSheet->SetCellValue( 'J' . ($i+4),'=(C' . ($i+4) . '+E' . ($i+4) .')/\'Basic Info\'!$C$3');
            $this->SchedC1CTFSheet->SetCellValue( 'K' . ($i+4),'=C' . ($i+4) . '/\'Basic Info\'!$C$3/12');
            $this->SchedC1CTFSheet->SetCellValue( 'L' . ($i+4),'=E' . ($i+4) . '/\'Basic Info\'!$C$3');
        }
        //SUMS AT BOTTOM
        $this->SchedC1CTFSheet->SetCellValue( 'C' . 34, '=SUM(C4:C33)');
        $this->SchedC1CTFSheet->SetCellValue( 'E' . 34, '=SUM(E4:E33)');
        $this->SchedC1CTFSheet->SetCellValue( 'G' . 34, '=SUM(G4:G33)');

        $this->SchedC1CTFSheet->SetCellValue( 'H' . 34, '=SUM(H4:H33)');
        $this->SchedC1CTFSheet->SetCellValue( 'J' . 34, '=SUM(J4:J33)');
        $this->SchedC1CTFSheet->SetCellValue( 'L' . 34, '=SUM(L4:L33)');

        $this->SchedC1CTFSheet->SetCellValue( 'J' . 35, '=AVERAGE(J4:J33)');
        $this->SchedC1CTFSheet->SetCellValue( 'K' . 35, '=AVERAGE(K4:K33)');
        $this->SchedC1CTFSheet->SetCellValue( 'L' . 35, '=AVERAGE(L4:L33)');

    }

    function getScheduleC2FF()
    {
        self::setupScheduleC2FF();
    }

    function setupScheduleC2FF()
    {
        //setup Titles starting at row 12
        $this->SchedC2FFSheet->SetCellValue('C12', 'First Occurrence');
        $this->SchedC2FFSheet->SetCellValue('D12', 'Repeat Every');
        $this->SchedC2FFSheet->SetCellValue('E12', 'Totals at Year 30 without Allowances');
        $cellRange1 = range('G', 'Z');
        $cellRange2 = range('B', 'I');
        $curLetter = 'F';
        $this->SchedC2FFSheet->SetCellValue('F12', '=\'Basic Info\'!C7+1');

        foreach($cellRange1 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue($letter . 12, '=' . $curLetter . '12 + 1');

            $curLetter = $letter;
        }
        $this->SchedC2FFSheet->SetCellValue('AA12', '=Z12 + 1');
        $curLetter = 'A';
        foreach($cellRange2 as $letter)
        {
            //echo 'A' . $letter . 12, '=A' . $curLetter . '12 + 1';exit;
            $this->SchedC2FFSheet->SetCellValue('A' . $letter . 12, '=A' . $curLetter . '12 + 1');
            $curLetter = $letter;
        }

        //setup titles A14-A19
        $titles = array(
            'Reserve Fund Opening Balance',
            'Annual Reserve Fund Contributions',
            'Possible Special Levies',
            'Possible Borrowings',
            'Annual Reserve Fund Interest Income',
            'Total Cash Resources'
        );
        for ($i = 0; $i < 6; $i++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . ($i + 14), $titles[$i]);
        }
        $this->SchedC2FFSheet->SetCellValue('A21', 'Reserve Expenditures');
        //list components by L4Name
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . (22 + $i), ($i + 1));
            $this->SchedC2FFSheet->SetCellValue('B' . (22 + $i), $this->allComponents[$i]);
            $cursor = 22 + $i;

        }
        $lastComponentRow = $cursor;
        $cursor += 2;
        //the totals
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Total Expenditures');
        $this->totalExpendituresRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Reserve Fund Closing Balance');
        $this->RFClosingBalanceRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Reserve Fund Requirements');
        $this->RFRequirementseRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Reserve Surplus (Deficiency)');
        $this->reserveSurplusDeficiencyRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Reserve Adequacy');
        $this->reserveAdequacyRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Monthly ASL Contributions');
        $this->monthlyASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Annual ASL Contributions');
        $this->annualASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Annual ASL Possible Special Levies');
        $this->annualPossibleSpecialLeviesRow = $cursor;
        $cursor++;
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Total Annual ASL Contributions and Special Levies');
        $this->totalASLContributionsSpecialLeviesRow = $cursor;
//From E14 to AI19
        //COLUMN F
        $this->SchedC2FFSheet->SetCellValue('E15', '=SUM(F15:AI15)');
        $this->SchedC2FFSheet->SetCellValue('E16', '=SUM(F16:AI16)');
        $this->SchedC2FFSheet->SetCellValue('E17', '=SUM(F17:AI17)');
        $this->SchedC2FFSheet->SetCellValue('E18', '=SUM(F18:AI18)');
        $this->SchedC2FFSheet->SetCellValue('F14', '36933'); //THESE HAVE TO BE ENTERED BY USER
        $this->SchedC2FFSheet->SetCellValue('F15', '=\'Basic Info\'!$C$9'); // THESE HAVE TO BE ENTERED BY USER OR MOST LIKELY PUT INTO BASICINFO
        $this->SchedC2FFSheet->SetCellValue('F18', '=+F14*\'Basic Info\'!$C$16');
        $this->SchedC2FFSheet->SetCellValue('F19', '=+F14+F15+F16+F18');
        $curLetter = 'F';
        //COLUMN G-Z
        foreach($cellRange1 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue($letter . 14, '=+' . $curLetter . $this->RFClosingBalanceRow);
            //THERE IS A MAXIMUM ANNUAL RESERVE FUND CONTRIBUTION AMOUNT THAT MUST BE SET
            $this->SchedC2FFSheet->SetCellValue($letter . 15, '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15'); // ANNUAL RESERVE FUND CONTRIBUTIONS MUST BE HERE
            if($letter == 'G')
                $this->SchedC2FFSheet->SetCellValue($letter . 16, '=F' . $this->reserveSurplusDeficiencyRow);
            $this->SchedC2FFSheet->SetCellValue($letter . 18, '=+' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC2FFSheet->SetCellValue($letter . 19, '=+' . $letter . '14+' . $letter . '15+' . $letter . '16+' . $letter . '18');

            $curLetter = $letter;
        }
        //COLUMN AA
        $this->SchedC2FFSheet->SetCellValue('AA14', '=+Z' . $this->RFClosingBalanceRow);
        $this->SchedC2FFSheet->SetCellValue('AA15', '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15');
        $this->SchedC2FFSheet->SetCellValue('AA18', '=+AA14*\'Basic Info\'!$C$16');
        $this->SchedC2FFSheet->SetCellValue('AA19', '=+AA14+AA15+AA16+AA18');
        $curLetter = 'A';
        //COLUMN AB-AI
        foreach($cellRange2 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . $letter . 14, '=+A' . $curLetter . $this->RFClosingBalanceRow);
            $this->SchedC2FFSheet->SetCellValue('A' . $letter . 15, '=(100%+\'Basic Info\'!$C$11)*A' . $curLetter . '15');
            $this->SchedC2FFSheet->SetCellValue('A' . $letter . 18, '=+A' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC2FFSheet->SetCellValue('A' . $letter . 19, '=+A' . $letter . '14+A' . $letter . '15+A' . $letter . '16+A' . $letter . '18');

            $curLetter = $letter;
        }
        $cellRange3 = range('F', 'Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($cellRange3, 'A' . $letter);
        }
        //CALCULATE TOTALS FOR EACH COMPONENT
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC2FFSheet->SetCellValue('C' . (22 + $i), '=\'Basic Info\'!$C$7+\'Sched. A\'!F'. ($i + 4));
            $this->SchedC2FFSheet->SetCellValue('D' . (22 + $i), '=\'Sched. A\'!D'. ($i + 4));
            $this->SchedC2FFSheet->SetCellValue('E' . (22 + $i), '=SUM(F' . (22 + $i). ':AI' . (22 + $i) . ')');
            $replaceEvery = $this->SchedC2FFSheet->getCell('D' . (22 + $i))->getCalculatedValue();
            $replaced = false;
            $yearsAfter = 0;
            $replaceLetter = '';
            foreach($cellRange3 as $letter)
            {

                if (!$replaced)
                {
                    $this->SchedC2FFSheet->SetCellValue($letter . (22 + $i), '=IF(' . $letter . '$12=$C' . (22 + $i). ',\'Sched. A\'!$K4,0)');
                    if ($this->SchedC2FFSheet->getCell($letter . '12')->getCalculatedValue() ==
                        $this->SchedC2FFSheet->getCell('C' . (22 + $i))->getCalculatedValue())
                    {
                        $replaced = true;
                        $replaceLetter = $letter;
                    }
                }
                else
                {
                    $yearsAfter++;
                    if ($yearsAfter % $replaceEvery == 0)
                    {
                        $this->SchedC2FFSheet->SetCellValue($letter . (22 + $i), '=' . $replaceLetter . (22 + $i) . '*(1+\'Basic Info\'!C14)^' . $replaceEvery);
                        $replaceLetter = $letter;
                    }
                    else
                    {
                        $this->SchedC2FFSheet->SetCellValue($letter . (22 + $i), 0);
                    }
                }
            }
        }
        $this->SchedC2FFSheet->SetCellValue('E' . ($this->totalExpendituresRow - 1), '=SUM(E22:E' . $lastComponentRow . ')');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->totalExpendituresRow, '=SUM('. $letter . 22 . ':' . $letter . $lastComponentRow . ')');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->RFClosingBalanceRow, '=+' . $letter . '19-' . $letter . $this->totalExpendituresRow);
        }
        //L59 needs to be set up properly for variable amounts of components
        $this->SchedC2FFSheet->SetCellValue( 'F' . $this->RFRequirementseRow, '=\'Sched. A\'!L' . $this->schedATotalsRow . '-F' . $this->totalExpendituresRow);
        $lastCol = 'F';
        foreach($cellRange3 as $letter)
        {
            if ($letter == 'F')
                continue;
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->RFRequirementseRow, '=(+' . $lastCol . $this->RFRequirementseRow . '*(1+\'Basic Info\'!$C$16))+\'Sched. A\'!$O$' . $this->schedATotalsRow . '-' . $letter . $this->totalExpendituresRow);
            $lastCol = $letter;
        }

        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->reserveSurplusDeficiencyRow, '=+' . $letter . $this->RFClosingBalanceRow . '-' . $letter . $this->RFRequirementseRow);
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->reserveAdequacyRow, '=' . $letter . $this->RFClosingBalanceRow . '/' . $letter . $this->RFRequirementseRow);
        }
        $this->SchedC2FFSheet->SetCellValue( $letter . $this->monthlyASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3/12');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->annualASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->annualPossibleSpecialLeviesRow, '=' . $letter . '16/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . $this->totalASLContributionsSpecialLeviesRow, '=(' . $letter . '15+' . $letter . '16)/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC2FFSheet->SetCellValue( $letter . 11, '='.$letter . $this->reserveAdequacyRow);
        }

        //STYLED LARGE TABLE YEARS 1-15
        $this->largeTable1To15Start = $this->totalASLContributionsSpecialLeviesRow + 2;
        $cursor = $this->totalASLContributionsSpecialLeviesRow + 2;
        //TOP ROW WITH TITLES
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC2FFSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC2FFSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC2FFSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        $largeTableArray1 = array(
            'Construction Inflation Rate:',
            'Investments Interest Rate:',
            'Fiscal Year End:',
            'RESERVE FUND OPENING BALANCE',
            'Annual Reserve Fund Contributions',
            'Possible Special Levies',
            'Possible Borrowings',
            'Annual Reserve Fund Interest Income',
            'Total Cash Resources',
            'RESERVE FUND EXPENDITURES');
        //$this->dummp($largeTableArray1);
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        $columnC = array(
            '=\'Basic Info\'!C14',
            '=\'Basic Info\'!$C$16',
            '=\'Basic Info\'!C8',
            'Current Reserve Fund Requirements',
            '',
            '',
            'Current Reserve Fund Requirements'
        );
        for ($i = ($this->largeTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        $nonComponent1to15 = array(
            12, 14, 15, 16, 17, 18, 19
        );
        $largeTable15YearsRange = range('D', 'R');

        for($i = ($this->largeTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        $schedAComponentRows = 4;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {

            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }

        //YEARS 1-15 AFTER COMPONENTS
        $afterComponentsArray = array(
            'TOTAL RESERVE FUND EXPENDITURES',
            'Reserve Fund Closing Balance',
            'Reserve Fund Requirements',
            'Reserve Surplus (Deficiency)',
            'Reserve Adequacy',
            'Monthly ASL Contributions',
            'Annual ASL Contributions',
            'Annual ASL Possible Special Levies',
            'Total Annual ASL Contributions and Special Levies',
        );
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->largeTable16To30Start = ($i + 2);
        }
        $this->SchedC2FFSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION

        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }

        //STYLED LARGE TABLE YEARS 16-30
        $cursor = $this->largeTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC2FFSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC2FFSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC2FFSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->largeTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->largeTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable1To15Start = ($i + 2);
        }
        $this->SchedC2FFSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }

        //STYLED SMALL TABLE YEARS 1-15
        $cursor = $this->smallTable1To15Start;
        //TOP ROW WITH TITLES
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC2FFSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC2FFSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC2FFSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        for ($i = ($this->smallTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        for($i = ($this->smallTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $schedAComponentRows = 4;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }
        //YEARS 1-15 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable16To30Start = ($i + 2);
        }
        $this->SchedC2FFSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }
        //STYLED SMALL TABLE YEARS 16-30
        $cursor = $this->smallTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC2FFSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC2FFSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC2FFSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC2FFSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->smallTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->smallTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC2FFSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
        }
        $this->SchedC2FFSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC2FFSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }
    }

    function styleScheduleC2FF()
    {
        $allBorders = array(
            'borders' => array(
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array( 'rgb' => 'BFBFBF')
                ),
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
            )
        );

        //OUTLINE
        $this->SchedC2FFSheet->getStyle('A12:AI'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray($allBorders);
        $this->SchedC2FFSheet->getStyle('A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15End)->applyFromArray($allBorders);
        $this->SchedC2FFSheet->getStyle('A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30End)->applyFromArray($allBorders);
        $this->SchedC2FFSheet->getStyle('A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15End)->applyFromArray($allBorders);
        $this->SchedC2FFSheet->getStyle('A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30End)->applyFromArray($allBorders);

        //WIDTHS
        $r = range("I", "Z");
        foreach(range("A", "I") as $i)
        {
            array_push($r, "A".$i);
        }
        $this->SchedC2FFSheet->getColumnDimension("A")->setWidth(5);
        $this->SchedC2FFSheet->getColumnDimension("B")->setWidth(45);
        foreach($r as $i)
        {
            $this->SchedC2FFSheet->getColumnDimension($i)->setWidth(10);
        }
        foreach(range('C', 'H') as $i)
        {
            $this->SchedC2FFSheet->getColumnDimension($i)->setWidth(13);
        }

        //HEIGHTS
        foreach(range(1, 10) as $i)
        {
            $this->SchedC2FFSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC2FFSheet->getRowDimension(12)->setRowHeight(60);
        $this->SchedC2FFSheet->getRowDimension($this->largeTable1To15Start)->setRowHeight(20);
        $this->SchedC2FFSheet->getRowDimension($this->largeTable16To30Start)->setRowHeight(20);

        $this->SchedC2FFSheet->getRowDimension($this->smallTable1To15Start)->setRowHeight(20);
        foreach(range(($this->smallTable1To15Start+11), ($this->smallTable1To15Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC2FFSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC2FFSheet->getRowDimension($this->smallTable16To30Start)->setRowHeight(20);
        foreach(range(($this->smallTable16To30Start+11), ($this->smallTable16To30Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC2FFSheet->getRowDimension($i)->setRowHeight(0);
        }

        //COLOURING
        $this->cellColor($this->SchedC2FFSheet, 'F15:AI16', 'DAEEF3');
        $this->cellColor($this->SchedC2FFSheet, 'A'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow, 'EBF1DE');

        $this->cellColor($this->SchedC2FFSheet, 'A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC2FFSheet, 'A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4), 'DCE6F1');

        $this->cellColor($this->SchedC2FFSheet, 'A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC2FFSheet, 'A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC2FFSheet, 'A'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4), 'DCE6F1');



        //FONT
        $this->SchedC2FFSheet->getStyle('C12:E12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'bold' => true,
                'size' => 10
            )
        ));
        $this->SchedC2FFSheet->getStyle('F12:AI12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('A14')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('A15:A19')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('A21')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('A22:B'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('C22:D'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('E22:E'.($this->numComponents+22))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.$this->totalExpendituresRow.':E'.$this->reserveAdequacyRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('F14:E'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC2FFSheet->getStyle('A'.$this->largeTable1To15Start.':D'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.$this->largeTable16To30Start.':D'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.$this->smallTable1To15Start.':D'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.$this->smallTable16To30Start.':D'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('R'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC2FFSheet->getStyle('R'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC2FFSheet->getStyle('R'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC2FFSheet->getStyle('R'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable1To15Start+1).':C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable16To30Start+1).':C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable1To15Start+1).':C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable16To30Start+1).':C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15Start+3).':R'.($this->largeTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30Start+3).':R'.($this->largeTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15Start+3).':R'.($this->smallTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30Start+3).':R'.($this->smallTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('A'.($this->largeTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->largeTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->smallTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->smallTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('A'.($this->largeTable1To15End-8).':A'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->largeTable16To30End-8).':A'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->smallTable1To15End-8).':A'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('A'.($this->smallTable16To30End-8).':A'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        //MERGE
        $this->SchedC2FFSheet->mergeCells('C'.($this->largeTable1To15Start+4).':C'.($this->largeTable1To15Start+5));
        $this->SchedC2FFSheet->mergeCells('C'.($this->largeTable16To30Start+4).':C'.($this->largeTable16To30Start+5));
        $this->SchedC2FFSheet->mergeCells('C'.($this->smallTable1To15Start+4).':C'.($this->smallTable1To15Start+5));
        $this->SchedC2FFSheet->mergeCells('C'.($this->smallTable16To30Start+4).':C'.($this->smallTable16To30Start+5));

        $this->SchedC2FFSheet->mergeCells('C'.($this->largeTable1To15Start+7).':C'.($this->largeTable1To15Start+9));
        $this->SchedC2FFSheet->mergeCells('C'.($this->largeTable16To30Start+7).':C'.($this->largeTable16To30Start+9));
        $this->SchedC2FFSheet->mergeCells('C'.($this->smallTable1To15Start+7).':C'.($this->smallTable1To15Start+9));
        $this->SchedC2FFSheet->mergeCells('C'.($this->smallTable16To30Start+7).':C'.($this->smallTable16To30Start+9));

        $this->SchedC2FFSheet->mergeCells('A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10));
        $this->SchedC2FFSheet->mergeCells('A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10));
        $this->SchedC2FFSheet->mergeCells('A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10));
        $this->SchedC2FFSheet->mergeCells('A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10));

        //NUMBER FORMATS
        //$objReader = PHPExcel_IOFactory::createReader('Excel2007');
        //$objPHPExcel = $objReader->load('Docs/template.xlsx');
        //$this->dummp($objPHPExcel->getSheet(6)->getStyle('F142')->getNumberFormat()->getFormatCode());

        $this->SchedC2FFSheet->getStyle('F11:AI11')->getNumberFormat()->setFormatCode('0%');
        $this->SchedC2FFSheet->getStyle('F14:AI14')->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('E15:AI'.$this->totalASLContributionsSpecialLeviesRow)->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC2FFSheet->getStyle('E'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow)->getNumberFormat()->setFormatCode('0%');

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15Start+4).':R'.($this->largeTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15Start+11).':R'.($this->largeTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15End-3).':R'.($this->largeTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC2FFSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30Start+4).':R'.($this->largeTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30Start+11).':R'.($this->largeTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30End-3).':R'.($this->largeTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15Start+4).':R'.($this->smallTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15Start+11).':R'.($this->smallTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15End-3).':R'.($this->smallTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC2FFSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30Start+4).':R'.($this->smallTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30Start+11).':R'.($this->smallTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30End-3).':R'.($this->smallTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC2FFSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->getNumberFormat()->setFormatCode('0%');


    }

    function getScheduleC2CFT()
    {
        self::setupScheduleC2CFT();
    }

    function setupScheduleC2CFT()
    {
        //TITLES OF TABLE 1
        $this->SchedC2CFTSheet->SetCellValue( 'A' . 1 , 'Strata:');
        $this->SchedC2CFTSheet->SetCellValue( 'C' . 1, '=\'Basic Info\'!$C$2');
        $this->SchedC2CFTSheet->SetCellValue( 'D' . 1, ' Schedule C.2 – FULL FUNDING MODEL – 30 Year RESERVE FUND CASH FLOW TABLE');
        $this->SchedC2CFTSheet->SetCellValue( 'A' . 2, 'Fiscal Year End');
        $this->SchedC2CFTSheet->SetCellValue( 'A' . 3, '=\'Basic Info\'!C8');
        $this->SchedC2CFTSheet->SetCellValue( 'A' . 34, 'Totals over 30 years');
        $this->SchedC2CFTSheet->SetCellValue( 'A' . 35, 'Average per year over 30 years');
        $titlesArray = array(
            'Reserve Fund Opening Balance',
            'Recommended Annual RF Contributions',
            'Percentage Increase in RF Contributions',
            'Possible Special Levies',
            'Borrowings and Loan Financing',
            'Estimated Reserve Fund Interest Earned',
            'Estimated Inflation Adjusted Expenditures',
            'Reserve Fund Closing Balance',
            'Total Annual ASL Contributions and Special Levies',
            'Monthly ASL Contributions',
            'Annual ASL Possible Special Levies',
        );
        $i = 0;
        foreach( range('B', 'L') as $letter)
        {
            $this->SchedC2CFTSheet->SetCellValue( $letter . 2, $titlesArray[$i]);
            $i++;
        }

        //The INNER PORTION OF TABLE 1
        $columnsRange = range('A', 'G');
        $yearsRange = range('F','Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($yearsRange, 'A' . $letter);
        }
        $aids = array(12, 14, 15, 16, 17, 18);
        for ($i = 0; $i < 30; $i++)
        {
            if ($i != 0)
            {
                $this->SchedC2CFTSheet->SetCellValue('D' . ($i+4),'=(C' . ($i+4) . '/C' . ($i+3) . ')-100%');
            } else {
            $this->SchedC2CFTSheet->SetCellValue('D' . ($i+4),'Not Calculated');
            }
            $j = 0;
            foreach($columnsRange as $letter)
            {
                if($letter == 'D')
                    continue;
                $this->SchedC2CFTSheet->SetCellValue( $letter . ($i+4), '=\'Sched. C.2 FF\'!$' . $yearsRange[$i] . '$' .  $aids[$j]);
                $j++;
            }
            $this->SchedC2CFTSheet->SetCellValue( 'H' . ($i+4), '=\'Sched. C.2 FF\'!$' . $yearsRange[$i] . '$' . $this->totalExpendituresRow);
            $this->SchedC2CFTSheet->SetCellValue( 'I' . ($i+4), '=\'Sched. C.2 FF\'!$' . $yearsRange[$i] . '$' . ($this->totalExpendituresRow + 1));
            $this->SchedC2CFTSheet->SetCellValue( 'J' . ($i+4),'=(C' . ($i+4) . '+E' . ($i+4) .')/\'Basic Info\'!$C$3');
            $this->SchedC2CFTSheet->SetCellValue( 'K' . ($i+4),'=C' . ($i+4) . '/\'Basic Info\'!$C$3/12');
            $this->SchedC2CFTSheet->SetCellValue( 'L' . ($i+4),'=E' . ($i+4) . '/\'Basic Info\'!$C$3');
        }
        //SUMS AT BOTTOM
        $this->SchedC2CFTSheet->SetCellValue( 'C' . 34, '=SUM(C4:C33)');
        $this->SchedC2CFTSheet->SetCellValue( 'E' . 34, '=SUM(E4:E33)');
        $this->SchedC2CFTSheet->SetCellValue( 'G' . 34, '=SUM(G4:G33)');

        $this->SchedC2CFTSheet->SetCellValue( 'H' . 34, '=SUM(H4:H33)');
        $this->SchedC2CFTSheet->SetCellValue( 'J' . 34, '=SUM(J4:J33)');
        $this->SchedC2CFTSheet->SetCellValue( 'L' . 34, '=SUM(L4:L33)');

        $this->SchedC2CFTSheet->SetCellValue( 'J' . 35, '=AVERAGE(J4:J33)');
        $this->SchedC2CFTSheet->SetCellValue( 'K' . 35, '=AVERAGE(K4:K33)');
        $this->SchedC2CFTSheet->SetCellValue( 'L' . 35, '=AVERAGE(L4:L33)');
    }

    function getScheduleC3UN()
    {
        self::setupScheduleC3UN();
    }

    function setupScheduleC3UN()
    {
        //setup Titles starting at row 12
        $this->SchedC3UNSheet->SetCellValue('C12', 'First Occurrence');
        $this->SchedC3UNSheet->SetCellValue('D12', 'Repeat Every');
        $this->SchedC3UNSheet->SetCellValue('E12', 'Totals at Year 30 without Allowances');
        $cellRange1 = range('G', 'Z');
        $cellRange2 = range('B', 'I');
        $curLetter = 'F';
        $this->SchedC3UNSheet->SetCellValue('F12', '=\'Basic Info\'!C7+1');

        foreach($cellRange1 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue($letter . 12, '=' . $curLetter . '12 + 1');

            $curLetter = $letter;
        }
        $this->SchedC3UNSheet->SetCellValue('AA12', '=Z12 + 1');
        $curLetter = 'A';
        foreach($cellRange2 as $letter)
        {
            //echo 'A' . $letter . 12, '=A' . $curLetter . '12 + 1';exit;
            $this->SchedC3UNSheet->SetCellValue('A' . $letter . 12, '=A' . $curLetter . '12 + 1');
            $curLetter = $letter;
        }

        //setup titles A14-A19
        $titles = array(
            'Reserve Fund Opening Balance',
            'Annual Reserve Fund Contributions',
            'Possible Special Levies',
            'Possible Borrowings',
            'Annual Reserve Fund Interest Income',
            'Total Cash Resources'
        );
        for ($i = 0; $i < 6; $i++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . ($i + 14), $titles[$i]);
        }
        $this->SchedC3UNSheet->SetCellValue('A21', 'Reserve Expenditures');
        //list components by L4Name
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . (22 + $i), ($i + 1));
            $this->SchedC3UNSheet->SetCellValue('B' . (22 + $i), $this->allComponents[$i]);
            $cursor = 22 + $i;

        }
        $lastComponentRow = $cursor;
        $cursor += 2;
        //the totals
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Total Expenditures');
        $this->totalExpendituresRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Reserve Fund Closing Balance');
        $this->RFClosingBalanceRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Reserve Fund Requirements');
        $this->RFRequirementseRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Reserve Surplus (Deficiency)');
        $this->reserveSurplusDeficiencyRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Reserve Adequacy');
        $this->reserveAdequacyRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Monthly ASL Contributions');
        $this->monthlyASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Annual ASL Contributions');
        $this->annualASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Annual ASL Possible Special Levies');
        $this->annualPossibleSpecialLeviesRow = $cursor;
        $cursor++;
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Total Annual ASL Contributions and Special Levies');
        $this->totalASLContributionsSpecialLeviesRow = $cursor;
//From E14 to AI19
        //COLUMN F
        $this->SchedC3UNSheet->SetCellValue('E15', '=SUM(F15:AI15)');
        $this->SchedC3UNSheet->SetCellValue('E16', '=SUM(F16:AI16)');
        $this->SchedC3UNSheet->SetCellValue('E17', '=SUM(F17:AI17)');
        $this->SchedC3UNSheet->SetCellValue('E18', '=SUM(F18:AI18)');

        $this->SchedC3UNSheet->SetCellValue('F14', '36933'); //THESE HAVE TO BE ENTERED BY USER
        $this->SchedC3UNSheet->SetCellValue('F15', '=\'Basic Info\'!$C$9'); // THESE HAVE TO BE ENTERED BY USER OR MOST LIKELY PUT INTO BASICINFO
        $this->SchedC3UNSheet->SetCellValue('F18', '=+F14*\'Basic Info\'!$C$16');
        $this->SchedC3UNSheet->SetCellValue('F19', '=+F14+F15+F16+F18');
        $curLetter = 'F';
        //COLUMN G-Z
        foreach($cellRange1 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue($letter . 14, '=+' . $curLetter . $this->RFClosingBalanceRow);
            //THERE IS A MAXIMUM ANNUAL RESERVE FUND CONTRIBUTION AMOUNT THAT MUST BE SET
            $this->SchedC3UNSheet->SetCellValue($letter . 15, '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15'); // ANNUAL RESERVE FUND CONTRIBUTIONS MUST BE HERE
            $this->SchedC3UNSheet->SetCellValue($letter . 18, '=+' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC3UNSheet->SetCellValue($letter . 19, '=+' . $letter . '14+' . $letter . '15+' . $letter . '16+' . $letter . '18');

            $curLetter = $letter;
        }
        //COLUMN AA
        $this->SchedC3UNSheet->SetCellValue('AA14', '=+Z' . $this->RFClosingBalanceRow);
        $this->SchedC3UNSheet->SetCellValue('AA15', '=(100%+\'Basic Info\'!$C$11)*' . $curLetter . '15');
        $this->SchedC3UNSheet->SetCellValue('AA18', '=+AA14*\'Basic Info\'!$C$16');
        $this->SchedC3UNSheet->SetCellValue('AA19', '=+AA14+AA15+AA16+AA18');
        $curLetter = 'A';
        //COLUMN AB-AI
        foreach($cellRange2 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . $letter . 14, '=+A' . $curLetter . $this->RFClosingBalanceRow);
            $this->SchedC3UNSheet->SetCellValue('A' . $letter . 15, '=(100%+\'Basic Info\'!$C$11)*A' . $curLetter . '15');
            $this->SchedC3UNSheet->SetCellValue('A' . $letter . 18, '=+A' . $letter . '14*\'Basic Info\'!$C$16');
            $this->SchedC3UNSheet->SetCellValue('A' . $letter . 19, '=+A' . $letter . '14+A' . $letter . '15+A' . $letter . '16+A' . $letter . '18');

            $curLetter = $letter;
        }
        $cellRange3 = range('F', 'Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($cellRange3, 'A' . $letter);
        }
        //CALCULATE TOTALS FOR EACH COMPONENT
        for ($i = 0; $i < $this->numComponents; $i++)
        {
            $this->SchedC3UNSheet->SetCellValue('C' . (22 + $i), '=\'Basic Info\'!$C$7+\'Sched. A\'!F'. ($i + 4));
            $this->SchedC3UNSheet->SetCellValue('D' . (22 + $i), '=\'Sched. A\'!D'. ($i + 4));
            $this->SchedC3UNSheet->SetCellValue('E' . (22 + $i), '=SUM(F' . (22 + $i). ':AI' . (22 + $i) . ')');
            $replaceEvery = $this->SchedC3UNSheet->getCell('D' . (22 + $i))->getCalculatedValue();
            $replaced = false;
            $yearsAfter = 0;
            $replaceLetter = '';
            foreach($cellRange3 as $letter)
            {

                if (!$replaced)
                {
                    $this->SchedC3UNSheet->SetCellValue($letter . (22 + $i), '=IF(' . $letter . '$12=$C' . (22 + $i). ',\'Sched. A\'!$K4,0)');
                    if ($this->SchedC3UNSheet->getCell($letter . '12')->getCalculatedValue() ==
                        $this->SchedC3UNSheet->getCell('C' . (22 + $i))->getCalculatedValue())
                    {
                        $replaced = true;
                        $replaceLetter = $letter;
                    }
                }
                else
                {
                    $yearsAfter++;
                    if ($yearsAfter % $replaceEvery == 0)
                    {
                        $this->SchedC3UNSheet->SetCellValue($letter . (22 + $i), '=' . $replaceLetter . (22 + $i) . '*(1+\'Basic Info\'!C14)^' . $replaceEvery);
                        $replaceLetter = $letter;
                    }
                    else
                    {
                        $this->SchedC3UNSheet->SetCellValue($letter . (22 + $i), 0);
                    }
                }
            }
        }
        $this->SchedC3UNSheet->SetCellValue('E' . ($this->totalExpendituresRow - 1), '=SUM(E22:E' . $lastComponentRow . ')');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->totalExpendituresRow, '=SUM('. $letter . 22 . ':' . $letter . $lastComponentRow . ')');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->RFClosingBalanceRow, '=+' . $letter . '19-' . $letter . $this->totalExpendituresRow);
        }
        //L59 needs to be set up properly for variable amounts of components
        $this->SchedC3UNSheet->SetCellValue( 'F' . $this->RFRequirementseRow, '=\'Sched. A\'!L' . $this->schedATotalsRow . '-F' . $this->totalExpendituresRow);
        $lastCol = 'F';
        foreach($cellRange3 as $letter)
        {
            if ($letter == 'F')
                continue;
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->RFRequirementseRow, '=(+' . $lastCol . $this->RFRequirementseRow . '*(1+\'Basic Info\'!$C$16))+\'Sched. A\'!$O$' . $this->schedATotalsRow . '-' . $letter . $this->totalExpendituresRow);
            $lastCol = $letter;
        }

        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->reserveSurplusDeficiencyRow, '=+' . $letter . $this->RFClosingBalanceRow . '-' . $letter . $this->RFRequirementseRow);
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->reserveAdequacyRow, '=' . $letter . $this->RFClosingBalanceRow . '/' . $letter . $this->RFRequirementseRow);
        }
        $this->SchedC3UNSheet->SetCellValue( $letter . $this->monthlyASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3/12');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->annualASLContributionsRow, '=' . $letter . '15/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->annualPossibleSpecialLeviesRow, '=' . $letter . '16/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . $this->totalASLContributionsSpecialLeviesRow, '=(' . $letter . '15+' . $letter . '16)/\'Basic Info\'!$C$3');
        }
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC3UNSheet->SetCellValue( $letter . 11, '='.$letter . $this->reserveAdequacyRow);
        }

        //STYLED LARGE TABLE YEARS 1-15
        $this->largeTable1To15Start = $this->totalASLContributionsSpecialLeviesRow + 2;
        $cursor = $this->totalASLContributionsSpecialLeviesRow + 2;
        //TOP ROW WITH TITLES
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC3UNSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC3UNSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC3UNSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        $largeTableArray1 = array(
            'Construction Inflation Rate:',
            'Investments Interest Rate:',
            'Fiscal Year End:',
            'RESERVE FUND OPENING BALANCE',
            'Annual Reserve Fund Contributions',
            'Possible Special Levies',
            'Possible Borrowings',
            'Annual Reserve Fund Interest Income',
            'Total Cash Resources',
            'RESERVE FUND EXPENDITURES');
        //$this->dummp($largeTableArray1);
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        $columnC = array(
            '=\'Basic Info\'!C14',
            '=\'Basic Info\'!$C$16',
            '=\'Basic Info\'!C8',
            'Current Reserve Fund Requirements',
            '',
            '',
            'Current Reserve Fund Requirements'
        );
        for ($i = ($this->largeTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        $nonComponent1to15 = array(
            12, 14, 15, 16, 17, 18, 19
        );
        $largeTable15YearsRange = range('D', 'R');

        for($i = ($this->largeTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        $schedAComponentRows = 4;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {

            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }

        //YEARS 1-15 AFTER COMPONENTS
        $afterComponentsArray = array(
            'TOTAL RESERVE FUND EXPENDITURES',
            'Reserve Fund Closing Balance',
            'Reserve Fund Requirements',
            'Reserve Surplus (Deficiency)',
            'Reserve Adequacy',
            'Monthly ASL Contributions',
            'Annual ASL Contributions',
            'Annual ASL Possible Special Levies',
            'Total Annual ASL Contributions and Special Levies',
        );
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->largeTable16To30Start = ($i + 2);
        }
        $this->SchedC3UNSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION

        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }

        //STYLED LARGE TABLE YEARS 16-30
        $cursor = $this->largeTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC3UNSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC3UNSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC3UNSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->largeTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->largeTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        $years1to15ComponentsRange = range('A', 'R');

        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable1To15Start = ($i + 2);
        }
        $this->SchedC3UNSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }

        //STYLED SMALL TABLE YEARS 1-15
        $cursor = $this->smallTable1To15Start;
        //TOP ROW WITH TITLES
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC3UNSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC3UNSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC3UNSheet->SetCellValue('R' . $cursor, 'Years 1 - 15');
        $cursor++;


        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        //Column C
        for ($i = ($this->smallTable1To15Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 1-15 NONCOMPONENT VALUES
        for($i = ($this->smallTable1To15Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
            }
        }
        //YEARS 1-15 COMPONENTS
        $schedAComponentRows = 4;
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                $l = $letter; ++$l;++$l;
                if($letter == 'C')
                {
                    $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }

                }
            }
            $afterComponents = ($i+1);
        }
        //YEARS 1-15 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
            $this->smallTable16To30Start = ($i + 2);
        }
        $this->SchedC3UNSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 1-15 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            foreach($largeTable15YearsRange as $letter)
            {
                $l = $letter;
                ++$l;++$l;
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
            }
        }
        //STYLED SMALL TABLE YEARS 16-30
        $cursor = $this->smallTable16To30Start;
        //TOP ROW WITH TITLES
        $this->SchedC3UNSheet->SetCellValue('A' . $cursor, 'Strata:');
        $this->SchedC3UNSheet->SetCellValue('C' . $cursor, '=\'Basic Info\'!$C$2');
        $this->SchedC3UNSheet->SetCellValue('D' . $cursor, '  Schedule C.1 – THRESHOLD MODEL – 30 Year RESERVE FUND CASH FLOW PROJECTION AND DEFICIENCY ANALYSIS');
        $this->SchedC3UNSheet->SetCellValue('R' . $cursor, 'Years 16 - 30');
        $cursor++;
        //title of next section
        for ($i = $cursor, $j = 0; $j < 10; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('A' . $i , $largeTableArray1[$j]);
            $componentsRowStart = ($i+1);
        }

        for ($i = ($this->smallTable16To30Start + 1), $j = 0; $j < 7; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue('C' . $i, $columnC[$j]);
        }
        //YEARS 16-30 NONCOMPONENT VALUES
        for($i = ($this->smallTable16To30Start+3), $j = 0; $j < 7; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . $nonComponent1to15[$j]);
                ++$l;
            }
        }
        //YEARS 16-30 COMPONENTS
        for($i = $componentsRowStart, $j = 0; $j < $this->numComponents; $i++, $j++)
        {
            $l = 'U';
            if ($this->SchedASheet->getCell('L' . $schedAComponentRows)->getValue() == NULL)
                $schedAComponentRows++;
            foreach($years1to15ComponentsRange as $letter)
            {
                if($letter == 'C')
                {
                    $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=\'Sched. A\'!L' . ($j + $schedAComponentRows));
                }
                else
                {
                    if ($letter == 'A' || $letter == 'B')
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $letter . ($j + 22));
                    }
                    else
                    {
                        $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($j + 22));
                    }
                }
                ++$l;
            }
            $afterComponents = ($i+1);
        }

        //YEARS 16-30 AFTER COMPONENTS
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $this->SchedC3UNSheet->SetCellValue( 'A' . $i, $afterComponentsArray[$j]);
        }
        $this->SchedC3UNSheet->SetCellValue( 'C' . $afterComponents, '=SUM(C' . $componentsRowStart . ':' . 'C' . ($afterComponents-1) . ')');
        //YEARS 16-30 FILL ALL AFTER COMPONENTS SECTION
        for($i = $afterComponents, $j = 0; $j < 9; $i++, $j++)
        {
            $l = 'U';
            foreach($largeTable15YearsRange as $letter)
            {
                $this->SchedC3UNSheet->SetCellValue( $letter . $i, '=' . $l . ($this->totalExpendituresRow + $j));
                ++$l;
            }
        }
    }

    function styleScheduleC3UN()
    {
        $allBorders = array(
            'borders' => array(
                'inside' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array( 'rgb' => 'BFBFBF')
                ),
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
            )
        );

        //OUTLINE
        $this->SchedC3UNSheet->getStyle('A12:AI'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray($allBorders);
        $this->SchedC3UNSheet->getStyle('A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15End)->applyFromArray($allBorders);
        $this->SchedC3UNSheet->getStyle('A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30End)->applyFromArray($allBorders);
        $this->SchedC3UNSheet->getStyle('A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15End)->applyFromArray($allBorders);
        $this->SchedC3UNSheet->getStyle('A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30End)->applyFromArray($allBorders);

        //WIDTHS
        $r = range("I", "Z");
        foreach(range("A", "I") as $i)
        {
            array_push($r, "A".$i);
        }
        $this->SchedC3UNSheet->getColumnDimension("A")->setWidth(5);
        $this->SchedC3UNSheet->getColumnDimension("B")->setWidth(45);
        foreach($r as $i)
        {
            $this->SchedC3UNSheet->getColumnDimension($i)->setWidth(10);
        }
        foreach(range('C', 'H') as $i)
        {
            $this->SchedC3UNSheet->getColumnDimension($i)->setWidth(13);
        }

        //HEIGHTS
        foreach(range(1, 10) as $i)
        {
            $this->SchedC3UNSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC3UNSheet->getRowDimension(12)->setRowHeight(60);
        $this->SchedC3UNSheet->getRowDimension($this->largeTable1To15Start)->setRowHeight(20);
        $this->SchedC3UNSheet->getRowDimension($this->largeTable16To30Start)->setRowHeight(20);

        $this->SchedC3UNSheet->getRowDimension($this->smallTable1To15Start)->setRowHeight(20);
        foreach(range(($this->smallTable1To15Start+11), ($this->smallTable1To15Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC3UNSheet->getRowDimension($i)->setRowHeight(0);
        }
        $this->SchedC3UNSheet->getRowDimension($this->smallTable16To30Start)->setRowHeight(20);
        foreach(range(($this->smallTable16To30Start+11), ($this->smallTable16To30Start+$this->numComponents+10)) as $i)
        {
            $this->SchedC3UNSheet->getRowDimension($i)->setRowHeight(0);
        }

        //COLOURING
        $this->cellColor($this->SchedC3UNSheet, 'F15:AI16', 'DAEEF3');
        $this->cellColor($this->SchedC3UNSheet, 'A'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow, 'EBF1DE');

        $this->cellColor($this->SchedC3UNSheet, 'A'.$this->largeTable1To15Start.':R'.$this->largeTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC3UNSheet, 'A'.$this->largeTable16To30Start.':R'.$this->largeTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4), 'DCE6F1');

        $this->cellColor($this->SchedC3UNSheet, 'A'.$this->smallTable1To15Start.':R'.$this->smallTable1To15Start, 'DCE6F1');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-8), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4), 'DCE6F1');

        $this->cellColor($this->SchedC3UNSheet, 'A'.$this->smallTable16To30Start.':R'.$this->smallTable16To30Start, 'DCE6F1');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-8), 'EBF1DE');
        $this->cellColor($this->SchedC3UNSheet, 'A'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4), 'DCE6F1');



        //FONT
        $this->SchedC3UNSheet->getStyle('C12:E12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'bold' => true,
                'size' => 10
            )
        ));
        $this->SchedC3UNSheet->getStyle('F12:AI12')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('A14')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('A15:A19')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('A21')->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'bold' => true,
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('A22:B'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('C22:D'.($this->numComponents+21))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('E22:E'.($this->numComponents+22))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.$this->totalExpendituresRow.':E'.$this->reserveAdequacyRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('F14:E'.$this->totalASLContributionsSpecialLeviesRow)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC3UNSheet->getStyle('A'.$this->largeTable1To15Start.':D'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.$this->largeTable16To30Start.':D'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.$this->smallTable1To15Start.':D'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.$this->smallTable16To30Start.':D'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 14,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('R'.$this->largeTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC3UNSheet->getStyle('R'.$this->largeTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC3UNSheet->getStyle('R'.$this->smallTable1To15Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));
        $this->SchedC3UNSheet->getStyle('R'.$this->smallTable16To30Start)->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 10
            )
        ));

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable1To15Start+1).':C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable16To30Start+1).':C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable1To15Start+1).':C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable16To30Start+1).':C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11
            )
        ));

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable1To15Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable16To30Start+4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15Start+3).':R'.($this->largeTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30Start+3).':R'.($this->largeTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15Start+3).':R'.($this->smallTable1To15Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30Start+3).':R'.($this->smallTable16To30Start+3))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable1To15Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable16To30Start+7))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('A'.($this->largeTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->largeTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->smallTable1To15Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->smallTable16To30Start+10))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('A'.($this->largeTable1To15End-8).':A'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->largeTable16To30End-8).':A'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->smallTable1To15End-8).':A'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('A'.($this->smallTable16To30End-8).':A'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+11+$this->numComponents))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->applyFromArray(array(
            'alignment' => array(
                'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM
            ),
            'font' => array(
                'size' => 11,
                'bold' => true
            )
        ));

        //MERGE
        $this->SchedC3UNSheet->mergeCells('C'.($this->largeTable1To15Start+4).':C'.($this->largeTable1To15Start+5));
        $this->SchedC3UNSheet->mergeCells('C'.($this->largeTable16To30Start+4).':C'.($this->largeTable16To30Start+5));
        $this->SchedC3UNSheet->mergeCells('C'.($this->smallTable1To15Start+4).':C'.($this->smallTable1To15Start+5));
        $this->SchedC3UNSheet->mergeCells('C'.($this->smallTable16To30Start+4).':C'.($this->smallTable16To30Start+5));

        $this->SchedC3UNSheet->mergeCells('C'.($this->largeTable1To15Start+7).':C'.($this->largeTable1To15Start+9));
        $this->SchedC3UNSheet->mergeCells('C'.($this->largeTable16To30Start+7).':C'.($this->largeTable16To30Start+9));
        $this->SchedC3UNSheet->mergeCells('C'.($this->smallTable1To15Start+7).':C'.($this->smallTable1To15Start+9));
        $this->SchedC3UNSheet->mergeCells('C'.($this->smallTable16To30Start+7).':C'.($this->smallTable16To30Start+9));

        $this->SchedC3UNSheet->mergeCells('A'.($this->largeTable1To15Start+10).':R'.($this->largeTable1To15Start+10));
        $this->SchedC3UNSheet->mergeCells('A'.($this->largeTable16To30Start+10).':R'.($this->largeTable16To30Start+10));
        $this->SchedC3UNSheet->mergeCells('A'.($this->smallTable1To15Start+10).':R'.($this->smallTable1To15Start+10));
        $this->SchedC3UNSheet->mergeCells('A'.($this->smallTable16To30Start+10).':R'.($this->smallTable16To30Start+10));

        //NUMBER FORMATS
        //$objReader = PHPExcel_IOFactory::createReader('Excel2007');
        //$objPHPExcel = $objReader->load('Docs/template.xlsx');
        //$this->dummp($objPHPExcel->getSheet(6)->getStyle('F142')->getNumberFormat()->getFormatCode());

        $this->SchedC3UNSheet->getStyle('F11:AI11')->getNumberFormat()->setFormatCode('0%');
        $this->SchedC3UNSheet->getStyle('F14:AI14')->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('E15:AI'.$this->totalASLContributionsSpecialLeviesRow)->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC3UNSheet->getStyle('E'.$this->reserveAdequacyRow.':AI'.$this->reserveAdequacyRow)->getNumberFormat()->setFormatCode('0%');

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable1To15Start+11).':C'.($this->largeTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15Start+4).':R'.($this->largeTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15Start+11).':R'.($this->largeTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15End-8).':R'.($this->largeTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15End-3).':R'.($this->largeTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable1To15End-4).':R'.($this->largeTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC3UNSheet->getStyle('C'.($this->largeTable16To30Start+11).':C'.($this->largeTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30Start+4).':R'.($this->largeTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30Start+11).':R'.($this->largeTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30End-8).':R'.($this->largeTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30End-3).':R'.($this->largeTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->largeTable16To30End-4).':R'.($this->largeTable16To30End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable1To15Start+11).':C'.($this->smallTable1To15Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15Start+4).':R'.($this->smallTable1To15Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15Start+11).':R'.($this->smallTable1To15Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15End-8).':R'.($this->smallTable1To15End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15End-3).':R'.($this->smallTable1To15End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable1To15End-4).':R'.($this->smallTable1To15End-4))->getNumberFormat()->setFormatCode('0%');

        $this->SchedC3UNSheet->getStyle('C'.($this->smallTable16To30Start+11).':C'.($this->smallTable16To30Start+10+$this->numComponents))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30Start+4).':R'.($this->smallTable16To30Start+9))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30Start+11).':R'.($this->smallTable16To30Start+11+$this->numComponents))->getNumberFormat()->setFormatCode('_-* "$"#,##0_-;[Red]\\-* "$"#,##0_-;_-* "-"??_-;_-@_-');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30End-8).':R'.($this->smallTable16To30End-5))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30End-3).':R'.($this->smallTable16To30End))->getNumberFormat()->setFormatCode('"$"#,##0;[Red]\\-"$"#,##0');
        $this->SchedC3UNSheet->getStyle('D'.($this->smallTable16To30End-4).':R'.($this->smallTable16To30End-4))->getNumberFormat()->setFormatCode('0%');

    }

    function getScheduleC3CFT()
    {
        self::setupScheduleC3CFT();
    }

    function setupScheduleC3CFT()
    {
        //TITLES OF TABLE 1
        $this->SchedC3CFTSheet->SetCellValue( 'A' . 1 , 'Strata:');
        $this->SchedC3CFTSheet->SetCellValue( 'C' . 1, '=\'Basic Info\'!$C$2');
        $this->SchedC3CFTSheet->SetCellValue( 'D' . 1, '  Schedule C.3 – UNFUNDED \'PAY AS YOU GO\' MODEL – 30 Year RESERVE FUND CASH FLOW TABLE');
        $this->SchedC3CFTSheet->SetCellValue( 'A' . 2, 'Fiscal Year End');
        $this->SchedC3CFTSheet->SetCellValue( 'A' . 3, '=\'Basic Info\'!C8');
        $this->SchedC3CFTSheet->SetCellValue( 'A' . 34, 'Totals over 30 years');
        $this->SchedC3CFTSheet->SetCellValue( 'A' . 35, 'Average per year over 30 years');
        $titlesArray = array(
            'Reserve Fund Opening Balance',
            'Recommended Annual RF Contributions',
            'Percentage Increase in RF Contributions',
            'Possible Special Levies',
            'Borrowings and Loan Financing',
            'Estimated Reserve Fund Interest Earned',
            'Estimated Inflation Adjusted Expenditures',
            'Reserve Fund Closing Balance',
            'Total Annual ASL Contributions and Special Levies',
            'Monthly ASL Contributions',
            'Annual ASL Possible Special Levies',
        );
        $i = 0;
        foreach( range('B', 'L') as $letter)
        {
            $this->SchedC3CFTSheet->SetCellValue( $letter . 2, $titlesArray[$i]);
            $i++;
        }

        //The INNER PORTION OF TABLE 1
        $columnsRange = range('A', 'G');
        $yearsRange = range('F','Z');
        foreach(range('A', 'I') as $letter)
        {
            array_push($yearsRange, 'A' . $letter);
        }
        $aids = array(12, 14, 15, 16, 17, 18);
        for ($i = 0; $i < 30; $i++)
        {
            if ($i != 0)
            {
                $this->SchedC3CFTSheet->SetCellValue('D' . ($i+4),'=(C' . ($i+4) . '/C' . ($i+3) . ')-100%');
            } else {
                $this->SchedC3CFTSheet->SetCellValue('D' . ($i+4),'Not Calculated');
            }
            $j = 0;
            foreach($columnsRange as $letter)
            {
                if($letter == 'D')
                    continue;
                $this->SchedC3CFTSheet->SetCellValue( $letter . ($i+4), '=\'Sched. C.3 UN\'!$' . $yearsRange[$i] . '$' .  $aids[$j]);
                $j++;
            }
            $this->SchedC3CFTSheet->SetCellValue( 'H' . ($i+4), '=\'Sched. C.3 UN\'!$' . $yearsRange[$i] . '$' . $this->totalExpendituresRow);
            $this->SchedC3CFTSheet->SetCellValue( 'I' . ($i+4), '=\'Sched. C.3 UN\'!$' . $yearsRange[$i] . '$' . ($this->totalExpendituresRow + 1));
            $this->SchedC3CFTSheet->SetCellValue( 'J' . ($i+4),'=(C' . ($i+4) . '+E' . ($i+4) .')/\'Basic Info\'!$C$3');
            $this->SchedC3CFTSheet->SetCellValue( 'K' . ($i+4),'=C' . ($i+4) . '/\'Basic Info\'!$C$3/12');
            $this->SchedC3CFTSheet->SetCellValue( 'L' . ($i+4),'=E' . ($i+4) . '/\'Basic Info\'!$C$3');
        }
        //SUMS AT BOTTOM
        $this->SchedC3CFTSheet->SetCellValue( 'C' . 34, '=SUM(C4:C33)');
        $this->SchedC3CFTSheet->SetCellValue( 'E' . 34, '=SUM(E4:E33)');
        $this->SchedC3CFTSheet->SetCellValue( 'G' . 34, '=SUM(G4:G33)');

        $this->SchedC3CFTSheet->SetCellValue( 'H' . 34, '=SUM(H4:H33)');
        $this->SchedC3CFTSheet->SetCellValue( 'J' . 34, '=SUM(J4:J33)');
        $this->SchedC3CFTSheet->SetCellValue( 'L' . 34, '=SUM(L4:L33)');

        $this->SchedC3CFTSheet->SetCellValue( 'J' . 35, '=AVERAGE(J4:J33)');
        $this->SchedC3CFTSheet->SetCellValue( 'K' . 35, '=AVERAGE(K4:K33)');
        $this->SchedC3CFTSheet->SetCellValue( 'L' . 35, '=AVERAGE(L4:L33)');
    }

    function save()
    {
        $fileName = ( 'Docs/testExcel.xlsx');
        $objWriter = new PHPExcel_Writer_Excel2007($this->PHPExcel);
        $objWriter->save($fileName);
        
        return $fileName;
    }
    
}