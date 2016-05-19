<?php
//page 1 is the cover
//page 2, 3 is the letter
//page 4 TOC and so on
//from TOC to the end, you can see the page number
$aux = array();
//AnnualReserveFundContributions is an array of array in onder of the year.
//On the report is 51000 but the correct is what is on the excel.
$aux['AnnualReserveFundContributions'][0] = array('year'=>2014, 'value'=>2400);     // sched c1 th,   F15
$aux['AnnualReserveFundContributions'][1] = array('year'=>2015, 'value'=>2640);     // sched c1 th,   G15
$aux['AnnualReserveFundContributions'][2] = array('year'=>2020, 'value'=>51961);    // sched c1 th,   L19
//pg 6
$aux['ReserveFundGroups'][0] = array('name'=>'Site Improvements Reserve Components', 'total'=>10); // i think it on the loop to get the compoenents.. u have a cout for it if iremember.
$aux['ReserveFundGroups'][1] = array('name'=>'Consultant Report', 'total'=>1); // can be any number of level1 total, name. COUNT for each level1 id 
$aux['Year1ReserveAdequacy']        = "37";       // basic info,    C17
$aux['Year30ReserveAdequacy']       = "60";       // basic info,    C18
$aux['CurrentReplacementCost']      = "162713";   // sched A,    J16
$aux['FutureReplacementCost']       = "501654";   // sched A,    K16
$aux['CurrentReserveFundCostReq']   = "63643";    // sched A,    L16
$aux['FutureReserveFundAcc']        = "95298";    // sched A,    M16
$aux['FutureReserveFundReq']        = "406356";   // sched A,    N16
$aux['ReserveFundAnnualCon']        = "10218";    // sched A,    O16
//pg 16
$aux['ReserveFundClosingBalance'][0] = array('date'=>'2013-12-31', 'value'=>22027); // sched c1 th,     F15
$aux['RecommendedAnnualRFContr'] = "1500";                                          // sched C1 CFT,    C4
$aux['ReserveAdequacy'] = "37";                                                     // sched C1 CFT,    F41
$aux['MonthlyASLContributions'][0] = array('year'=>'2014', 'value'=>20.00); // ? C1 TH
$aux['MonthlyASLContributions'][1] = array('year'=>'2019', 'value'=>32.21); // ?
//pg65
$aux['MonthlyASLContributions'][2] = array('year'=>'2015', 'value'=>22.00); // ?
//pg 60
$aux['OpeningBalanceDate'] = "2014-01-01";
$aux['OpeningBalanceValue'] = "22027";
$aux['CurrentBudgetedAnnualRFC'] = "2400";
$aux['AuthorizedSpecialLeveies'] = "0";
$aux['Borrowings'] = "0";
$aux['LoanRefinance'] = "0";
$aux['ReserveFundTaxFreeAnnualIntIncome'] = "330";
$aux['LessRepaymentOfFinancingLoan'] = "0";
$aux['LessReserveFundBudgetCurrentFYear'] = "-1575";
$aux['ProjectedReserveFundBalanceDate'] = "2014-12-31";
$aux['ProjectedReserveFundBalanceValue'] = "23182";
$aux['EstimatedReserveFund_Shortfall'] = "62068";
$aux['BudgetTransferFromDate'] = "2015-01-01";
$aux['BudgetTransferFromValue'] = "6000";
$aux['ProposedSpecialLeveiesDate'] = "2015-01-01";
$aux['ProposedSpecialLeveiesValue'] = "33468";
$aux['EstimatedReserveFundAdequacy'] = "100";
//pg62
$aux['EstimatedReserveFundDeficiency'] = "38885";
$aux['ReserveAdequacyDate'] = "2014-12-31";
$aux['ReserveAdequacyValue'] = "37";
