-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 198.71.227.98:3306
-- Generation Time: Apr 27, 2016 at 12:56 PM
-- Server version: 5.5.43-37.2-log
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bramwell`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `ClientId` int(11) NOT NULL,
  `PMFirstName` varchar(45) NOT NULL,
  `PMLastName` varchar(45) NOT NULL,
  `CompanyName` varchar(45) NOT NULL,
  `City` varchar(45) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`ClientId`, `PMFirstName`, `PMLastName`, `CompanyName`, `City`, `Address`) VALUES
(1, 'Self', 'Administered', 'Self', 'Example City', 'Example Address'),
(4, 'Example First Name', 'Example Last Name', 'Example Company Name', 'Example Long City', 'Example Long Address '),
(5, 'Example First Name', 'Example Last Name', 'Example Long Company Name', 'Example Very Long City', 'Example Very Long Address '),
(6, 'First Name', 'Last Name', 'Company Name', 'City', 'Address'),
(9, 'Ron', 'Hall', 'Strata Corporation LMS 667 - Ocean Crest', 'Surrey', '12985 15th Avenue');

-- --------------------------------------------------------

--
-- Table structure for table `componentpicture`
--

CREATE TABLE `componentpicture` (
  `PictureId` int(11) NOT NULL,
  `PlanId` int(11) NOT NULL,
  `LevelFourId` int(11) NOT NULL,
  `PictureURI` varchar(45) NOT NULL,
  `Caption` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `componentpicture`
--

INSERT INTO `componentpicture` (`PictureId`, `PlanId`, `LevelFourId`, `PictureURI`, `Caption`) VALUES
(24, 1, 3, 'uploads/report1/comp3img1.jpg', '  Figure : Stamped concrete surface.'),
(23, 1, 2, 'uploads/report1/comp2img2.jpg', '  Figure : Asphalt surface.'),
(22, 1, 2, 'uploads/report1/comp2img1.jpg', '  Figure : Asphalt roadway.'),
(20, 1, 1, 'uploads/report1/comp1img1.jpg', '  Figure 1: Ashphalt cul-de-sac.'),
(21, 1, 1, 'uploads/report1/comp1img2.jpg', '  Figure : Junction or transition between concrete rollover curbs and asphalt blacktop surface.'),
(25, 1, 3, 'uploads/report1/comp3img2.jpg', '  Figure : Moss growth in stamped concrete grooving.'),
(26, 1, 3, 'uploads/report1/comp3img3.jpg', 'Figure 7: Uneven edge transition between the stamped concrete and the asphalt.'),
(27, 1, 3, 'uploads/report1/comp3img4.jpg', '  Figure : Stamped concrete inset pattern detail.'),
(28, 1, 3, 'uploads/report1/comp3img5.jpg', '  Figure : Concrete cracking in the perimeter portion.'),
(29, 1, 3, 'uploads/report1/comp3img6.jpg', '  Figure : Junction between stamped concrete and concrete rollover curbs.'),
(30, 1, 4, 'uploads/report1/comp4img1.jpg', 'Figure : Chipped concrete curb.'),
(31, 1, 4, 'uploads/report1/comp4img2.jpg', 'Figure : Extended view of concrete curb and transition to asphalt roadway as well as an exposed  aggregate concrete driveway.'),
(32, 1, 4, 'uploads/report1/comp4img3.jpg', 'Figure : Curved internal curbing'),
(33, 1, 4, 'uploads/report1/comp4img4.jpg', '  Figure : Curved convex curbs facing out from the cul-de-sac at the entrance.'),
(34, 1, 5, 'uploads/report1/comp5img1.jpg', 'Figure : Internal standard street light'),
(35, 1, 6, 'uploads/report1/comp6img1.jpg', 'Figure : Safety wall around transformer'),
(36, 1, 6, 'uploads/report1/comp6img2.jpg', 'Figure : Close-up of transformer block wall with mortar at joints.'),
(37, 1, 6, 'uploads/report1/comp6img3.jpg', 'Figure 18: Aerial view of cracking mortar inside the transformer enclosure'),
(38, 1, 6, 'uploads/report1/comp6img4.jpg', 'Figure 19: Transformer enclosure base course.'),
(39, 1, 7, 'uploads/report1/comp7img1.png', '  Figure : Southwest view into the cul-de-sac towards the monument'),
(40, 1, 7, 'uploads/report1/comp7img2.jpg', 'Figure : Brick wall cap detailing on monument.'),
(41, 1, 7, 'uploads/report1/comp7img3.jpg', '  Figure : Moss growth in the monument.'),
(42, 1, 7, 'uploads/report1/comp7img4.jpg', 'Figure : Square pillar at the end of the monument.'),
(43, 27, 7, 'uploads/report27/streetlight.jpg', 'Street lights in the complex.'),
(44, 27, 7, 'uploads/report27/george_statue.jpg', 'The monument.'),
(45, 28, 15, 'uploads/report28/SRP corner logo.jpg', 'Eater of corpses'),
(46, 1, 16, 'uploads/report1/SRP corner logo.jpg', 'image caption');

-- --------------------------------------------------------

--
-- Table structure for table `constructioninfo`
--

CREATE TABLE `constructioninfo` (
  `ConstructInfoId` int(11) NOT NULL,
  `InfoType` varchar(25) NOT NULL,
  `BuildingType` varchar(25) NOT NULL,
  `Comment` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `constructioninfo`
--

INSERT INTO `constructioninfo` (`ConstructInfoId`, `InfoType`, `BuildingType`, `Comment`) VALUES
(32, 'foundations', 'all', 'Second option for testing and demo.'),
(31, 'services', 'all', 'A basic entry that describes any type of strata lot.'),
(30, 'electrical', 'all', 'A basic entry that describes any type of strata lot.'),
(29, 'amenities', 'all', 'A basic entry that describes any type of strata lot.'),
(28, 'roof', 'all', 'A basic entry that describes any type of strata lot.'),
(25, 'substructure', 'all', 'A basic entry that describes any type of Substructure.'),
(26, 'foundations', 'all', 'A basic entry that describes any type of strata lot.'),
(27, 'exterior', 'all', 'A basic entry that describes any type of strata lot.'),
(24, 'overview', 'all', 'A basic entry that describes any type of strata lot.');

-- --------------------------------------------------------

--
-- Table structure for table `dateinspected`
--

CREATE TABLE `dateinspected` (
  `DateInspectedId` int(11) NOT NULL,
  `PlanId` int(11) NOT NULL,
  `Date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dateinspected`
--

INSERT INTO `dateinspected` (`DateInspectedId`, `PlanId`, `Date`) VALUES
(148, 29, '2014-02-07'),
(145, 28, '2015-12-04'),
(144, 27, '2015-11-14'),
(140, 26, '2015-12-03'),
(152, 1, '2014-03-29');

-- --------------------------------------------------------

--
-- Table structure for table `incompletecomponents`
--

CREATE TABLE `incompletecomponents` (
  `PlanId` int(11) NOT NULL,
  `LevelFourId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `incompletecomponents`
--

INSERT INTO `incompletecomponents` (`PlanId`, `LevelFourId`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 9),
(1, 10),
(1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `inspectedby`
--

CREATE TABLE `inspectedby` (
  `PlanId` int(11) NOT NULL,
  `InspectorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inspectedby`
--

INSERT INTO `inspectedby` (`PlanId`, `InspectorId`) VALUES
(26, 1),
(27, 1),
(29, 2),
(1, 7),
(28, 7),
(29, 7);

-- --------------------------------------------------------

--
-- Table structure for table `inspector`
--

CREATE TABLE `inspector` (
  `InspectorId` int(11) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Phone` varchar(25) NOT NULL,
  `Cell` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inspector`
--

INSERT INTO `inspector` (`InspectorId`, `FirstName`, `LastName`, `Email`, `Phone`, `Cell`) VALUES
(1, 'Tom', 'Thomson', 'Inspectoremail@bramwell.com', '012-345-6789', '1- 800-123-4567'),
(2, 'Joe', 'Joeson', 'longInspectoremail@bramwell.com', '1- 800-123-4567', '012-345-6789'),
(7, 'Jeremy', 'Bramwell', 'JB@JB.JB', '1800STRATA5', '1800STRATA5');

-- --------------------------------------------------------

--
-- Table structure for table `levelfour`
--

CREATE TABLE `levelfour` (
  `LevelFourId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `LevelThreeId` int(11) NOT NULL,
  `DefPhysicalCondition` longtext NOT NULL,
  `DefFinancialAnalysis` longtext NOT NULL,
  `DefConditionAnalysis` longtext NOT NULL,
  `Cost` double DEFAULT NULL,
  `DefDeficiencyAnalysis` longtext NOT NULL,
  `ExpectedLifespan` int(11) DEFAULT NULL,
  `DefPotentialDeterioration` longtext NOT NULL,
  `UnitOfMeasure` varchar(45) NOT NULL,
  `Note` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levelfour`
--

INSERT INTO `levelfour` (`LevelFourId`, `Name`, `LevelThreeId`, `DefPhysicalCondition`, `DefFinancialAnalysis`, `DefConditionAnalysis`, `Cost`, `DefDeficiencyAnalysis`, `ExpectedLifespan`, `DefPotentialDeterioration`, `UnitOfMeasure`, `Note`) VALUES
(1, 'Asphalt Resurfacing', 1, 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 'The roadway was in good condition.', 3.34, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 25, 'The asphalt paved areas are susceptible to indentations from vehicles, especially from heavy vehicles turning on the hot asphalt surface. Ground settling, and ponding water may cause cracking and alligatoring as well.', 'squareFeet', NULL),
(2, 'Asphalt Replacement', 1, 'This component refers to the asphalt vehicular roadway and surface parking for the development, which is assumed to have a flexible basecoat and a hard top-coat continuous application over a bed of crushed rocks around storm drainage grates and grilles.', 'There were no known reported expenditures for this component since 1992.', 'The roadway was in good condition.', 12.5, 'We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 50, 'As asphalt is a by-product of crude oil, and refining has found ways to remove and use the volatiles out of crude oil, the quality of asphalt has decreased and additives such as polymers, latex, tire rubber have improved some of the asphalt qualities. As aggregates have different expansion characteristics than the asphalt, internal thermal expansion stresses deteriorate the asphalt. Water enters pavement from cracks, from edges from ground water. The soils under and at the edges of asphalt is affected by vegetationâ€™s moisture cycles as big tree rootsâ€™ moisture is drawn away and then allows water to be replaced when the rains occur thus causing soil expansion leading to cracks in the asphalt. Typical damage is cracking, alligator cracking, surface pumping, edge ravelling problems and vegetation in the field of pavement. ', 'squareFeet', NULL),
(3, 'Stamped Concrete', 2, 'This component refers to the stamped concrete area of the roadway at the entrance to the cul-de-sac. ', 'There were no known reported expenditures for this component since 1992.', 'We observed minimal damage to the surface, but some cracking.', 12.5, 'We noted minor plant and miss growth, as well as some cracks. Accelerated deterioration may lead to a faster re-stamping than anticipated.', 50, 'Stamped concrete is prone to deterioration from vehicular traffic and chemical damage. Like conventional concrete, stamped concrete will provide decades of service when properly installed and maintained, even when exposed to harsh winter weather. Adding steel reinforcement or wire mesh as well as fiberglass flakes augments the strength of the stamped concrete and helps to control cracking. Resealing the wear surface every few years â€“ or as needed to protect the surface from stains and maintain color vibrancy helps to meet the stamped concreteâ€™s lifespan.', 'squareFeet', NULL),
(4, 'Curb Replacement', 2, 'This component included broom finished concrete rollover curb sections around the perimeter of the roadway, as well as the gutter, for the drainage of water to the gutter.', 'This component has no known expenditures since 1992. ', 'The component concrete works appeared in good condition at this time with some curb breakage and wear surface cracks.', 32.58, 'We noted small amount of organic matter growth â€“ that requires power-washing and some settlement cracks. We noted that wear surface sealant does not seem to have been applied.', 50, 'The concrete sections are prone to settlement damage, to impact damage from machinery and vehicles and from exposure to the elements. They typically last longer if they are well maintained, powerwashed regularly, and sealed. They typically last longer than the asphalt roadway but are typically replaced concurrently.', 'linearFeet', NULL),
(5, 'Street Lights', 3, 'This component refers to the four (4) street lights in the cul de sac that are 20 feet in height and installed on concrete pads. A light timer installed behind the monument controls these lights.', 'This component has no known expenditures since 1992, other than a recent repair after a garbage truck damaged one street light post.  This was paid out of the operating fund, and reimbursed later. ', 'The majority of observed street lights were in good condition.', 2500, 'We recommend that the strata council research new bulb technologies that use much less energy.  It is assumed that the strata will periodically replace the bulbs and paint the poles as an expense from the operating fund.', 70, 'The fact that the majority of these fixtures are exposed to the elements indicated that their deterioration is accelerated and as such, their finishes should be monitored for evidence of paint peeling and coating damage as well as paths that insects and or water may follow which might lead to electrical wire damage and or short circuits.', 'unit', NULL),
(6, 'Transformer Enclosure', 4, 'This component describes the masonry block walls surrounding the BC Hydro transformer installed on site within the strata corporation property lines.', 'This component has no known expenditures since 1992. ', 'The component and its elements were in average condition.', 35, 'Some blocks were out of place. Some repointing of the mortar would help to maintain this component to ensure its expected potential lifespan is met.', 60, 'Inclement weather, freeze thaw cycles, improper installation or maintenance and impact damage are factors that drive deterioration of this component. Deterioration of the mortar can cause the structure to crack allowing vegetation egress.', 'squareFeet', NULL),
(7, 'Monument', 4, 'This component refers to the block and brick entry monument located at the vehicular entrance to the development. The monument is more than likely comprised of concrete blocks set on a concrete pad with mortared veneer.', 'This component has no known expenditures since 1992. ', 'While the structure appeared to be stable, the block surface appeared to have vegetative growth and little evidence of sealant.', 4000, 'No structural damage noted but the monument surfaces appear to be in need of cleaning and sealant.', 50, 'The monument is prone to damage from ground settling and damage from the elements to the wear surface and may require some re-mortaring.  ', 'unit', NULL),
(9, 'Underground Water Services', 6, 'This component refers to the water system connected to the municipalityâ€™s main water system and includes piping, connectors and ancillary equipment.', 'This component has no known reported expenditures since 1992.', 'As the water system was not visible we are not able to provide an opinion at this time. We observed that the system appeared to be functioning at the time of the site-visit.', 5000, 'None noted or reported.', 70, 'As the site services are under the frost line they typically last as long as the development unless damage occurs to the connections. We assume that the city had the responsibility of maintaining the latter.', 'system', NULL),
(10, 'Underground Sewer and Drainage Servies', 6, 'This component refers to the site services and include assumed-to-be cast iron sanitary and storm drainage systems typically installed parallel to each other and connected to the municipalityâ€™s storm and sanitary systems.', 'This component has no known reported expenditures since 1992. ', 'As the sanitary and storm drainage systems were not visible we are not able to provide an opinion at this time. We observed that the sanitary and sewage systems appeared to be functioning at the time of the site-visit.', 5000, 'None noted or reported.', 70, 'As the site services are under the frost line they typically last approx. as long as the development unless damage occurs to the connections. We assume that the city had the responsibility of maintaining the latter and that this has been done in the past.', 'system', NULL),
(11, 'Depreciation Report Updates', 7, 'This component reserves for future Depreciation Reports in the Reserve Fund rather than out of the Operating Fund. This component builds reserves on an annual basis for meeting the Strata Property Act mandate of renewing the Depreciation Report every three-year cycle. We are assuming at this time that all future reports will be updated reports although we may anticipate that a full report may be required in the future as legislative changes occur.', 'This component has no known expenditures since 1988. ', 'None.', 788, 'None.', 3, 'None.', 'unit', NULL),
(15, 'Light Bulbs', 3, 'This component covers all the lightbulbs in the plan. It does not cover special lighting fixtures, E.G a chandelier', 'This component has a low financial impact, as bulbs tend to last far past their expected lifespan', 'These lightbulbs appear to be in good shape.', 18.5, 'The recommended type of light bulb is in use at this location.', 7, 'The bulbs may burn out if left on for too long, or will simply wear out over time.', 'number', NULL),
(16, 'Electrical Distribution System', 10, 'This component refers to the underground electrical refers to the underground electrical distribution\nsystem to the strata corporation.', 'This component has no known expenditures since its acquisition.', 'One meter and sub-panel was observed and attached to the monument above ground.', 5000, 'None noted.', 70, 'The underground distribution system will tpyically last as long as the develoment unless damage occurs at the connections\ndue to settling or deterioration of the attachments.', 'system', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `levelone`
--

CREATE TABLE `levelone` (
  `LevelOneId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levelone`
--

INSERT INTO `levelone` (`LevelOneId`, `Name`) VALUES
(2, 'Consultant Reports'),
(8, 'default'),
(1, 'Site Improvement');

-- --------------------------------------------------------

--
-- Table structure for table `levelthree`
--

CREATE TABLE `levelthree` (
  `LevelThreeId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `LevelTwoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levelthree`
--

INSERT INTO `levelthree` (`LevelThreeId`, `Name`, `LevelTwoId`) VALUES
(1, 'Roadway', 1),
(2, 'Concrete', 1),
(3, 'Lighting', 1),
(4, 'Special', 1),
(6, 'Water', 1),
(7, 'Administrative', 2),
(8, 'default', 8),
(10, 'Electrical', 1);

-- --------------------------------------------------------

--
-- Table structure for table `leveltwo`
--

CREATE TABLE `leveltwo` (
  `LevelTwoId` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `LevelOneId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leveltwo`
--

INSERT INTO `leveltwo` (`LevelTwoId`, `Name`, `LevelOneId`) VALUES
(1, 'Improvement', 1),
(2, 'Admin', 2),
(8, 'default', 8);

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `PlanId` int(11) NOT NULL,
  `StrataNumber` varchar(45) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `UserId` int(11) NOT NULL,
  `PostalCode` varchar(6) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `Street` varchar(255) DEFAULT NULL,
  `ConstructionType` varchar(45) DEFAULT NULL,
  `StrataRegistrationDate` date DEFAULT NULL,
  `ConstructionYear` year(4) DEFAULT NULL,
  `Floors` varchar(45) DEFAULT NULL,
  `SiteArea` int(11) DEFAULT NULL,
  `BuiltSiteCoverage` int(11) DEFAULT NULL,
  `BuildingHeightLevels` int(11) DEFAULT NULL,
  `StrataPlans` longtext,
  `BuildingPlans` longtext,
  `SitePlans` longtext,
  `ClientId` int(11) NOT NULL,
  `EffectiveDate` date DEFAULT NULL,
  `Overview` longtext,
  `Foundations` longtext,
  `Substructure` longtext,
  `Exterior` longtext,
  `RoofDrainage` longtext,
  `Amenities` longtext,
  `Electrical` longtext,
  `Services` longtext,
  `NumResidentialStrataLots` int(11) DEFAULT NULL,
  `NumCommercialStrataLots` int(11) DEFAULT NULL,
  `NumComplexOwnedStrataLots` int(11) DEFAULT NULL,
  `RestrictedCovenant` int(11) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `MaterialGiven` text NOT NULL,
  `NumBuildings` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`PlanId`, `StrataNumber`, `Name`, `UserId`, `PostalCode`, `City`, `Street`, `ConstructionType`, `StrataRegistrationDate`, `ConstructionYear`, `Floors`, `SiteArea`, `BuiltSiteCoverage`, `BuildingHeightLevels`, `StrataPlans`, `BuildingPlans`, `SitePlans`, `ClientId`, `EffectiveDate`, `Overview`, `Foundations`, `Substructure`, `Exterior`, `RoofDrainage`, `Amenities`, `Electrical`, `Services`, `NumResidentialStrataLots`, `NumCommercialStrataLots`, `NumComplexOwnedStrataLots`, `RestrictedCovenant`, `Location`, `MaterialGiven`, `NumBuildings`) VALUES
(1, 'LMS 667', 'Ocean Crest', 14, 'V4A9S6', 'Surrey', '12985 15th Avenue', 'Asphalt and stamped concrete road with concre', '1992-12-04', 1992, '2', 72315, 50000, 2, 'Reviewed', 'Only city sewer plans off street reviewed.', 'Not reviewed.', 9, '2014-01-01', 'This bare land strata development has 10 strata lots and was constructed in accordance with applicable fire codes, city by-laws, and construction practices in existence, in approximately 1992.\n\nThe quality of construction, materials and workmanship are considered to be average at the time of the site-visit.', '', '', '', '', '', 'Electrical services come from the 1 transformer on site.  Incoming metered service includes a distribution panel, wiring and fixtures for the monument lighting and the street lights. All incoming utilities are underground.', 'Water, sewer and drainage services are underground.  ', 10, 0, 0, 0, 'A cul-de-sac at the east side of 130th Street.', 'None.', 10),
(26, 'ads', 'da', 12, 'd8d7f7', 'ad', 'ad', 'sda', '2015-12-09', 1995, '12', 12, 12, 2, 'ds', 'assda', 'sd', 1, '2015-12-10', 'gg', 'gg', 'gg', 'gg', 'gg', 'gg', 'gg', 'gg', 12, 12, 12, 0, 'ad', 'a', 12),
(27, 'MSC-20122', 'The Renaissance on Main', 14, 'V5A2G6', 'Vancouver', '123 Main Street', '', '2015-01-01', 2013, '3', 50000, 30000, 3, '', '', '', 1, '2016-01-01', '', '', '', '', '', '', '', '', 30, 0, 0, 0, 'Downtown', '', 1),
(28, 'XYZ 123', 'Bear Creek Village', 12, 'V3W5C3', 'Surrey', '8555 King George Blvd', 'Wooden', '1995-02-07', 1995, '2', 250120, 200000, 2, 'Reviewed', 'Not reviewed.', 'No', 6, '2016-01-01', 'This is a place, this is a test', '', '', '', '', '', 'Transformers, robots in disguise', 'Sewer "GRATES" HAHA', 30, 1, 1, 0, 'Across from Bear Creek Park', 'None', 32),
(29, 'asd123', 'AMAZING TOWN', 14, 'V3W9X9', 'SURREY', '123 Street', 'Steel beams and Jet fuel', '2007-09-14', 1995, '2', 32000, 3000, 2, 'Reviewed', 'Unreviewed', 'Sewer city plans', 4, '2015-12-01', 'Hello darkness my old friend', '', '', '', '', '', 'Transformers, robots, in, disguise;', 'Dank', 4, 0, 0, 0, 'East West of the Nile', 'as123', 4);

-- --------------------------------------------------------

--
-- Table structure for table `plancomponent`
--

CREATE TABLE `plancomponent` (
  `PlanComponentId` int(11) NOT NULL,
  `PlanId` int(11) NOT NULL,
  `LevelFourId` int(11) NOT NULL,
  `YearAcquired` year(4) DEFAULT NULL,
  `DeficiencyAnalysis` longtext,
  `ConditionAnalysis` longtext,
  `PhysicalCondition` longtext,
  `FinancialAnalysis` longtext,
  `NumUnits` int(11) NOT NULL DEFAULT '0',
  `EffectiveAge` int(4) DEFAULT NULL,
  `UnitOfMeasure` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plancomponent`
--

INSERT INTO `plancomponent` (`PlanComponentId`, `PlanId`, `LevelFourId`, `YearAcquired`, `DeficiencyAnalysis`, `ConditionAnalysis`, `PhysicalCondition`, `FinancialAnalysis`, `NumUnits`, `EffectiveAge`, `UnitOfMeasure`) VALUES
(24, 1, 1, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(25, 1, 2, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(26, 1, 3, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(27, 1, 4, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(28, 1, 5, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(29, 1, 6, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(30, 1, 7, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(31, 1, 9, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(32, 1, 10, 1992, 'We noted minor alligatoring. We recommend that pavement be monitored for ravelling or cracking and that preventative maintenance be followed that ensure proper drainage of the surface before sealing.', 'The roadway was in good condition.', 'This component refers to the asphalt vehicular roadway and surface parking. Resurfacing refers to the removal and re-instatement of the top coat once deterioration no longer benefits from sealant application. A few base-coat structural repairs and the resetting of one storm drainage drain or grille are typically included in a resurfacing major repair.', 'There has been no known resurfacing since construction.', 7155, 23, 'squareFeet'),
(33, 26, 1, 1995, ' ', ' ', ' ', ' ', 2, 20, 'squareFeet'),
(34, 1, 11, 1992, 'This is a description of an electrical system. This description ecxists only for demonstration purposes\nas well as testing.', 'None.', 'This component reserves for future Depreciation Reports in the Reserve Fund rather than out of the Operating Fund. This component builds reserves on an annual basis for meeting the Strata Property Act mandate of renewing the Depreciation Report every three-year cycle. We are assuming at this time that all future reports will be updated reports although we may anticipate that a full report may be required in the future as legislative changes occur.', 'This component has no known expenditures since 1988. ', 1, 22, 'squareFeet'),
(35, 27, 5, 2013, 'This monument is in excellent shape, and with regular maintenance will stay that way.', 'These street lights are due for repainting in 5 years. ', 'The monument is located in the lobby of the strata''s main entrance.', 'This component is a water feature, and as such will need regular maintenance.', 15, 2, 'unit'),
(36, 27, 7, 2013, 'This monument is in excellent shape, and with regular maintenance will stay that way.', 'These street lights are due for repainting in 5 years. ', 'The monument is located in the lobby of the strata''s main entrance.', 'This component is a water feature, and as such will need regular maintenance.', 0, 2, 'unit'),
(37, 29, 3, 1995, 'b;oaj;of;joadbjpo', 'We observed minimal damage to the surface, but some cracking.', 'This component refers to the stamped concrete area of the roadway at the entrance to the cul-de-sac. ', 'There were no known reported expenditures for this component since 1992.', 55, 20, 'squareFeet'),
(38, 1, 16, 1992, 'This is a description of an electrical system. This description ecxists only for demonstration purposes\nas well as testing.', 'One meter and sub-panel was observed and attached to the monument above ground.', 'This component refers to the underground electrical refers to the underground electrical distribution\nsystem to the strata corporation.', 'This component has no known expenditures since its acquisition.', 1, 22, 'squareFeet');

-- --------------------------------------------------------

--
-- Table structure for table `planservice`
--

CREATE TABLE `planservice` (
  `PlanServiceId` int(11) NOT NULL,
  `PlanId` int(11) NOT NULL,
  `ServiceName` varchar(45) NOT NULL,
  `Comment` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `planservice`
--

INSERT INTO `planservice` (`PlanServiceId`, `PlanId`, `ServiceName`, `Comment`) VALUES
(202, 27, 'Roadways:', '3000'),
(203, 27, 'Curbs:', '6000'),
(204, 27, 'Street Lighting:', '3000'),
(205, 27, 'Driveway and Parking Area:', '3000'),
(206, 27, 'Fencing:', '5000'),
(207, 28, 'Roadways:', '50000'),
(208, 28, 'Curbs:', '600'),
(209, 28, 'Street Lighting:', '10'),
(210, 28, 'Driveway and Parking Area:', '12'),
(211, 28, 'Fencing:', '400'),
(212, 28, 'Playground Area:', '120'),
(225, 29, 'Roadways:', '5000'),
(226, 29, 'Curbs:', '450'),
(227, 29, 'Street Lighting:', '6'),
(228, 29, 'Driveway and Parking Area:', '200'),
(229, 29, 'Fencing:', '400'),
(230, 29, 'Playground Area:', '120'),
(243, 1, 'Site Services:', '2'),
(244, 1, 'Roadways:', '7674'),
(245, 1, 'Curbs:', '569'),
(246, 1, 'Street Lighting:', '4');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `ServiceId` int(11) NOT NULL,
  `ServiceName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stndcomment`
--

CREATE TABLE `stndcomment` (
  `StndCommentId` int(11) NOT NULL,
  `Comment` longtext NOT NULL,
  `LevelFourId` int(11) NOT NULL,
  `Type` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stndcomment`
--

INSERT INTO `stndcomment` (`StndCommentId`, `Comment`, `LevelFourId`, `Type`) VALUES
(34, 'Second option.', 16, 'condAnalysis');

-- --------------------------------------------------------

--
-- Table structure for table `temporaryreport`
--

CREATE TABLE `temporaryreport` (
  `PlanId` int(11) NOT NULL,
  `DatePaused` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temporaryreport`
--

INSERT INTO `temporaryreport` (`PlanId`, `DatePaused`) VALUES
(1, '2015-12-03'),
(29, '2015-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `Username` varchar(45) NOT NULL,
  `Password` char(255) NOT NULL,
  `AccessLevel` int(11) DEFAULT NULL,
  `Email` varchar(90) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserId`, `Username`, `Password`, `AccessLevel`, `Email`) VALUES
(12, 'Bramwell', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 6, 'bramwell@bramwell.com'),
(13, 'client', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 1, 'client@clients.ca'),
(14, 'inspector', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 2, 'inspesctors@clients.ca'),
(18, 'Assistant', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 4, 'assistant@bramwell.com'),
(19, 'Costing', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 3, 'costing@costing.com'),
(20, 'Test', '$2a$07$usesomesillystringforeIo8sT1NkGIqqmFURKkrDRuRIJ1XVz22', 2, 'test@test.com'),
(21, 'Liam', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 2, 'liamwyatt19@gmail.com'),
(22, 'Cooper', '$2a$07$usesomesillystringforeaukJGqKyjM.17r0oegwMm5vRFfPVfju', 2, 'cooper@cooper.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ClientId`);

--
-- Indexes for table `componentpicture`
--
ALTER TABLE `componentpicture`
  ADD PRIMARY KEY (`PictureId`);

--
-- Indexes for table `constructioninfo`
--
ALTER TABLE `constructioninfo`
  ADD PRIMARY KEY (`ConstructInfoId`);

--
-- Indexes for table `dateinspected`
--
ALTER TABLE `dateinspected`
  ADD PRIMARY KEY (`DateInspectedId`);

--
-- Indexes for table `incompletecomponents`
--
ALTER TABLE `incompletecomponents`
  ADD PRIMARY KEY (`PlanId`,`LevelFourId`);

--
-- Indexes for table `inspectedby`
--
ALTER TABLE `inspectedby`
  ADD PRIMARY KEY (`PlanId`,`InspectorId`),
  ADD KEY `InspectorIDInspectedBy_idx` (`InspectorId`);

--
-- Indexes for table `inspector`
--
ALTER TABLE `inspector`
  ADD PRIMARY KEY (`InspectorId`);

--
-- Indexes for table `levelfour`
--
ALTER TABLE `levelfour`
  ADD PRIMARY KEY (`LevelFourId`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`),
  ADD KEY `LevelThreeId_idx` (`LevelThreeId`);

--
-- Indexes for table `levelone`
--
ALTER TABLE `levelone`
  ADD PRIMARY KEY (`LevelOneId`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`);

--
-- Indexes for table `levelthree`
--
ALTER TABLE `levelthree`
  ADD PRIMARY KEY (`LevelThreeId`),
  ADD KEY `LevelTwoId_idx` (`LevelTwoId`);

--
-- Indexes for table `leveltwo`
--
ALTER TABLE `leveltwo`
  ADD PRIMARY KEY (`LevelTwoId`),
  ADD UNIQUE KEY `Name_UNIQUE` (`Name`),
  ADD KEY `LevelOneId_idx` (`LevelOneId`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`PlanId`),
  ADD KEY `InspectorId_idx` (`UserId`),
  ADD KEY `ClientId_idx` (`ClientId`);

--
-- Indexes for table `plancomponent`
--
ALTER TABLE `plancomponent`
  ADD PRIMARY KEY (`PlanComponentId`),
  ADD KEY `LevelFourId_idx` (`LevelFourId`),
  ADD KEY `PlanId_idx` (`PlanId`);

--
-- Indexes for table `planservice`
--
ALTER TABLE `planservice`
  ADD PRIMARY KEY (`PlanServiceId`),
  ADD UNIQUE KEY `ServiceId_UNIQUE` (`PlanServiceId`),
  ADD KEY `PlanIdService_idx` (`PlanId`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`ServiceId`);

--
-- Indexes for table `stndcomment`
--
ALTER TABLE `stndcomment`
  ADD PRIMARY KEY (`StndCommentId`),
  ADD KEY `LevelFourId_idx` (`LevelFourId`);

--
-- Indexes for table `temporaryreport`
--
ALTER TABLE `temporaryreport`
  ADD PRIMARY KEY (`PlanId`),
  ADD UNIQUE KEY `PlanId_UNIQUE` (`PlanId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `ClientId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `componentpicture`
--
ALTER TABLE `componentpicture`
  MODIFY `PictureId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `constructioninfo`
--
ALTER TABLE `constructioninfo`
  MODIFY `ConstructInfoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `dateinspected`
--
ALTER TABLE `dateinspected`
  MODIFY `DateInspectedId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;
--
-- AUTO_INCREMENT for table `inspector`
--
ALTER TABLE `inspector`
  MODIFY `InspectorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `levelfour`
--
ALTER TABLE `levelfour`
  MODIFY `LevelFourId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `levelone`
--
ALTER TABLE `levelone`
  MODIFY `LevelOneId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `levelthree`
--
ALTER TABLE `levelthree`
  MODIFY `LevelThreeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `leveltwo`
--
ALTER TABLE `leveltwo`
  MODIFY `LevelTwoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `PlanId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `plancomponent`
--
ALTER TABLE `plancomponent`
  MODIFY `PlanComponentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `planservice`
--
ALTER TABLE `planservice`
  MODIFY `PlanServiceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `ServiceId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stndcomment`
--
ALTER TABLE `stndcomment`
  MODIFY `StndCommentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `incompletecomponents`
--
ALTER TABLE `incompletecomponents`
  ADD CONSTRAINT `PlanIdIncompTemp` FOREIGN KEY (`PlanId`) REFERENCES `temporaryreport` (`PlanId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspectedby`
--
ALTER TABLE `inspectedby`
  ADD CONSTRAINT `InspectorIDInspectedBy` FOREIGN KEY (`InspectorId`) REFERENCES `inspector` (`InspectorId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `PlanIDInspectedBy` FOREIGN KEY (`PlanId`) REFERENCES `plan` (`PlanId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `levelfour`
--
ALTER TABLE `levelfour`
  ADD CONSTRAINT `LevelThreeId` FOREIGN KEY (`LevelThreeId`) REFERENCES `levelthree` (`LevelThreeId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `levelthree`
--
ALTER TABLE `levelthree`
  ADD CONSTRAINT `LevelTwoId` FOREIGN KEY (`LevelTwoId`) REFERENCES `leveltwo` (`LevelTwoId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `leveltwo`
--
ALTER TABLE `leveltwo`
  ADD CONSTRAINT `LevelOneId` FOREIGN KEY (`LevelOneId`) REFERENCES `levelone` (`LevelOneId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `ClientId` FOREIGN KEY (`ClientId`) REFERENCES `client` (`ClientId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UserId` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `plancomponent`
--
ALTER TABLE `plancomponent`
  ADD CONSTRAINT `LevelFourId` FOREIGN KEY (`LevelFourId`) REFERENCES `levelfour` (`LevelFourId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `PlanId` FOREIGN KEY (`PlanId`) REFERENCES `plan` (`PlanId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `planservice`
--
ALTER TABLE `planservice`
  ADD CONSTRAINT `PlanIdService` FOREIGN KEY (`PlanId`) REFERENCES `plan` (`PlanId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stndcomment`
--
ALTER TABLE `stndcomment`
  ADD CONSTRAINT `LevelFourId_con` FOREIGN KEY (`LevelFourId`) REFERENCES `levelfour` (`LevelFourId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `temporaryreport`
--
ALTER TABLE `temporaryreport`
  ADD CONSTRAINT `PlanIdPlanTemp` FOREIGN KEY (`PlanId`) REFERENCES `plan` (`PlanId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
