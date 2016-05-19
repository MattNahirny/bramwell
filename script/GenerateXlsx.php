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
        $this->basicInfoQuery = '';
        $this->allComponents = array();


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

        $this->aux = array();

        // sched c1 th,   F15
        $this->aux['AnnualReserveFundContributions'][0] = array('year'=>2014, 'value'=>2400);
        // sched c1 th,   G15
        $this->aux['AnnualReserveFundContributions'][1] = array('year'=>2015, 'value'=>2640);
        // sched c1 th,   L19
        $this->aux['AnnualReserveFundContributions'][2] = array('year'=>2020, 'value'=>51961);
//pg 6
        // i think it on the loop to get the compoenents.. u have a cout for it if iremember.
        $this->aux['ReserveFundGroups'][0] = array('name'=>'Site Improvements Reserve Components', 'total'=>10);
        // can be any number of level1 total, name. COUNT for each level1 id
        $this->aux['ReserveFundGroups'][1] = array('name'=>'Consultant Report', 'total'=>1);
        // basic info,    C17
        $this->aux['Year1ReserveAdequacy']        = "37";
        // basic info,    C18
        $this->aux['Year30ReserveAdequacy']       = "60";
        // sched A,    J16
        $this->aux['CurrentReplacementCost']      = "162713";
        // sched A,    K16
        $this->aux['FutureReplacementCost']       = "501654";
        // sched A,    L16
        $this->aux['CurrentReserveFundCostReq']   = "63643";
        // sched A,    M16
        $this->aux['FutureReserveFundAcc']        = "95298";
        // sched A,    N16
        $this->aux['FutureReserveFundReq']        = "406356";
        // sched A,    O16
        $this->aux['ReserveFundAnnualCon']        = "10218";
//pg 16
        // sched c1 th,     F15
        $this->aux['ReserveFundClosingBalance'][0] = array('date'=>'2013-12-31', 'value'=>22027);
        // sched C1 CFT,    C4
        $this->aux['RecommendedAnnualRFContr'] = "1500";
        // sched C1 CFT,    F41
        $this->aux['ReserveAdequacy'] = "37";
        // ? C1 TH
        $this->aux['MonthlyASLContributions'][0] = array('year'=>'2014', 'value'=>20.00);
        // ?
        $this->aux['MonthlyASLContributions'][1] = array('year'=>'2019', 'value'=>32.21);
//pg65
        // ?
        $this->aux['MonthlyASLContributions'][2] = array('year'=>'2015', 'value'=>22.00);
//pg 60

        $this->aux['OpeningBalanceDate'] = "2014-01-01";

        $this->aux['OpeningBalanceValue'] = "22027";

        $this->aux['CurrentBudgetedAnnualRFC'] = "2400";

        $this->aux['AuthorizedSpecialLeveies'] = "0";

        $this->aux['Borrowings'] = "0";

        $this->aux['LoanRefinance'] = "0";

        $this->aux['ReserveFundTaxFreeAnnualIntIncome'] = "330";

        $this->aux['LessRepaymentOfFinancingLoan'] = "0";

        $this->aux['LessReserveFundBudgetCurrentFYear'] = "-1575";

        $this->aux['ProjectedReserveFundBalanceDate'] = "2014-12-31";

        $this->aux['ProjectedReserveFundBalanceValue'] = "23182";

        $this->aux['EstimatedReserveFund_Shortfall'] = "62068";

        $this->aux['BudgetTransferFromDate'] = "2015-01-01";

        $this->aux['BudgetTransferFromValue'] = "6000";

        $this->aux['ProposedSpecialLeveiesDate'] = "2015-01-01";

        $this->aux['ProposedSpecialLeveiesValue'] = "33468";

        $this->aux['EstimatedReserveFundAdequacy'] = "100";
//pg62

        $this->aux['EstimatedReserveFundDeficiency'] = "38885";

        $this->aux['ReserveAdequacyDate'] = "2014-12-31";

        $this->aux['ReserveAdequacyValue'] = "37";


























    }

    function dummp($data) {
        echo '<pre>' . var_export($data, true) . '</pre>';exit;
    }

    function run()
    {
        $this->setup();
        $this->getBasicInfo();
        $this->getTally();
        $this->getComparison();
        $this->getScheduleA();
        $this->getScheduleC1TH();
        $this->save();
    }

    function setup()
    {
        //CREATE all sheets
        $this->BasicInfoSheet = $this->PHPExcel->createSheet();
        $this->BasicInfoSheet->setTitle("Basic Info");

        $this->TallySheet = $this->PHPExcel->createSheet();
        $this->TallySheet->setTitle("Tally");

        $this->ComparisonSheet = $this->PHPExcel->createSheet();
        $this->ComparisonSheet->setTitle("Comparison");

        $this->TenYearsSheet = $this->PHPExcel->createSheet();
        $this->TenYearsSheet->setTitle("10 Years");

        $this->SchedASheet = $this->PHPExcel->createSheet();
        $this->SchedASheet->setTitle("Sched. A");

        $this->SchedBSheet = $this->PHPExcel->createSheet();
        $this->SchedBSheet->setTitle("Sched. B");

        $this->SchedC1THSheet = $this->PHPExcel->createSheet();
        $this->SchedC1THSheet->setTitle("Sched. C.1 TH");

        $this->SchedC1CTFSheet = $this->PHPExcel->createSheet();
        $this->SchedC1CTFSheet->setTitle("Sched. C.1 CTF");

        $this->SchedC2FFSheet = $this->PHPExcel->createSheet();
        $this->SchedC2FFSheet->setTitle("Sched. C.2 FF");

        $this->SchedC2CFTSheet = $this->PHPExcel->createSheet();
        $this->SchedC2CFTSheet->setTitle("Sched. C.2 CTF");

        $this->SchedC3UNSheet = $this->PHPExcel->createSheet();
        $this->SchedC3UNSheet->setTitle("Sched. C.3 UN");

        $this->SchedC3CFTSheet = $this->PHPExcel->createSheet();
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
            $r['C1THYr1RA'] = 0;
            $r['C1THYr30RA'] = 0;
            $r['MaxRFContribution'] = 10000;
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
        $this->BasicInfoSheet->SetCellValue('C17', $dataRows['C1THYr1RA']);
        $this->BasicInfoSheet->SetCellValue('C18', $dataRows['C1THYr30RA']);
        $this->BasicInfoSheet->SetCellValue('C19', $dataRows['MaxRFContribution']);

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
            'C1 and C.2 Year 1 Proposed RF Contributions Amount:',
            'Construction Inflation rate:',
            'Strata Corporation Sched. B Average Interest Rate:',
            'Investment Interest Rate:',
            'Strata Corporation C.1 Threshold Year 1 Reserve Adequacy',
            'Strata Corporation C.1 Threshold Year 30 Reserve Adequacy',
            'Maximum Reserve Fund Contributions');

        $columnA_2 = array(
            'Total Strata Fees',
            'Operating Budget',
            'Reserve Fund Opening Balance',
            'Reserve Fund Closing Balance',
            'Reserve Fund Annual Contributions',
            'Transfer to or (from)  Reserve Fund',
            'Interest earned during period',
            'Interest % earned during period',
            'Special Levies',
            'Borrowings');

        $this->BasicInfoSheet->SetCellValue('A1', 'Property Details');

        for ($i = 2; $i < 19; $i++)
        {
            $this->BasicInfoSheet->SetCellValue('A'. $i, $columnA_1[$i-2]);
            $this->BasicInfoSheet->mergeCells('A' . $i . ':B' . $i);
        }

        $this->BasicInfoSheet->SetCellValue('A20', 'Financial Information');
        for ($i = 22; $i < 32; $i++)
        {
            $this->BasicInfoSheet->SetCellValue('A'. $i, $columnA_2[$i-22]);
        }

        $this->BasicInfoSheet->getColumnDimension("A")->setWidth(44);
        $this->BasicInfoSheet->getColumnDimension("B")->setWidth(11);
        $this->BasicInfoSheet->getColumnDimension("C")->setWidth(8);


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
            '=\'Basic Info\'!$D$23',
            '=\'Sched. A\'!$L$59',
            '=\'Sched. C.1 TH\'!F78',
            '=\'Sched. C.1 TH\'!F14',
            '=\'Sched. C.1 TH\'!E71',
            '=\'Sched. C.1 TH\'!E15',
            '=\'Sched. C.1 TH\'!E16',
            '=\'Sched. C.1 TH\'!E18',
            '=\'Sched. C.1 TH\'!AI74',
            '=\'Sched. C.1 TH\'!AI78',
            '=\'Sched. C.1 TH\'!E17',
            '=C9+C11+C10',
            '=C9/C15',
            '=C11/C15',
            '=C10/C15',
            '=C14/C15',
            '=COUNTIF(\'Sched. C.1 TH\'!F16:AI16,">0")',
            '=\'Sched. C.1 TH\'!AI19',
            '=\'Sched. C.1 TH\'!AI76',
            '=\'Sched. C.1 TH\'!F79',
            '=\'Sched. C.1 TH\'!AI79',

        );

        $columnD_1 = array(
            'Schedule C.2  –  FULLY FUNDED MODEL',
            'FULLY FUNDED MODEL',
            '=\'Basic Info\'!$D$23',
            '=\'Sched. A\'!$L$59',
            '=\'Sched. C.2 FF\'!F73',
            '=\'Sched. C.2 FF\'!F10',
            '=\'Sched. C.2 FF\'!E67',
            '=\'Sched. C.2 FF\'!E11',
            '=\'Sched. C.2 FF\'!E12',
            '=\'Sched. C.2 FF\'!E14',
            '=\'Sched. C.2 FF\'!AI70',
            '=\'Sched. C.2 FF\'!AI73',
            '=\'Sched. C.2 FF\'!E13',
            '=D9+D11+D10',
            '=D9/D15',
            '=D11/D15',
            '=D10/D15',
            '=D14/D15',
            '=COUNTIF(\'Sched. C.2 FF\'!F12:AI12,">0")',
            '=\'Sched. C.2 FF\'!AI15',
            '=\'Sched. C.2 FF\'!R211',
            '=\'Sched. C.2 FF\'!F74',
            '=\'Sched. C.2 FF\'!AI74',
        );

        $columnE_1 = array(
            'Schedule C.3 –  UNFUNDED \'PAY AS YOU GO\' MODEL',
            'EXISTING MODEL',
            '=\'Basic Info\'!$D$23',
            '=\'Sched. A\'!$L$59',
            '=\'Sched. C.3  UN\'!F78',
            '=\'Sched. C.3  UN\'!F14',
            '=\'Sched. C.3  UN\'!E71',
            '=\'Sched. C.3  UN\'!E15',
            '=\'Sched. C.3  UN\'!E16',
            '=\'Sched. C.3  UN\'!E18',
            '=\'Sched. C.3  UN\'!AI74',
            '=\'Sched. C.3  UN\'!AI78',
            '=\'Sched. C.3  UN\'!E17',
            '=E9+E11+E10',
            '=E9/E15',
            '=E11/E15',
            '=E10/E15',
            '=E14/E15',
            '=COUNTIF(\'Sched. C.3  UN\'!F16:AI16,">0")',
            '=\'Sched. C.3  UN\'!AI19',
            '=\'Sched. C.3  UN\'!AI76',
            '=\'Sched. C.3  UN\'!F79',
            '=\'Sched. C.3  UN\'!AI79'
        );

        $this->ComparisonSheet->SetCellValue('A1', 'Scenario Result Comparisons');
        for ($i = 4; $i < 25; $i++)
        {
            $this->ComparisonSheet->SetCellValue('A'. $i, $columnA_1[$i-4]);
            //->BasicInfoSheet->mergeCells('A' . $i . ':B' . $i);
        }

        for ($i = 2; $i < 25; $i++)
        {
            $this->ComparisonSheet->SetCellValue('C'. $i, $columnC_1[$i-2]);
            //->BasicInfoSheet->mergeCells('A' . $i . ':B' . $i);
        }

        for ($i = 2; $i < 25; $i++)
        {
            $this->ComparisonSheet->SetCellValue('D'. $i, $columnD_1[$i-2]);
            //->BasicInfoSheet->mergeCells('A' . $i . ':B' . $i);
        }

        for ($i = 2; $i < 25; $i++)
        {
            $this->ComparisonSheet->SetCellValue('E'. $i, $columnE_1[$i-2]);
            //->BasicInfoSheet->mergeCells('A' . $i . ':B' . $i);
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
        foreach ($this->allTallyData as $index => $array) {
            if ($levelone != $array['l1Name']) {
                $levelone = $array['l1Name'];
                $this->SchedASheet->SetCellValue('A' . $curRow, $levelone);
                $curRow++;
            } else {
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
                $this->SchedASheet->SetCellValue('P' . $curRow, '=O'  . $curRow . '/$O$16');

                $curRow++;
            }
            $this->TallySheet->calculateColumnWidths();
        }
        $totalsRow = $curRow;
        $this->schedATotalsRow = $curRow;
        $this->SchedASheet->SetCellValue('A' . $curRow, 'TOTAL RESERVES');
        $this->SchedASheet->SetCellValue('J' . $curRow, '=SUM(J3:J' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('K' . $curRow, '=SUM(K3:K' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('L' . $curRow, '=SUM(L3:L' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('M' . $curRow, '=SUM(M3:M' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('N' . $curRow, '=SUM(N3:N' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('O' . $curRow, '=SUM(O3:O' . ($curRow - 1) . ')');
        $this->SchedASheet->SetCellValue('P' . $curRow, '=SUM(P3:P' . ($curRow - 1) . ')');
        $cursor = $curRow;
        $importantinfo = array(
            array('A' => 'Current Replacement Costs:','C' => '=J' . $totalsRow),
            array('A' => 'Future Replacement Costs:',	'C' => '=K' . $totalsRow),
            array('A' => 'Current Reserve Fund Requirements:', 'C' => '=L' . $totalsRow),
            array('A' => 'Future Reserve Fund Accumulations:', 'C' => '=M' . $totalsRow),
            array('A' => 'Future Reserve Fund Requirements:',	'C' => '=N' . $totalsRow),
            array('A' => 'Fully Funded Annual Reserve Fund Constributions:', 'C' => '=' . $totalsRow)
        );


        for($i = (5 + $curRow); $i < (10 + $curRow); $i++)
        {
            //echo 'hello' . $i . $importantinfo[($i - 5 - $curRow)]['A'] . $importantinfo[($i - 5 - $curRow)]['C'] . '<br>';
            $this->SchedASheet->SetCellValue('A' . $i, $importantinfo[($i - 5 - $curRow)]['A']);
            $this->SchedASheet->SetCellValue('C' . $i, $importantinfo[($i - 5 - $curRow)]['C']);
            $cursor = $i;
        }

        $benchmark = array(

            array('A' => 'Benchmark Future Replacement Cost', 'E' => '=K16'),
            array('A' => 'Total Expenditures without allowances over 30 years', 'E' => '=\'Sched. C.1 TH\'!E33'),
            array('A' => 'Year 1 Total Allowances', 'E' => '=Q16'),
            array('A' => 'Total Allowances over Future Replacement Cost', 'E' => '=Q16/K16'),
            array('A' => 'Percent Allowances over Current Replacement Cost', 'E' => '=Q16/L16'),
            array('A' => 'If Development Began today per year RF Recommended Contributions per average lot:', 'E' => '=O16/\'Basic Info\'!C3'),
            array('A' => 'If Development began today per month RF Contributions per average lot:', 'E' => '=E32/12'),
            array('A' => 'Current per month RF Contributions per average lot:', 'E' => '=(\'Basic Info\'!D26/\'Basic Info\'!C3)/12'),
            array('A' => 'Annual Expenditures Increase = (Future Replacement Cost/(Current Replacement Cost - 1)/30):', 'E' => '=(K16/J16-1)/30')


        );

        $this->SchedASheet->SetCellValue('A' . ($cursor + 1), 'BENCHMARK FULL FUNDING ANALYSIS');
        $cursor++;

        for($i = 0; $i < 9; $i++)
        {
            $this->SchedASheet->SetCellValue('A' . $cursor, $benchmark[$i]['A']);
            $this->SchedASheet->SetCellValue('A' . $cursor, $benchmark[$i]['E']);
            $cursor++;
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

        foreach($cellRange as $letter)
        {
            $this->SchedASheet->getStyle($letter . '2')->getAlignment()->setWrapText(true);
        }


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
        //setup Titles starting at row 12
        $this->SchedC1THSheet->SetCellValue('C12', 'First Occurrence');
        $this->SchedC1THSheet->SetCellValue('D12', 'Repeat Every');
        $this->SchedC1THSheet->SetCellValue('E12', 'Totals at Year 30 without Allowances');
        $cellRange1 = range('G', 'Z');
        $cellRange2 = range('B', 'I');
        $curletter = 'F';
        $this->SchedC1THSheet->SetCellValue('F12', '=\'Basic Info\'!C7+1');

        foreach($cellRange1 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue($letter . 12, '=' . $curletter . '12 + 1');

            $curletter = $letter;
        }
        $this->SchedC1THSheet->SetCellValue('AA12', '=Z12 + 1');
        $curletter = 'A';
        foreach($cellRange2 as $letter)
        {
            //echo 'A' . $letter . 12, '=A' . $curletter . '12 + 1';exit;
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 12, '=A' . $curletter . '12 + 1');
            $curletter = $letter;
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
        $totalExpendituresRow = $cursor;
        $cursor += 2;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Fund Closing Balance');
        $RFClosingBalanceRow = $cursor;
        $cursor += 2;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Fund Requirements');
        $RFRequirementseRow = $cursor;
        $cursor += 2;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Surplus (Deficiency)');
        $reserveSurplusDeficiencyRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Reserve Adequacy');
        $reserveAdequacy = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Monthly ASL Contributions');
        $monthlyASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Annual ASL Contributions');
        $annualASLContributionsRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Annual ASL Possible Special Levies');
        $annualPossibleSpecialLeviesRow = $cursor;
        $cursor++;
        $this->SchedC1THSheet->SetCellValue('A' . $cursor, 'Total Annual ASL Contributions and Special Levies');
        $totalASLContributionsSpecialLeviesRow = $cursor;
//From E14 to AI19
        //COLUMN F
        $this->SchedC1THSheet->SetCellValue('E15', '=SUM(F15:AI15)');
        $this->SchedC1THSheet->SetCellValue('E16', '=SUM(F16:AI16)');
        $this->SchedC1THSheet->SetCellValue('E17', '=SUM(F17:AI17)');
        $this->SchedC1THSheet->SetCellValue('E18', '=SUM(F18:AI18)');

        $this->SchedC1THSheet->SetCellValue('F14', '36933'); //THESE HAVE TO BE ENTERED BY USER
        $this->SchedC1THSheet->SetCellValue('F15', '5546'); // THESE HAVE TO BE ENTERED BY USER OR MOST LIKELY PUT INTO BASICINFO
        $this->SchedC1THSheet->SetCellValue('F16', '0');
        $this->SchedC1THSheet->SetCellValue('F17', '0');
        $this->SchedC1THSheet->SetCellValue('F18', '=+F14*\'Basic Info\'!$C$16');
        $this->SchedC1THSheet->SetCellValue('F19', '=+F14+F15+F16+F18');
        $curletter = 'F';
        $maxRFContribution = 10000;
        //COLUMN G-Z
        foreach($cellRange1 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue($letter . 14, '=+' . $curletter . $RFClosingBalanceRow);
            //THERE IS A MAXIMUM ANNUAL RESERVE FUND CONTRIBUTION AMOUNT THAT MUST BE SET
            $this->SchedC1THSheet->SetCellValue($letter . 15, '=IF((100%+\'Basic Info\'!$C$11)*' . $curletter . '15 <= \'Basic Info\'!$C$19,(100%+\'Basic Info\'!$C$11)*' . $curletter . '15,\'Basic Info\'!$C$19)'); // ANNUAL RESERVE FUND CONTRIBUTIONS MUST BE HERE
            $this->SchedC1THSheet->SetCellValue($letter . 16, '0');
            $this->SchedC1THSheet->SetCellValue($letter . 17, '0');
            $this->SchedC1THSheet->SetCellValue($letter . 18, '=+' . $curletter . '14*\'Basic Info\'!$C$16');
            $this->SchedC1THSheet->SetCellValue($letter . 19, '=+' . $curletter . '14+' . $curletter . '15+' . $curletter . '16+' . $curletter . '18');

            $curletter = $letter;
        }
        //COLUMN AA
        $this->SchedC1THSheet->SetCellValue('AA14', '=+Z' . $RFClosingBalanceRow);
        $this->SchedC1THSheet->SetCellValue('AA15', '=IF((100%+\'Basic Info\'!$C$11)*' . $curletter . '15 <= \'Basic Info\'!$C$19,(100%+\'Basic Info\'!$C$11)*' . $curletter . '15,\'Basic Info\'!$C$19)');
        $this->SchedC1THSheet->SetCellValue('AA16', '');
        $this->SchedC1THSheet->SetCellValue('AA17', '0');
        $this->SchedC1THSheet->SetCellValue('AA18', '=+Z14*\'Basic Info\'!$C$16');
        $this->SchedC1THSheet->SetCellValue('AA19', '=+AA14+AA15+AA16+AA18');
        $curletter = 'A';
        //COLUMN AB-AI
        foreach($cellRange2 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 14, '=+A' . $curletter . $RFClosingBalanceRow);
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 15, '=IF((100%+\'Basic Info\'!$C$11)*A' . $curletter . '15 <= \'Basic Info\'!$C$19,(100%+\'Basic Info\'!$C$11)*A' . $curletter . '15,\'Basic Info\'!$C$19)');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 16, '0');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 17, '0');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 18, '=+A' . $curletter . '14*\'Basic Info\'!$C$16');
            $this->SchedC1THSheet->SetCellValue('A' . $letter . 19, '=+A' . $curletter . '14+A' . $curletter . '15+A' . $curletter . '16+A' . $curletter . '18');

            $curletter = $letter;
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
        $this->SchedC1THSheet->SetCellValue('E' . ($totalExpendituresRow - 1), '=SUM(E22:E' . $lastComponentRow . ')');
        foreach ($cellRange3 as $letter)
        {
            $this->SchedC1THSheet->SetCellValue( $letter . $totalExpendituresRow, '=SUM('. $letter . 22 . ':' . $letter . $lastComponentRow . ')');
        }
        $this->SchedC1THSheet->SetCellValue( $letter . $RFClosingBalanceRow, '=+' . $letter . '19-' . $letter . $lastComponentRow);
        //L59 needs to be set up properly for variable amounts of components
        $this->SchedC1THSheet->SetCellValue( 'F' . $RFRequirementseRow, '=\'Sched. A\'!L' . $this->schedATotalsRow . '-F' . $totalExpendituresRow);
        $lastCol = 'F';
        foreach($cellRange3 as $letter)
        {
            if ($letter == 'F')
                continue;
            $this->SchedC1THSheet->SetCellValue( $letter . $RFRequirementseRow, '=(+' . $lastCol . $RFRequirementseRow . '*(1+\'Basic Info\'!$C$16))+\'Sched. A\'!$O$' . $this->schedATotalsRow . '-' . $letter . $totalExpendituresRow);
            $lastCol = $letter;
        }

        //$this->SchedC1THSheet->SetCellValue( , );
        //$this->SchedC1THSheet->SetCellValue( , );
        //$this->SchedC1THSheet->SetCellValue( , );
        //$this->SchedC1THSheet->SetCellValue( , );
        //$this->SchedC1THSheet->SetCellValue( , );
        //$this->SchedC1THSheet->SetCellValue( , );











    }

    function getScheduleC1TF()
    {
        self::setupScheduleC1TF();

    }

    function setupScheduleC1TF()
    {

    }

    function getScheduleC2FF()
    {
        self::setupScheduleC2FF();
    }

    function setupScheduleC2FF()
    {

    }

    function getScheduleC2CFT()
    {
        self::setupScheduleC2CFT();
    }

    function setupScheduleC2CFT()
    {

    }

    function getScheduleC3UN()
    {
        self::setupScheduleC3UN();
    }

    function setupScheduleC3UN()
    {

    }

    function getScheduleC3CFT()
    {
        self::setupScheduleC3CFT();
    }

    function setupScheduleC3CFT()
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