/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 8.0.17 : Database - party_humanres
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`party_humanres` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `party_humanres`;

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `DepartmentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DepartmentParentID` int(11) unsigned DEFAULT '0',
  `DepartmentTypeID` int(11) unsigned DEFAULT '0',
  `DepartmentClassID` int(11) unsigned DEFAULT NULL,
  `DepartmentPeriodID` int(11) unsigned DEFAULT NULL,
  `DepartmentName` varchar(100) DEFAULT NULL,
  `DepartmentFullName` varchar(300) DEFAULT NULL,
  `DepartmentFunction` text,
  `DepartmentOrder` smallint(6) DEFAULT '0',
  `DepartmentIsActive` smallint(6) DEFAULT '1',
  `DepartmentCountChild` smallint(6) DEFAULT '0',
  `DepartmentCountJob` smallint(6) DEFAULT '0',
  `DepartmentCountPosition` smallint(6) DEFAULT '0',
  `DepartmentCountEmployee` smallint(6) DEFAULT '0',
  `DepartmentAreaIDs` text,
  `DepartmentCreatePersonID` int(11) unsigned DEFAULT NULL,
  `DepartmentCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `DepartmentCreateDate` datetime DEFAULT NULL,
  `DepartmentUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `DepartmentUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `DepartmentUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`DepartmentID`),
  KEY `DepartmentParentID` (`DepartmentParentID`),
  KEY `DepartmentTypeID` (`DepartmentTypeID`),
  KEY `DepartmentClassID` (`DepartmentClassID`)
) ENGINE=InnoDB AUTO_INCREMENT=855 DEFAULT CHARSET=utf8;

/*Data for the table `department` */

/*Table structure for table `department_rel` */

DROP TABLE IF EXISTS `department_rel`;

CREATE TABLE `department_rel` (
  `RelDepartmentID` int(11) unsigned DEFAULT NULL,
  `RelDepartmentParentID` int(11) unsigned DEFAULT NULL,
  `RelDepartmentDepth` smallint(6) DEFAULT '1',
  UNIQUE KEY `RelDepartmentID` (`RelDepartmentID`,`RelDepartmentParentID`),
  KEY `FK_departmentrel` (`RelDepartmentID`),
  KEY `FK_departmentrel1` (`RelDepartmentParentID`),
  KEY `RelDepartmentDepth` (`RelDepartmentDepth`),
  CONSTRAINT `FK_departmentrel` FOREIGN KEY (`RelDepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_departmentrel1` FOREIGN KEY (`RelDepartmentParentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `department_rel` */

/*Table structure for table `employee` */

DROP TABLE IF EXISTS `employee`;

CREATE TABLE `employee` (
  `EmployeeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `EmployeePersonID` int(11) unsigned NOT NULL,
  `EmployeeDepartmentID` int(11) DEFAULT NULL,
  `EmployeePositionID` int(11) DEFAULT NULL,
  `EmployeeType` smallint(6) DEFAULT '1',
  `EmployeeIsActive` smallint(6) DEFAULT '0',
  `EmployeeStartID` smallint(6) DEFAULT NULL,
  `EmployeeStartDate` date NOT NULL,
  `EmployeeStartOrderNo` varchar(100) DEFAULT NULL,
  `EmployeeStartOrderDate` date DEFAULT NULL,
  `EmployeeQuitID` smallint(6) DEFAULT NULL,
  `EmployeeQuitSubID` smallint(6) DEFAULT NULL,
  `EmployeeQuitDate` date DEFAULT NULL,
  `EmployeeQuitOrderNo` varchar(100) DEFAULT NULL,
  `EmployeeQuitOrderDate` date DEFAULT NULL,
  `EmployeeCreatePersonID` int(11) unsigned DEFAULT NULL,
  `EmployeeCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EmployeeCreateDate` datetime DEFAULT NULL,
  `EmployeeUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `EmployeeUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EmployeeUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`EmployeeID`),
  KEY `FK_employee` (`EmployeePersonID`),
  KEY `EmployeeDepartmentID` (`EmployeeDepartmentID`),
  KEY `EmployeePositionID` (`EmployeePositionID`),
  KEY `EmployeeIsActive` (`EmployeeIsActive`),
  KEY `EmployeeStartID` (`EmployeeStartID`),
  KEY `EmployeeStartDate` (`EmployeeStartDate`),
  KEY `EmployeeQuitID` (`EmployeeQuitID`),
  KEY `EmployeeQuitSubID` (`EmployeeQuitSubID`),
  CONSTRAINT `FK_employee` FOREIGN KEY (`EmployeePersonID`) REFERENCES `person` (`PersonID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2102 DEFAULT CHARSET=utf8;

/*Data for the table `employee` */

insert  into `employee`(`EmployeeID`,`EmployeePersonID`,`EmployeeDepartmentID`,`EmployeePositionID`,`EmployeeType`,`EmployeeIsActive`,`EmployeeStartID`,`EmployeeStartDate`,`EmployeeStartOrderNo`,`EmployeeStartOrderDate`,`EmployeeQuitID`,`EmployeeQuitSubID`,`EmployeeQuitDate`,`EmployeeQuitOrderNo`,`EmployeeQuitOrderDate`,`EmployeeCreatePersonID`,`EmployeeCreateEmployeeID`,`EmployeeCreateDate`,`EmployeeUpdatePersonID`,`EmployeeUpdateEmployeeID`,`EmployeeUpdateDate`) values (1,1,NULL,NULL,1,1,1,'2019-12-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `person` */

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `PersonID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PersonEmployeeID` int(11) unsigned DEFAULT NULL,
  `PersonEducationLevelID` smallint(6) unsigned DEFAULT NULL,
  `PersonEthnicID` int(11) DEFAULT NULL,
  `PersonRegisterNumber` varchar(100) DEFAULT NULL,
  `PersonLastLetter` varchar(3) DEFAULT NULL,
  `PersonLastName` varchar(200) DEFAULT NULL,
  `PersonFirstLetter` varchar(3) DEFAULT NULL,
  `PersonFirstName` varchar(200) DEFAULT NULL,
  `PersonMiddleName` varchar(200) DEFAULT NULL,
  `PersonBirthDate` date DEFAULT NULL,
  `PersonGender` smallint(6) DEFAULT NULL,
  `PersonBirthCityID` int(11) DEFAULT NULL,
  `PersonBirthDistrictID` int(11) DEFAULT NULL,
  `PersonBirthPlace` varchar(300) DEFAULT NULL,
  `PersonBasicCityID` int(11) DEFAULT NULL,
  `PersonBasicDistrictID` int(11) DEFAULT NULL,
  `PersonBasicPlace` varchar(300) DEFAULT NULL,
  `PersonImageSource` varchar(100) DEFAULT NULL,
  `PersonIsSoldiering` smallint(6) DEFAULT '0',
  `PersonSoldierPassNo` varchar(100) DEFAULT NULL,
  `PersonSoldierYear` year(4) DEFAULT NULL,
  `PersonSoldierID` smallint(6) DEFAULT NULL,
  `PersonSoldierDescr` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `PersonSoldierUpdateDate` datetime DEFAULT NULL,
  `PersonWorkYearAll` int(11) DEFAULT '0',
  `PersonWorkMonthAll` int(11) DEFAULT '0',
  `PersonWorkDayAll` int(11) DEFAULT '0',
  `PersonWorkYearGov` int(11) DEFAULT '0',
  `PersonWorkMonthGov` int(11) DEFAULT '0',
  `PersonWorkDayGov` int(11) DEFAULT '0',
  `PersonWorkYearMilitary` int(11) DEFAULT '0',
  `PersonWorkMonthMilitary` int(11) DEFAULT '0',
  `PersonWorkDayMilitary` int(11) DEFAULT '0',
  `PersonWorkYearCompany` int(11) DEFAULT '0',
  `PersonWorkMonthCompany` int(11) DEFAULT '0',
  `PersonWorkDayCompany` int(11) DEFAULT '0',
  `PersonWorkYearOrgan` int(11) DEFAULT '0',
  `PersonWorkMonthOrgan` int(11) DEFAULT '0',
  `PersonWorkDayOrgan` int(11) DEFAULT '0',
  `PersonAbsentYear` int(11) DEFAULT '0',
  `PersonAbsentMonth` int(11) DEFAULT '0',
  `PersonAbsentDay` int(11) DEFAULT '0',
  `PersonIsEditable` smallint(6) DEFAULT '1',
  `PersonCreatePersonID` int(11) unsigned DEFAULT NULL,
  `PersonCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PersonCreateDate` datetime DEFAULT NULL,
  `PersonUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `PersonUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PersonUpdateDate` datetime DEFAULT NULL,
  `PersonContactMobilePhone` varchar(200) DEFAULT NULL,
  `PersonContactWorkPhone` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PersonContactFax` varchar(200) DEFAULT NULL,
  `PersonContactEmail` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PersonContactEmailOrgan` varchar(200) DEFAULT NULL,
  `PersonContactWebsite` varchar(200) DEFAULT NULL,
  `PersonContactEmergencyName` varchar(200) DEFAULT NULL,
  `PersonContactEmergencyPhone` varchar(100) DEFAULT NULL,
  `PersonAddressCityID` int(11) DEFAULT NULL,
  `PersonAddressDistrictID` int(11) DEFAULT NULL,
  `PersonAddressHorooID` int(11) DEFAULT NULL,
  `PersonAddress` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PersonAddressFull` varchar(300) DEFAULT NULL,
  `PersonContactUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `PersonContactUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PersonContactUpdateDate` datetime DEFAULT NULL,
  `PersonCountEmployee` smallint(6) DEFAULT '1',
  `PersonCountEducation` smallint(6) DEFAULT '0',
  `PersonCountStudy` smallint(6) DEFAULT '0',
  `PersonCountLanguage` smallint(6) DEFAULT '0',
  `PersonCountDegree` smallint(6) DEFAULT '0',
  `PersonCountAward` smallint(6) DEFAULT '0',
  `PersonCountJob` smallint(6) DEFAULT '0',
  `PersonCountPunishment` smallint(6) DEFAULT '0',
  `PersonCountTrip` smallint(6) DEFAULT '0',
  `PersonCountSalary` smallint(6) DEFAULT '0',
  `PersonCountFamily` smallint(6) DEFAULT '0',
  `PersonCountRelate` smallint(6) DEFAULT '0',
  `PersonCountPosRank` smallint(6) DEFAULT '0',
  `PersonCountHoliday` smallint(6) DEFAULT '0',
  `PersonCountBill` smallint(6) DEFAULT '0',
  `PersonUserIsCreated` smallint(6) DEFAULT '0',
  `PersonUserName` varchar(100) DEFAULT NULL,
  `PersonUserEmail` varchar(150) DEFAULT NULL,
  `PersonUserPassword` varchar(100) DEFAULT NULL,
  `PersonUserUpdatePersonID` int(11) DEFAULT NULL,
  `PersonUserUpdateEmployeeID` int(11) DEFAULT NULL,
  `PersonUserUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`PersonID`),
  UNIQUE KEY `NewIndex2` (`PersonUserEmail`),
  UNIQUE KEY `PersonRegisterNumber` (`PersonRegisterNumber`),
  KEY `NewIndex1` (`PersonUserName`),
  KEY `PersonLastEmployeeID` (`PersonEmployeeID`),
  KEY `PersonEducationLevelID` (`PersonEducationLevelID`),
  KEY `PersonEthnicID` (`PersonEthnicID`),
  KEY `PersonIsEditable` (`PersonIsEditable`)
) ENGINE=InnoDB AUTO_INCREMENT=1921 DEFAULT CHARSET=utf8;

/*Data for the table `person` */

insert  into `person`(`PersonID`,`PersonEmployeeID`,`PersonEducationLevelID`,`PersonEthnicID`,`PersonRegisterNumber`,`PersonLastLetter`,`PersonLastName`,`PersonFirstLetter`,`PersonFirstName`,`PersonMiddleName`,`PersonBirthDate`,`PersonGender`,`PersonBirthCityID`,`PersonBirthDistrictID`,`PersonBirthPlace`,`PersonBasicCityID`,`PersonBasicDistrictID`,`PersonBasicPlace`,`PersonImageSource`,`PersonIsSoldiering`,`PersonSoldierPassNo`,`PersonSoldierYear`,`PersonSoldierID`,`PersonSoldierDescr`,`PersonSoldierUpdateDate`,`PersonWorkYearAll`,`PersonWorkMonthAll`,`PersonWorkDayAll`,`PersonWorkYearGov`,`PersonWorkMonthGov`,`PersonWorkDayGov`,`PersonWorkYearMilitary`,`PersonWorkMonthMilitary`,`PersonWorkDayMilitary`,`PersonWorkYearCompany`,`PersonWorkMonthCompany`,`PersonWorkDayCompany`,`PersonWorkYearOrgan`,`PersonWorkMonthOrgan`,`PersonWorkDayOrgan`,`PersonAbsentYear`,`PersonAbsentMonth`,`PersonAbsentDay`,`PersonIsEditable`,`PersonCreatePersonID`,`PersonCreateEmployeeID`,`PersonCreateDate`,`PersonUpdatePersonID`,`PersonUpdateEmployeeID`,`PersonUpdateDate`,`PersonContactMobilePhone`,`PersonContactWorkPhone`,`PersonContactFax`,`PersonContactEmail`,`PersonContactEmailOrgan`,`PersonContactWebsite`,`PersonContactEmergencyName`,`PersonContactEmergencyPhone`,`PersonAddressCityID`,`PersonAddressDistrictID`,`PersonAddressHorooID`,`PersonAddress`,`PersonAddressFull`,`PersonContactUpdatePersonID`,`PersonContactUpdateEmployeeID`,`PersonContactUpdateDate`,`PersonCountEmployee`,`PersonCountEducation`,`PersonCountStudy`,`PersonCountLanguage`,`PersonCountDegree`,`PersonCountAward`,`PersonCountJob`,`PersonCountPunishment`,`PersonCountTrip`,`PersonCountSalary`,`PersonCountFamily`,`PersonCountRelate`,`PersonCountPosRank`,`PersonCountHoliday`,`PersonCountBill`,`PersonUserIsCreated`,`PersonUserName`,`PersonUserEmail`,`PersonUserPassword`,`PersonUserUpdatePersonID`,`PersonUserUpdateEmployeeID`,`PersonUserUpdateDate`) values (1,1,NULL,NULL,'1','Н','Н','А','admin',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,1,1,'2021-10-22 17:55:30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,'admin',NULL,'21232f297a57a5a743894a0e4a801fc3',NULL,NULL,NULL);

/*Table structure for table `person_award` */

DROP TABLE IF EXISTS `person_award`;

CREATE TABLE `person_award` (
  `AwardID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AwardPersonID` int(11) unsigned DEFAULT '0',
  `AwardRefID` int(11) unsigned DEFAULT '0',
  `AwardRefSubID` int(11) DEFAULT '0',
  `AwardOrganTitle` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `AwardTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `AwardDate` date DEFAULT NULL,
  `AwardLicence` varchar(50) DEFAULT NULL,
  `AwardCreatePersonID` int(11) unsigned DEFAULT NULL,
  `AwardCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `AwardCreateDate` datetime DEFAULT NULL,
  `AwardUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `AwardUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `AwardUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`AwardID`),
  KEY `AwardPersonID` (`AwardPersonID`),
  KEY `AwardRefID` (`AwardRefID`),
  KEY `AwardDate` (`AwardDate`),
  KEY `AwardRefSubID` (`AwardRefSubID`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

/*Data for the table `person_award` */

/*Table structure for table `person_education` */

DROP TABLE IF EXISTS `person_education`;

CREATE TABLE `person_education` (
  `EducationID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `EducationPersonID` int(11) unsigned DEFAULT '0',
  `EducationLevelID` int(11) unsigned DEFAULT '0',
  `EducationDegreeID` int(11) unsigned DEFAULT '0',
  `EducationSchoolID` int(11) unsigned DEFAULT '0',
  `EducationSchoolTitle` varchar(200) DEFAULT NULL,
  `EducationIsNow` smallint(6) DEFAULT '0',
  `EducationDateStart` date DEFAULT NULL,
  `EducationDateEnd` date DEFAULT NULL,
  `EducationProfession` varchar(200) DEFAULT NULL,
  `EducationLicence` varchar(200) DEFAULT NULL,
  `EducationCreatePersonID` int(11) unsigned DEFAULT NULL,
  `EducationCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EducationCreateDate` datetime DEFAULT NULL,
  `EducationUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `EducationUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EducationUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`EducationID`),
  KEY `EducationPersonID` (`EducationPersonID`),
  KEY `EducationLevelID` (`EducationLevelID`),
  KEY `EducationDegreeID` (`EducationDegreeID`),
  KEY `EducationSchoolID` (`EducationSchoolID`),
  KEY `EducationDateEnd` (`EducationDateEnd`),
  KEY `EducationDateStart` (`EducationDateStart`),
  KEY `EducationIsNow` (`EducationIsNow`)
) ENGINE=InnoDB AUTO_INCREMENT=588 DEFAULT CHARSET=utf8;

/*Data for the table `person_education` */

/*Table structure for table `person_edurank` */

DROP TABLE IF EXISTS `person_edurank`;

CREATE TABLE `person_edurank` (
  `EduID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `EduPersonID` int(11) unsigned DEFAULT '0',
  `EduRankID` int(11) unsigned DEFAULT '0',
  `EduDate` date DEFAULT NULL,
  `EduLicence` varchar(50) DEFAULT NULL,
  `EduCreatePersonID` int(11) unsigned DEFAULT NULL,
  `EduCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EduCreateDate` datetime DEFAULT NULL,
  `EduUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `EduUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `EduUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`EduID`),
  KEY `EduPersonID` (`EduPersonID`),
  KEY `EduRankID` (`EduRankID`),
  KEY `EduDate` (`EduDate`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `person_edurank` */

/*Table structure for table `person_family` */

DROP TABLE IF EXISTS `person_family`;

CREATE TABLE `person_family` (
  `FamilyID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `FamilyPersonID` int(11) unsigned DEFAULT '0',
  `FamilyRelationID` int(11) unsigned DEFAULT '0',
  `FamilyJobTypeID` int(11) unsigned DEFAULT '0',
  `FamilyBirthIsAbroad` smallint(6) unsigned DEFAULT '0',
  `FamilyBirthCountryID` int(11) unsigned DEFAULT '0',
  `FamilyBirthCityID` int(11) unsigned DEFAULT '0',
  `FamilyBirthDistrictID` int(11) unsigned DEFAULT '0',
  `FamilyLastName` varchar(100) DEFAULT NULL,
  `FamilyFirstName` varchar(100) DEFAULT NULL,
  `FamilyBirthDate` date DEFAULT NULL,
  `FamilyJobOrgan` varchar(200) DEFAULT NULL,
  `FamilyJobPosition` varchar(200) DEFAULT NULL,
  `FamilyCreatePersonID` int(11) unsigned DEFAULT NULL,
  `FamilyCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `FamilyCreateDate` datetime DEFAULT NULL,
  `FamilyUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `FamilyUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `FamilyUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FamilyID`),
  KEY `FamilyPersonID` (`FamilyPersonID`),
  KEY `FamilyRelationID` (`FamilyRelationID`),
  KEY `FamilyJobTypeID` (`FamilyJobTypeID`),
  KEY `FamilyBirthCityID` (`FamilyBirthCityID`),
  KEY `FamilyBirthDistrictID` (`FamilyBirthDistrictID`),
  KEY `FamilyBirthDate` (`FamilyBirthDate`)
) ENGINE=InnoDB AUTO_INCREMENT=636 DEFAULT CHARSET=utf8;

/*Data for the table `person_family` */

/*Table structure for table `person_holiday` */

DROP TABLE IF EXISTS `person_holiday`;

CREATE TABLE `person_holiday` (
  `HolidayID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `HolidayPersonID` int(11) unsigned DEFAULT '0',
  `HolidayEmployeeID` int(11) DEFAULT '0',
  `HolidayRegisterDate` date DEFAULT NULL,
  `HolidayRegisterNumberYear` varchar(2) DEFAULT NULL,
  `HolidayRegisterNumber` int(11) DEFAULT NULL,
  `HolidayRegisterNumberFull` varchar(10) DEFAULT NULL,
  `HolidayIsFirstYear` smallint(6) DEFAULT '0',
  `HolidayJobDateStart` date DEFAULT NULL,
  `HolidayJobDateEnd` date DEFAULT NULL,
  `HolidayDateStart` date DEFAULT NULL,
  `HolidayDateEnd` date DEFAULT NULL,
  `HolidayDays` int(11) DEFAULT '0',
  `HolidayAddition` smallint(6) DEFAULT '0',
  `HolidayAddition1` smallint(6) DEFAULT '0',
  `HolidayChiefPersonID` int(11) unsigned DEFAULT NULL,
  `HolidayChiefEmployeeID` int(11) unsigned DEFAULT NULL,
  `HolidayHumanresPersonID` int(11) unsigned DEFAULT NULL,
  `HolidayHumanresEmployeeID` int(11) unsigned DEFAULT NULL,
  `HolidayCreatePersonID` int(11) unsigned DEFAULT NULL,
  `HolidayCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `HolidayCreateDate` datetime DEFAULT NULL,
  `HolidayUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `HolidayUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `HolidayUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`HolidayID`),
  KEY `HolidayPersonID` (`HolidayPersonID`),
  KEY `HolidayRegisterNumberFull` (`HolidayRegisterNumberFull`),
  KEY `HolidayRegisterNumberYear` (`HolidayRegisterNumberYear`),
  KEY `HolidayRegisterDate` (`HolidayRegisterDate`),
  KEY `HolidayEmployeeID` (`HolidayEmployeeID`),
  KEY `HolidayRegisterNumber` (`HolidayRegisterNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `person_holiday` */

/*Table structure for table `person_holiday_bill` */

DROP TABLE IF EXISTS `person_holiday_bill`;

CREATE TABLE `person_holiday_bill` (
  `BillID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `BillPersonID` int(11) unsigned DEFAULT '0',
  `BillEmployeeID` int(11) DEFAULT '0',
  `BillRegisterDate` date DEFAULT NULL,
  `BillRegisterNumberYear` varchar(2) DEFAULT NULL,
  `BillRegisterNumber` int(11) DEFAULT NULL,
  `BillRegisterNumberFull` varchar(10) DEFAULT NULL,
  `BillJobDate` date DEFAULT NULL,
  `BillTime` float DEFAULT '0',
  `BillHolidayDay` int(11) DEFAULT '0',
  `BillValue` float DEFAULT '0',
  `BillHolidayDay1` int(11) DEFAULT '0',
  `BillHolidayDay2` int(11) DEFAULT '0',
  `BillChiefPersonID` int(11) DEFAULT '0',
  `BillChiefEmployeeID` int(11) DEFAULT '0',
  `BillHumanresPersonID` int(11) DEFAULT '0',
  `BillHumanresEmployeeID` int(11) DEFAULT '0',
  `BillCreatePersonID` int(11) unsigned DEFAULT NULL,
  `BillCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `BillCreateDate` datetime DEFAULT NULL,
  `BillUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `BillUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `BillUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`BillID`),
  KEY `BillPersonID` (`BillPersonID`),
  KEY `BillRegisterNumberFull` (`BillRegisterNumberFull`),
  KEY `BillRegisterNumberYear` (`BillRegisterNumberYear`),
  KEY `BillRegisterDate` (`BillRegisterDate`),
  KEY `BillEmployeeID` (`BillEmployeeID`),
  KEY `BillRegisterNumber` (`BillRegisterNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `person_holiday_bill` */

/*Table structure for table `person_job` */

DROP TABLE IF EXISTS `person_job`;

CREATE TABLE `person_job` (
  `JobID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `JobPersonID` int(11) unsigned DEFAULT '0',
  `JobOrganTypeID` int(11) DEFAULT '0',
  `JobOrganID` int(11) unsigned DEFAULT '0',
  `JobOrganSubID` int(11) DEFAULT '0',
  `JobPositionID` int(11) DEFAULT '0',
  `JobOrganTitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `JobDepartmentTitle` varchar(200) DEFAULT NULL,
  `JobPositionTitle` varchar(200) DEFAULT NULL,
  `JobIsNow` smallint(6) DEFAULT '0',
  `JobDateStart` date DEFAULT NULL,
  `JobStartOrder` varchar(100) DEFAULT NULL,
  `JobDateQuit` date DEFAULT NULL,
  `JobQuitReason` varchar(100) DEFAULT NULL,
  `JobQuitOrder` varchar(100) DEFAULT NULL,
  `JobQuitOrderDate` date DEFAULT NULL,
  `JobWorkedYear` int(11) DEFAULT '0',
  `JobWorkedMonth` int(11) DEFAULT '0',
  `JobWorkedDay` int(11) DEFAULT '0',
  `JobIsAbsent` smallint(6) DEFAULT '0',
  `JobAbsentDateStart` date DEFAULT NULL,
  `JobAbsentYear` int(11) DEFAULT '0',
  `JobAbsentMonth` int(11) DEFAULT '0',
  `JobAbsentDay` int(11) DEFAULT '0',
  `JobCreatePersonID` int(11) unsigned DEFAULT NULL,
  `JobCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `JobCreateDate` datetime DEFAULT NULL,
  `JobUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `JobUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `JobUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`JobID`),
  KEY `JobPersonID` (`JobPersonID`),
  KEY `JobOrganID` (`JobOrganID`),
  KEY `JobOrganSubID` (`JobOrganSubID`),
  KEY `JobPositionID` (`JobPositionID`),
  KEY `JobIsNow` (`JobIsNow`),
  KEY `JobAbsentIs` (`JobIsAbsent`)
) ENGINE=InnoDB AUTO_INCREMENT=1291 DEFAULT CHARSET=utf8;

/*Data for the table `person_job` */

/*Table structure for table `person_language` */

DROP TABLE IF EXISTS `person_language`;

CREATE TABLE `person_language` (
  `LanguageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `LanguagePersonID` int(11) unsigned DEFAULT '0',
  `LanguageRefID` int(11) unsigned DEFAULT '0',
  `LanguageLevelID` int(11) unsigned DEFAULT '0',
  `LanguageYears` int(11) DEFAULT '0',
  `LanguageCreatePersonID` int(11) unsigned DEFAULT NULL,
  `LanguageCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `LanguageCreateDate` datetime DEFAULT NULL,
  `LanguageUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `LanguageUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `LanguageUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LanguageID`),
  KEY `LanguagePersonID` (`LanguagePersonID`),
  KEY `LanguageRefID` (`LanguageRefID`),
  KEY `LanguageLevelID` (`LanguageLevelID`)
) ENGINE=InnoDB AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

/*Data for the table `person_language` */

/*Table structure for table `person_posrank` */

DROP TABLE IF EXISTS `person_posrank`;

CREATE TABLE `person_posrank` (
  `PosID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PosPersonID` int(11) unsigned DEFAULT '0',
  `PosRankID` int(11) unsigned DEFAULT '0',
  `PosDate` date DEFAULT NULL,
  `PosNumber` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PosCreatePersonID` int(11) unsigned DEFAULT NULL,
  `PosCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PosCreateDate` datetime DEFAULT NULL,
  `PosUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `PosUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PosUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`PosID`),
  KEY `PosPersonID` (`PosPersonID`),
  KEY `PosRankID` (`PosRankID`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;

/*Data for the table `person_posrank` */

/*Table structure for table `person_punishment` */

DROP TABLE IF EXISTS `person_punishment`;

CREATE TABLE `person_punishment` (
  `PunishmentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PunishmentPersonID` int(11) unsigned DEFAULT '0',
  `PunishmentRefID` int(11) unsigned DEFAULT '0',
  `PunishmentOrder` varchar(50) DEFAULT NULL,
  `PunishmentOrderDate` date DEFAULT NULL,
  `PunishmentReason` text,
  `PunishmentTime` int(11) unsigned DEFAULT NULL,
  `PunishmentPercent` float unsigned DEFAULT NULL,
  `PunishmentCreatePersonID` int(11) unsigned DEFAULT NULL,
  `PunishmentCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PunishmentCreateDate` datetime DEFAULT NULL,
  `PunishmentUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `PunishmentUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PunishmentUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`PunishmentID`),
  KEY `PunishmentPersonID` (`PunishmentPersonID`),
  KEY `PunishmentRefID` (`PunishmentRefID`),
  KEY `PunishmentOrderDate` (`PunishmentOrderDate`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `person_punishment` */

/*Table structure for table `person_relation` */

DROP TABLE IF EXISTS `person_relation`;

CREATE TABLE `person_relation` (
  `RelationID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RelationPersonID` int(11) unsigned DEFAULT '0',
  `RelationRelationID` int(11) unsigned DEFAULT '0',
  `RelationJobTypeID` int(11) unsigned DEFAULT '0',
  `RelationLastName` varchar(100) DEFAULT NULL,
  `RelationFirstName` varchar(100) DEFAULT NULL,
  `RelationJobOrgan` varchar(200) DEFAULT NULL,
  `RelationJobPosition` varchar(200) DEFAULT NULL,
  `RelationCreatePersonID` int(11) unsigned DEFAULT NULL,
  `RelationCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `RelationCreateDate` datetime DEFAULT NULL,
  `RelationUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `RelationUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `RelationUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`RelationID`),
  KEY `RelationPersonID` (`RelationPersonID`),
  KEY `RelationRelationID` (`RelationRelationID`),
  KEY `RelationJobTypeID` (`RelationJobTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=766 DEFAULT CHARSET=utf8;

/*Data for the table `person_relation` */

/*Table structure for table `person_salary` */

DROP TABLE IF EXISTS `person_salary`;

CREATE TABLE `person_salary` (
  `SalaryID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SalaryPersonID` int(11) unsigned DEFAULT '0',
  `SalaryDegreeID` int(11) unsigned DEFAULT '0',
  `SalaryConditionID` int(11) DEFAULT '0',
  `SalaryEduID` int(11) DEFAULT '0',
  `SalaryValue` float DEFAULT '0',
  `SalaryCreatePersonID` int(11) unsigned DEFAULT NULL,
  `SalaryCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `SalaryCreateDate` datetime DEFAULT NULL,
  `SalaryUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `SalaryUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `SalaryUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SalaryID`),
  KEY `SalaryPersonID` (`SalaryPersonID`),
  KEY `SalaryRefID` (`SalaryDegreeID`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;

/*Data for the table `person_salary` */

/*Table structure for table `person_study` */

DROP TABLE IF EXISTS `person_study`;

CREATE TABLE `person_study` (
  `StudyID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `StudyPersonID` int(11) unsigned DEFAULT '0',
  `StudyDirectionID` int(11) unsigned DEFAULT '0',
  `StudyDirSubID` int(11) DEFAULT NULL,
  `StudyDirSub1ID` int(11) DEFAULT NULL,
  `StudyCountryID` int(11) unsigned DEFAULT '0',
  `StudySchoolTitle` varchar(200) DEFAULT NULL,
  `StudyDateStart` date DEFAULT NULL,
  `StudyDateEnd` date DEFAULT NULL,
  `StudyDay` int(11) DEFAULT '0',
  `StudyTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `StudyDescr` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `StudyLicence` varchar(200) DEFAULT NULL,
  `StudyLicenceDate` date DEFAULT NULL,
  `StudyCreatePersonID` int(11) unsigned DEFAULT NULL,
  `StudyCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `StudyCreateDate` datetime DEFAULT NULL,
  `StudyUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `StudyUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `StudyUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`StudyID`),
  KEY `StudyPersonID` (`StudyPersonID`),
  KEY `StudyDirectionID` (`StudyDirectionID`),
  KEY `StudyCountryID` (`StudyCountryID`),
  KEY `StudyDateEnd` (`StudyDateEnd`),
  KEY `StudyDateStart` (`StudyDateStart`),
  KEY `StudyLicenceDate` (`StudyLicenceDate`),
  KEY `StudyDirSubID` (`StudyDirSubID`),
  KEY `StudyDirSub1ID` (`StudyDirSub1ID`)
) ENGINE=InnoDB AUTO_INCREMENT=495 DEFAULT CHARSET=utf8;

/*Data for the table `person_study` */

/*Table structure for table `person_trip` */

DROP TABLE IF EXISTS `person_trip`;

CREATE TABLE `person_trip` (
  `TripID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TripPersonID` int(11) unsigned DEFAULT '0',
  `TripRefID` int(11) unsigned DEFAULT '0',
  `TripDateStart` date DEFAULT NULL,
  `TripDateEnd` date DEFAULT NULL,
  `TripDay` int(11) DEFAULT '0',
  `TripOrder` varchar(50) DEFAULT NULL,
  `TripOrderDate` date DEFAULT NULL,
  `TripCreatePersonID` int(11) unsigned DEFAULT NULL,
  `TripCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `TripCreateDate` datetime DEFAULT NULL,
  `TripUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `TripUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `TripUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`TripID`),
  KEY `TripPersonID` (`TripPersonID`),
  KEY `TripRefID` (`TripRefID`),
  KEY `TripDateEnd` (`TripDateEnd`),
  KEY `TripDateStart` (`TripDateStart`),
  KEY `TripOrderDate` (`TripOrderDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `person_trip` */

/*Table structure for table `position` */

DROP TABLE IF EXISTS `position`;

CREATE TABLE `position` (
  `PositionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PositionDepartmentID` int(11) unsigned DEFAULT NULL,
  `PositionTypeID` smallint(6) unsigned DEFAULT NULL,
  `PositionRankID` int(11) unsigned DEFAULT NULL,
  `PositionDegreeID` int(11) unsigned DEFAULT NULL,
  `PositionClassID` int(11) unsigned DEFAULT NULL,
  `PositionName` varchar(100) DEFAULT NULL,
  `PositionFullName` varchar(300) DEFAULT NULL,
  `PositionCountPerson` smallint(6) DEFAULT '0',
  `PositionCountEmployee` smallint(6) DEFAULT '0',
  `PositionCountEmployeed` smallint(6) DEFAULT '0',
  `PositionCountEmployeeRecord` smallint(6) DEFAULT '0',
  `PositionOrder` smallint(6) DEFAULT '0',
  `PositionPurpose` text,
  `PositionReqEducation1` text,
  `PositionReqEducation2` text,
  `PositionReqProfession1` text,
  `PositionReqProfession2` text,
  `PositionReqQualification1` text,
  `PositionReqQualification2` text,
  `PositionReqExperience1` text,
  `PositionReqExperience2` text,
  `PositionReqSkill1` text,
  `PositionReqSkill2` text,
  `PositionReqSpecial1` text,
  `PositionReqSpecial2` text,
  `PositionFactor1` text,
  `PositionFactor2` text,
  `PositionFactor3` text,
  `PositionFactor4` text,
  `PositionFactor5` text,
  `PositionFactor6` text,
  `PositionFactor7` text,
  `PositionFactor8` text,
  `PositionFactor9` text,
  `PositionWarrantPosition1Name` varchar(300) DEFAULT NULL,
  `PositionWarrantSign1` varchar(200) DEFAULT NULL,
  `PositionWarrantDate1` date DEFAULT NULL,
  `PositionWarrantPosition2Name` varchar(300) DEFAULT NULL,
  `PositionWarrantSign2` varchar(200) DEFAULT NULL,
  `PositionWarrantDate2` date DEFAULT NULL,
  `PositionCreatePersonID` int(11) unsigned DEFAULT NULL,
  `PositionCreateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PositionCreateDate` datetime DEFAULT NULL,
  `PositionUpdatePersonID` int(11) unsigned DEFAULT NULL,
  `PositionUpdateEmployeeID` int(11) unsigned DEFAULT NULL,
  `PositionUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`PositionID`),
  KEY `PositionDepartmentID` (`PositionDepartmentID`),
  KEY `PositionTypeID` (`PositionTypeID`),
  KEY `PositionRankID` (`PositionRankID`),
  KEY `PositionDegreeID` (`PositionDegreeID`),
  KEY `PositionClassID` (`PositionClassID`),
  KEY `PositionOrder` (`PositionOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=2306 DEFAULT CHARSET=utf8;

/*Data for the table `position` */

/*Table structure for table `ref_age` */

DROP TABLE IF EXISTS `ref_age`;

CREATE TABLE `ref_age` (
  `AgeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AgeTitle` varchar(250) DEFAULT NULL,
  `AgeStart` smallint(6) DEFAULT '0',
  `AgeEnd` smallint(6) DEFAULT '0',
  `AgeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`AgeID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ref_age` */

insert  into `ref_age`(`AgeID`,`AgeTitle`,`AgeStart`,`AgeEnd`,`AgeOrder`) values (1,'18-24',18,24,1),(2,'25-34',25,34,2),(3,'35-44',35,44,3),(4,'45-54\r\n',45,54,4),(5,'55-59\r\n',55,59,5),(6,'60 болон түүнээс дээш\r\n',60,300,6);

/*Table structure for table `ref_award` */

DROP TABLE IF EXISTS `ref_award`;

CREATE TABLE `ref_award` (
  `RefAwardID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefAwardParentID` int(11) DEFAULT '0',
  `RefAwardTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefAwardOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefAwardID`),
  KEY `AwardOrder` (`RefAwardOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `ref_award` */

insert  into `ref_award`(`RefAwardID`,`RefAwardParentID`,`RefAwardTitle`,`RefAwardOrder`) values (1,0,'Төрийн одон',1),(2,0,'Засгийн газрын шагнал',2),(3,0,'АТГ-ын шагнал',3),(4,0,'Бусад байгууллагын шагнал',7),(5,0,'Бусад',8),(6,2,'Засгийн газрын Жуух бичиг',1),(7,2,'&quot;Засгийн газрын салбарын тэргүүний ажилтан&quot; хүндэт тэмдэг',2),(8,1,'Сүхбаатарын одон',1),(9,1,'Хөдөлмөрийн гавъяаны улаан тугийн одон',2),(10,1,'Байлдааны гавьяаны улаан тугийн одон',3),(11,1,'Алтан гадас одон',4),(12,1,'Цэргийн гавьяаны одон',5),(13,1,'Хөдөлмөрийн хүндэт медаль',6),(14,1,'Цэргийн хүндэт медаль',7),(15,1,'Шударга журам медаль',8),(16,1,'Бусад',10),(17,3,'Хүндэт жуух',1),(18,3,'Баярын бичиг',2),(19,3,'Албаны төлөө-I',3),(20,3,'Албаны төлөө-II',4),(21,3,'Албаны төлөө-III',5),(22,3,'Хөдөлмөрийн аварга',6),(23,3,'Ажил мэргэжлийн аварга',7),(24,1,'Ардын хувьсгалын ойн медаль',9),(25,2,'Цагдаагийн алдар хүндэт тэмдэг',3),(26,0,'Хууль зүй, Дотоод хэргийн яамны шагнал',4),(27,0,'Цагдаагийн байгууллагын шагнал',5),(28,0,'Тагнуулын байгууллагын шагнал',6),(29,26,'Хүндэт жуух бичиг',1),(30,26,'Мөнгөн шагнал, үнэ бүхий зүйл',2),(31,27,'Цагдаагийн гавъяа',1),(32,27,'Цагдаагийн төлөө',2),(33,27,'Монгол цагдаа',3),(34,27,'Хүндэт жуух бичиг\r\n',4),(35,27,'Баярын бичиг\r\n',5),(36,27,'Спортын алдар',6),(37,28,'Тагнуулын алдар',1),(38,28,'Аюулгүй байдлын төлөө -1\r\n',2),(39,28,'Аюулгүй байдлын төлөө -2\r\n',3),(40,28,'Аюулгүй байдлын төлөө -3\r\n',4),(41,4,'МЗХ-ны шагнал',1),(42,4,'ШШГЕГ-ын шагнал',2),(43,4,'БХЯ-ны шагнал\r\n',3),(44,4,'Прокурорын байгууллагын шагнал\r\n',4),(45,4,'Бусад ойн медаль, тэмдэг, өргөмжлөл, гэрчилгээ',5),(46,5,'Тэргүүний албан хаагч\r\n',1),(47,5,'Бусад\r\n',2),(48,27,'Цагдаагийн албаны төлөө-1',7),(49,27,'Цагдаагийн албаны төлөө-2',8),(50,27,'Цагдаагийн албаны төлөө-3',9);

/*Table structure for table `ref_department_class` */

DROP TABLE IF EXISTS `ref_department_class`;

CREATE TABLE `ref_department_class` (
  `RefClassID` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `RefClassTitle` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefClassOrder` smallint(6) DEFAULT '0',
  PRIMARY KEY (`RefClassID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ref_department_class` */

insert  into `ref_department_class`(`RefClassID`,`RefClassTitle`,`RefClassOrder`) values (1,'Удирдлага',1),(2,'Газар',2),(3,'Хэлтэс',3),(4,'Алба',4);

/*Table structure for table `ref_department_type` */

DROP TABLE IF EXISTS `ref_department_type`;

CREATE TABLE `ref_department_type` (
  `RefTypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefTypeTitle` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefTypeOrder` smallint(6) DEFAULT '0',
  PRIMARY KEY (`RefTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ref_department_type` */

insert  into `ref_department_type`(`RefTypeID`,`RefTypeTitle`,`RefTypeOrder`) values (1,'Гэмт хэргийн шинжтэй - Мөрдөн шалгах хэлтэс',1),(2,'Гэмт хэргийн шинжтэй - Гүйцэтгэх ажлын хэлтэс',2),(3,'Ашиг сонирхлын зөрчил, хөрөнгө орлогын мэдээтэй холбоотой',3),(4,'Төрийн байгууллагын ёс зүй, хүнд суртал, удирдах ажилтны гаргасан шийдвэртэй холбоотой',4),(5,'Захиргааны шинж чанартай',5),(6,'АТГ албан тушаалтантай холбоотой',6),(7,'-',7);

/*Table structure for table `ref_education_degree` */

DROP TABLE IF EXISTS `ref_education_degree`;

CREATE TABLE `ref_education_degree` (
  `RefDegreeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDegreeTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDegreeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefDegreeID`),
  KEY `LevelOrder` (`RefDegreeOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ref_education_degree` */

insert  into `ref_education_degree`(`RefDegreeID`,`RefDegreeTitle`,`RefDegreeOrder`) values (1,'Баклавр',1),(2,'Магистр',2),(3,'Доктор',3);

/*Table structure for table `ref_education_level` */

DROP TABLE IF EXISTS `ref_education_level`;

CREATE TABLE `ref_education_level` (
  `RefLevelID` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `RefLevelTitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefLevelOrder` smallint(6) DEFAULT '1',
  PRIMARY KEY (`RefLevelID`),
  KEY `LevelOrder` (`RefLevelOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `ref_education_level` */

insert  into `ref_education_level`(`RefLevelID`,`RefLevelTitle`,`RefLevelOrder`) values (1,'Бага ',2),(2,'Бүрэн бус дунд /суурь/',3),(3,'Бүрэн дунд',4),(4,'Техникийн эсхүл Мэргэжлийн',5),(5,'Дипломын дээд',6),(6,'Бакалавр эсхүл түүнтэй тэнцэх',7),(7,'Магистр эсхүл түүнтэй тэнцэх',8),(8,'Доктор эсвэл түүнтэй тэнцэх',9),(9,'Боловсролгүй',1);

/*Table structure for table `ref_education_rank` */

DROP TABLE IF EXISTS `ref_education_rank`;

CREATE TABLE `ref_education_rank` (
  `RefRankID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefRankTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefRankOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefRankID`),
  KEY `RankOrder` (`RefRankOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ref_education_rank` */

insert  into `ref_education_rank`(`RefRankID`,`RefRankTitle`,`RefRankOrder`) values (1,'Дэд профессор',1),(2,'Профессор',2),(3,'Академич',3),(4,'Шинжлэх ухааны Доктор',4),(5,'Философийн Доктор',5);

/*Table structure for table `ref_education_school` */

DROP TABLE IF EXISTS `ref_education_school`;

CREATE TABLE `ref_education_school` (
  `RefSchoolID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefSchoolTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefSchoolOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefSchoolID`),
  KEY `SchoolOrder` (`RefSchoolOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ref_education_school` */

insert  into `ref_education_school`(`RefSchoolID`,`RefSchoolTitle`,`RefSchoolOrder`) values (1,'Ерөнхий боловсролын сургууль',1),(2,'Коллеж',2),(3,'Мэргэжлийн сургууль',3),(4,'Их, дээд сургууль',4);

/*Table structure for table `ref_employee_quit` */

DROP TABLE IF EXISTS `ref_employee_quit`;

CREATE TABLE `ref_employee_quit` (
  `RefQuitID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefQuitTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefQuitOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefQuitID`),
  KEY `StartOrder` (`RefQuitOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ref_employee_quit` */

insert  into `ref_employee_quit`(`RefQuitID`,`RefQuitTitle`,`RefQuitOrder`) values (1,'Өөр албан тушаалд шилжүүлэх, сэлгэн ажиллуулах',1),(2,'Захиргааны санаачилгаар албан тушаал бууруулах',2),(3,'Албан тушаалаас түр чөлөөлөх',3),(4,'Албан тушаалаас чөлөөлөх',4),(5,'Албан тушаалаас халах',5);

/*Table structure for table `ref_employee_quit_sub` */

DROP TABLE IF EXISTS `ref_employee_quit_sub`;

CREATE TABLE `ref_employee_quit_sub` (
  `RefSubID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefSubParentID` int(11) unsigned NOT NULL,
  `RefSubTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefSubOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefSubID`),
  KEY `StartOrder` (`RefSubOrder`),
  KEY `RefSubQuitID` (`RefSubParentID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `ref_employee_quit_sub` */

insert  into `ref_employee_quit_sub`(`RefSubID`,`RefSubParentID`,`RefSubTitle`,`RefSubOrder`) values (1,1,'Нэгж хооронд сэлгэсэн',1),(2,1,'Гадны байгууллагатай харилцан тохиролцсоны дагуу шилжүүлсэн',2),(3,2,'Хууль тогтоомж болон албан тушаалын тодорхойлолтод заасан чиг үүрэг, зорилтоо хангалтгүй биелүүлсэн',1),(4,2,'Үйл ажиллагааны үр дүн, мэргэшлийн түвшин нь тухайн албан тушаал эрхлэх шаардлага хангахгүй болсон',2),(5,3,'Биеийн эрүүл мэндийн байдлын улмаас 3 сараас дээш хугацаагаар албан ажлаа хийхгүйгээр эмчлүүлж, сувилуулах шаардлагатай болсон',1),(6,3,'Зургаан сараас дээш хугацааны сургалтаар бэлтгэгдэх болсон',2),(7,3,'Цэргийн жинхэнэ алба хаах болсон',3),(8,3,'Гэрч, хохирогчийг хамгаалах тухай хуулийн дагуу хамгаалалтын арга хэмжээнд хамрагдсан',4),(9,4,'Сонгууль нэр дэвших болсон',1),(10,4,'Тэтгэвэр тогтоолгох насанд хүрсэн',2),(11,4,'Төрийн алба хаах насны дээд хязгаарт хүрсэн',3),(12,4,'Өөрийн санаачилгаар төрийн албанаас чөлөөлөгдөх хүсэлт гаргасан',4),(13,4,'Нас барсан',5),(14,5,'Удаа дараа албан үүргээ хангалтгүй биелүүлсэн',1),(15,5,'Гэмт хэрэг үйлдсэн нь шүүхээр тогтоогдсон',2),(16,5,'МУ-ын харьяатаас гарсан',3);

/*Table structure for table `ref_employee_start` */

DROP TABLE IF EXISTS `ref_employee_start`;

CREATE TABLE `ref_employee_start` (
  `RefStartID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefStartTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefStartOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefStartID`),
  KEY `StartOrder` (`RefStartOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ref_employee_start` */

insert  into `ref_employee_start`(`RefStartID`,`RefStartTitle`,`RefStartOrder`) values (1,'Тухайн байгууллагад ажиллаж байсан ',1),(2,'Сэлгэн томилогдсон  /байгууллага дотроос, бусад байгууллагад/ эсэх                                          ',2),(3,'Бусад байгууллагаас шилжин томилогдсон ',3),(4,'Төрийн албан хаагчийн нөөцөөс',4),(5,'Сонгон шалгаруулалтаар',5);

/*Table structure for table `ref_ethnicity` */

DROP TABLE IF EXISTS `ref_ethnicity`;

CREATE TABLE `ref_ethnicity` (
  `RefEthnicID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefEthnicTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefEthnicOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefEthnicID`),
  KEY `EthnicOrder` (`RefEthnicOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `ref_ethnicity` */

insert  into `ref_ethnicity`(`RefEthnicID`,`RefEthnicTitle`,`RefEthnicOrder`) values (1,'Халх',1),(2,'Казак',2),(3,'Дөрвөд',3),(4,'Буриад',4),(5,'Баяд',5),(6,'Дарьганга',6),(7,'Урианхай',7),(8,'Захчин',8),(9,'Дархад',9),(10,'Торгууд',10),(11,'Өөлд',11),(12,'Барга',12),(13,'Үзэмчин',13),(14,'Харчин',14),(15,'Цахар',15),(16,'Хотогойд',16),(17,'Элжигэн',17),(18,'Цаатан',18),(19,'Сартуул',19);

/*Table structure for table `ref_holiday` */

DROP TABLE IF EXISTS `ref_holiday`;

CREATE TABLE `ref_holiday` (
  `RefHolidayID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefHolidayYear` year(4) DEFAULT NULL,
  `RefHolidayType` smallint(6) DEFAULT NULL,
  `RefHolidayTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefHolidayDateStart` varchar(10) DEFAULT NULL,
  `RefHolidayDateEnd` varchar(10) DEFAULT NULL,
  `RefHolidayCreatePersonID` int(11) DEFAULT NULL,
  `RefHolidayCreateEmployeeID` int(11) DEFAULT NULL,
  `RefHolidayCreateDate` datetime DEFAULT NULL,
  `RefHolidayUpdatePersonID` int(11) DEFAULT NULL,
  `RefHolidayUpdateEmployeeID` int(11) DEFAULT NULL,
  `RefHolidayUpdateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`RefHolidayID`),
  KEY `HolidayType` (`RefHolidayType`),
  KEY `RefHolidayYear` (`RefHolidayYear`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `ref_holiday` */

insert  into `ref_holiday`(`RefHolidayID`,`RefHolidayYear`,`RefHolidayType`,`RefHolidayTitle`,`RefHolidayDateStart`,`RefHolidayDateEnd`,`RefHolidayCreatePersonID`,`RefHolidayCreateEmployeeID`,`RefHolidayCreateDate`,`RefHolidayUpdatePersonID`,`RefHolidayUpdateEmployeeID`,`RefHolidayUpdateDate`) values (1,2021,1,'Шинэ жил','2021-01-01','2021-01-01',1,1,'2021-09-22 18:18:25',NULL,NULL,NULL),(2,2021,1,'Их Эзэн Чингис хааны өдөр','2021-11-05','2021-11-05',1,1,'2021-09-22 18:20:15',1,1,'2021-09-22 18:43:01'),(3,2021,1,'Бүгд найрамдах улс тунхагласан өдөр','2021-11-26','2021-11-26',1,1,'2021-09-22 18:22:11',NULL,NULL,NULL),(4,2021,1,'Эмэгтэйчүүдийн эрхийг хамгаалах өдөр','2021-03-08','2021-03-08',1,1,'2021-09-22 18:23:20',NULL,NULL,NULL),(5,2021,1,'Хүүхдийн эрхийг хамгаалах олон улсын  өдөр','2021-06-01','2021-06-01',1,1,'2021-09-22 18:41:37',NULL,NULL,NULL),(6,2021,1,'Үндэсний эрх чөлөө, тусгаар тогтнолоо сэргээсний баярын өдөр','2021-12-29','2021-12-29',1,1,'2021-09-22 18:44:21',NULL,NULL,NULL),(7,2021,1,'Цагаан сар','2021-02-12','2021-02-14',1,1,'2021-09-22 18:45:30',NULL,NULL,NULL),(8,2021,1,'Бурхан багшийн Их дүйчин өдөр','2021-05-26','2021-05-26',1,1,'2021-09-22 18:46:45',NULL,NULL,NULL),(10,2021,1,'Үндэсний их баяр наадам','2021-07-11','2021-07-15',1,1,'2021-09-22 18:49:05',NULL,NULL,NULL),(11,2021,1,'Ерөнхийлөгчийн сонгууль','2021-06-09','2021-06-09',1,1,'2021-10-08 14:44:46',NULL,NULL,NULL);

/*Table structure for table `ref_holiday_addition` */

DROP TABLE IF EXISTS `ref_holiday_addition`;

CREATE TABLE `ref_holiday_addition` (
  `RefAdditionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefAdditionType` smallint(6) DEFAULT NULL,
  `RefAdditionTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefAdditionStart` int(11) DEFAULT '0',
  `RefAdditionEnd` int(11) DEFAULT '0',
  `RefAddition` int(11) DEFAULT NULL,
  `RefAdditionOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefAdditionID`),
  KEY `RefAdditionOrder` (`RefAdditionOrder`),
  KEY `RefAdditionType` (`RefAdditionType`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `ref_holiday_addition` */

insert  into `ref_holiday_addition`(`RefAdditionID`,`RefAdditionType`,`RefAdditionTitle`,`RefAdditionStart`,`RefAdditionEnd`,`RefAddition`,`RefAdditionOrder`) values (1,1,'0-60 сар - 15 хоног',0,60,15,1),(2,1,'60 сар 1 хоногоос 120 сар - 18',60,120,18,2),(3,1,'120 сар 1 хоногоос 180 сар - 20',120,180,20,3),(4,1,'180 сар 1 хоногоос 240 сар - 22',180,240,22,4),(5,1,'240 сар 1 хоногоос 300 сар - 24',240,300,24,5),(6,1,'300 сар 1 хоногоос 360 сар - 26',300,360,26,6),(7,1,'360 сар 1 хоногоос дээш - 29',360,80000,29,7),(8,2,'60 сар 1 хоногоос 120 сар - 3',60,120,3,1),(9,2,'120 сар 1 хоногоос 180 сар - 6',120,180,6,2),(10,2,'180 сар 1 хоногоос 240 сар - 9',180,240,9,3),(11,2,'240 сар 1 хоногоос 300 сар - 12',240,300,12,4),(12,2,'300 сар 1 хоногоос 360 сар - 15',300,360,15,5),(13,2,'360 сар 1 хоногоос дээш - 18',360,80000,18,6);

/*Table structure for table `ref_holiday_days` */

DROP TABLE IF EXISTS `ref_holiday_days`;

CREATE TABLE `ref_holiday_days` (
  `RefDayID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDayHolidayID` int(11) unsigned DEFAULT NULL,
  `RefDayYear` varchar(4) DEFAULT NULL,
  `RefDayDate` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`RefDayID`),
  KEY `HolidayType` (`RefDayHolidayID`),
  KEY `RefDayYear` (`RefDayYear`),
  KEY `RefDayDate` (`RefDayDate`),
  CONSTRAINT `ref_holiday_days_ibfk_1` FOREIGN KEY (`RefDayHolidayID`) REFERENCES `ref_holiday` (`RefHolidayID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Data for the table `ref_holiday_days` */

insert  into `ref_holiday_days`(`RefDayID`,`RefDayHolidayID`,`RefDayYear`,`RefDayDate`) values (1,1,'2021','2021-01-01'),(3,3,'2021','2021-11-26'),(4,4,'2021','2021-03-08'),(5,5,'2021','2021-06-01'),(6,2,'2021','2021-11-05'),(7,6,'2021','2021-12-29'),(8,7,'2021','2021-02-12'),(9,7,'2021','2021-02-13'),(10,7,'2021','2021-02-14'),(11,8,'2021','2021-05-26'),(17,10,'2021','2021-07-11'),(18,10,'2021','2021-07-12'),(19,10,'2021','2021-07-13'),(20,10,'2021','2021-07-14'),(21,10,'2021','2021-07-15'),(22,11,'2021','2021-06-09');

/*Table structure for table `ref_job_organ` */

DROP TABLE IF EXISTS `ref_job_organ`;

CREATE TABLE `ref_job_organ` (
  `RefOrganID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefOrganTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefOrganOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefOrganID`),
  KEY `OrganOrder` (`RefOrganOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ref_job_organ` */

insert  into `ref_job_organ`(`RefOrganID`,`RefOrganTitle`,`RefOrganOrder`) values (1,'Төрийн',1),(2,'Аж ахуйн нэгж',2),(3,'Төрийн болон орон нутгийн өмчит компани',3),(4,'Төрийн бус байгууллага',4),(5,'Олон улсын байгууллага',5),(6,'Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцсон',6);

/*Table structure for table `ref_job_organsub` */

DROP TABLE IF EXISTS `ref_job_organsub`;

CREATE TABLE `ref_job_organsub` (
  `RefOrganSubID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefOrganSubTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefOrganSubOrder` smallint(6) DEFAULT NULL,
  `RefOrganSubType` smallint(6) DEFAULT '0',
  PRIMARY KEY (`RefOrganSubID`),
  KEY `OrganSubOrder` (`RefOrganSubOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `ref_job_organsub` */

insert  into `ref_job_organsub`(`RefOrganSubID`,`RefOrganSubTitle`,`RefOrganSubOrder`,`RefOrganSubType`) values (1,'Авлигатай тэмцэх газар',1,1),(2,'Цагдаагийн байгууллага',2,2),(3,'Зэвсэгт хүчин',3,2),(4,'Хилийн цэргийн байгууллага',4,2),(5,'Дотоодын цэргийн байгууллага',5,2),(6,'Тагнуулын байгууллага',6,2),(7,'Онцгой байдлын асуудал эрхэлсэн байгууллага',7,2),(8,'Шүүх шинжилгээний байгууллага',8,2),(9,'Шүүхийн шийдвэр гүйцэтгэх байгууллага',9,2),(10,'Бусад',10,3);

/*Table structure for table `ref_job_organtype` */

DROP TABLE IF EXISTS `ref_job_organtype`;

CREATE TABLE `ref_job_organtype` (
  `RefOrganTypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefOrganTypeTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefOrganTypeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefOrganTypeID`),
  KEY `OrganTypeOrder` (`RefOrganTypeOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ref_job_organtype` */

insert  into `ref_job_organtype`(`RefOrganTypeID`,`RefOrganTypeTitle`,`RefOrganTypeOrder`) values (1,'Цэргийн байгууллага',1),(2,'Энгийн байгууллага',2);

/*Table structure for table `ref_job_position` */

DROP TABLE IF EXISTS `ref_job_position`;

CREATE TABLE `ref_job_position` (
  `RefPositionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefPositionTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefPositionOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefPositionID`),
  KEY `OrganOrder` (`RefPositionOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `ref_job_position` */

insert  into `ref_job_position`(`RefPositionID`,`RefPositionTitle`,`RefPositionOrder`) values (1,'Генерал',1),(2,'Офицер',2),(3,'Ахлагч',3),(4,'Түрүүч, байлдагч',4),(5,'Цэрэг, цагдаагийн сургуулийн сонсогч',12),(6,'Мөрдөгч',8),(7,'Тусгайлан эрх олгогдсон ажилтан',11),(8,'Хэлтсийн дарга',5),(9,'Албаны дарга',6),(10,'Ахлах мөрдөгч',7),(11,'Тусгайлан эрх олгогдсон ахлах ажилтан',10),(12,'Гүйцэтгэх ажилтан',9);

/*Table structure for table `ref_job_type` */

DROP TABLE IF EXISTS `ref_job_type`;

CREATE TABLE `ref_job_type` (
  `RefTypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefTypeTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefTypeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefTypeID`),
  KEY `TypeOrder` (`RefTypeOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `ref_job_type` */

insert  into `ref_job_type`(`RefTypeID`,`RefTypeTitle`,`RefTypeOrder`) values (1,'Төрийн албан хаагч',1),(2,'Төрийн болон орон нутгийн өмчийн оролцоотой ААН-д',2),(3,'Хувийн хэвшлийн байгууллагад ажиллагч',3),(4,'Төрийн бус байгууллагын ажилтан',4),(5,'Олон улсын байгууллагын ажилтан',5),(6,'Мал аж ахуй эрхлэгч',6),(7,'Хувиараа хөдөлмөр эрхлэгч',7),(8,'Ажилгүй',8),(9,'Оюутан',9),(10,'Тэтгэвэрт',10),(11,'Сурагч',11),(12,'Бага насны хүүхэд',12);

/*Table structure for table `ref_language` */

DROP TABLE IF EXISTS `ref_language`;

CREATE TABLE `ref_language` (
  `RefLanguageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefLanguageTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefLanguageOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefLanguageID`),
  KEY `LanguageOrder` (`RefLanguageOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `ref_language` */

insert  into `ref_language`(`RefLanguageID`,`RefLanguageTitle`,`RefLanguageOrder`) values (1,'Англи',1),(2,'Орос',2),(3,'Хятад',3),(4,'Солонгос',4),(5,'Япон',5),(6,'Араб',6),(7,'Испани',7),(8,'Бусад',8);

/*Table structure for table `ref_language_level` */

DROP TABLE IF EXISTS `ref_language_level`;

CREATE TABLE `ref_language_level` (
  `RefLevelID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefLevelTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefLevelOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefLevelID`),
  KEY `LevelOrder` (`RefLevelOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ref_language_level` */

insert  into `ref_language_level`(`RefLevelID`,`RefLevelTitle`,`RefLevelOrder`) values (1,'Бага',1),(2,'Дунд',2),(3,'Ахисан дунд',3),(4,'Ахисан',4);

/*Table structure for table `ref_pos_rank` */

DROP TABLE IF EXISTS `ref_pos_rank`;

CREATE TABLE `ref_pos_rank` (
  `RefRankID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefRankTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefRankOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefRankID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ref_pos_rank` */

insert  into `ref_pos_rank`(`RefRankID`,`RefRankTitle`,`RefRankOrder`) values (1,'Тэргүүн комиссар',1),(2,'Эрхэлсэн комиссар',2),(3,'Ахлах комиссар',3),(4,'Комиссар',4),(5,'Дэд комиссар',5);

/*Table structure for table `ref_position_class` */

DROP TABLE IF EXISTS `ref_position_class`;

CREATE TABLE `ref_position_class` (
  `RefClassID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefClassTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefClassOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefClassID`),
  KEY `ClassOrder` (`RefClassOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ref_position_class` */

insert  into `ref_position_class`(`RefClassID`,`RefClassTitle`,`RefClassOrder`) values (1,'Төрийн тусгай',1),(2,'Төрийн үйлчилгээ',2);

/*Table structure for table `ref_position_degree` */

DROP TABLE IF EXISTS `ref_position_degree`;

CREATE TABLE `ref_position_degree` (
  `RefDegreeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDegreeTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDegreeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefDegreeID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ref_position_degree` */

insert  into `ref_position_degree`(`RefDegreeID`,`RefDegreeTitle`,`RefDegreeOrder`) values (1,'Тэргүүн комиссар',1),(2,'Эрхэлсэн комиссар',2),(3,'Ахлах комиссар',3),(4,'Комиссар',4),(5,'Дэд комиссар',5),(6,'--- Зэрэг, дэвгүй ---',6);

/*Table structure for table `ref_position_rank` */

DROP TABLE IF EXISTS `ref_position_rank`;

CREATE TABLE `ref_position_rank` (
  `RefRankID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefRankTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefRankOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefRankID`),
  KEY `RankOrder` (`RefRankOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ref_position_rank` */

insert  into `ref_position_rank`(`RefRankID`,`RefRankTitle`,`RefRankOrder`) values (1,'ТҮ-1',2),(2,'ТҮ-2',3),(3,'ТҮ-3',4),(4,'ТҮ-4',5),(5,'ТҮ-5',6),(6,'ТТ',1);

/*Table structure for table `ref_position_type` */

DROP TABLE IF EXISTS `ref_position_type`;

CREATE TABLE `ref_position_type` (
  `RefTypeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefTypeTitle` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefTypeTitleShort` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefTypeOrder` smallint(6) DEFAULT '0',
  `RefTypeClass` smallint(6) DEFAULT '0',
  PRIMARY KEY (`RefTypeID`),
  KEY `RefTypeOrder` (`RefTypeOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `ref_position_type` */

insert  into `ref_position_type`(`RefTypeID`,`RefTypeTitle`,`RefTypeTitleShort`,`RefTypeOrder`,`RefTypeClass`) values (1,'Төсвийн ерөнхийлөн захирагч','ТЕЗ',1,1),(2,'Төсвийн шууд захирагч','ТШЗ',3,1),(3,'Нэгжийн менежер','Нэгжийн менежер',5,1),(4,'Төрийн албан хаагч','Албан хаагч',6,2),(5,'Төсвийн ерөнхийлөн захирагчийн орлогч','ТЕЗ-ийн орлогч',2,1),(6,'Төсвийн шууд захирагчийн орлогч','ТШЗ-ийн орлогч',4,1),(7,'Туслах албан хаагч','Туслах албан хаагч',7,3),(9,'Нэгжийн менежер / ТӨҮГ, ТӨХК, ОНӨҮГ ... /','Нэгжийн менежер / ТӨҮГ, ТӨХК, ОНӨҮГ ... /',9,0),(10,'Дэд захирал / ТӨҮГ, ТӨХК, ОНӨҮГ ... /','Дэд захирал / ТӨҮГ, ТӨХК, ОНӨҮГ ... /',8,0),(11,'Захирал / ТӨҮГ, ТӨХК, ОНӨҮГ ... /','Захирал / ТӨҮГ, ТӨХК, ОНӨҮГ ... /',10,0);

/*Table structure for table `ref_punishment` */

DROP TABLE IF EXISTS `ref_punishment`;

CREATE TABLE `ref_punishment` (
  `RefPunishmentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefPunishmentTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefPunishmentOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefPunishmentID`),
  KEY `PunishmentOrder` (`RefPunishmentOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ref_punishment` */

insert  into `ref_punishment`(`RefPunishmentID`,`RefPunishmentTitle`,`RefPunishmentOrder`) values (1,'Сануулах',1),(2,'Цалингийн хэмжээ бууруулах',2),(3,'Төрийн албанаас халах',3),(4,'Төрийн албанд 3 жилийн хугацаанд эргэж орох эрхгүйгээр халах',4);

/*Table structure for table `ref_relation` */

DROP TABLE IF EXISTS `ref_relation`;

CREATE TABLE `ref_relation` (
  `RefRelationID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefRelationTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefRelationOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefRelationID`),
  KEY `RelationOrder` (`RefRelationOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `ref_relation` */

insert  into `ref_relation`(`RefRelationID`,`RefRelationTitle`,`RefRelationOrder`) values (1,'Аав',1),(2,'Ээж',2),(3,'Нөхөр',3),(4,'Эхнэр',4),(5,'Ах',5),(6,'Эгч',6),(7,'Дүү',7),(8,'Хүү',8),(9,'Охин',9),(10,'Өвөө',10),(11,'Эмээ',11),(12,'Хүргэн',12),(13,'Бэр',13),(14,'Хүргэн ах',14),(15,'Бэр эгч',15),(16,'Ач хүү',16),(17,'Ач охин',17),(18,'Зээ хүү',18),(19,'Зээ охин',19),(20,'Хадам аав',20),(21,'Хадам ээж',21),(22,'Хадам ах',22),(23,'Хадам эгч',23),(24,'Хадам дүү',24),(25,'Нагац ах',25),(26,'Нагац эгч',26),(27,'Нагац дүү',27),(28,'Өвөг эцэг',28),(29,'Өвөг эх',29);

/*Table structure for table `ref_salary_condition` */

DROP TABLE IF EXISTS `ref_salary_condition`;

CREATE TABLE `ref_salary_condition` (
  `RefConditionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefConditionTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefConditionPercent` smallint(6) DEFAULT NULL,
  `RefConditionOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefConditionID`),
  KEY `SalaryOrder` (`RefConditionOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ref_salary_condition` */

insert  into `ref_salary_condition`(`RefConditionID`,`RefConditionTitle`,`RefConditionPercent`,`RefConditionOrder`) values (1,'АТГ-ын дарга, дэд дарга, хэлтэс, албаны дарга, хөрөнгө, орлогын мэдүүлгийг хянан шалгах, хэрэг бүртгэх, мөдөн байцаах, гүйцэтгэх ажил эрхэлсэн албан хаагч - 40 хувь',40,1),(2,'Бусад албан хаагчид - 25 хувь',25,2);

/*Table structure for table `ref_salary_degree` */

DROP TABLE IF EXISTS `ref_salary_degree`;

CREATE TABLE `ref_salary_degree` (
  `RefDegreeID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDegreePositionID` int(11) DEFAULT NULL,
  `RefDegreeTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDegreePercent` smallint(11) DEFAULT NULL,
  `RefDegreeOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefDegreeID`),
  KEY `SalaryOrder` (`RefDegreeOrder`),
  KEY `RefDegreePositionID` (`RefDegreePositionID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ref_salary_degree` */

insert  into `ref_salary_degree`(`RefDegreeID`,`RefDegreePositionID`,`RefDegreeTitle`,`RefDegreePercent`,`RefDegreeOrder`) values (1,1,'Тэргүүн комиссар - 35 хувь',35,1),(2,2,'Эрхэлсэн комиссар - 32 хувь',32,2),(3,3,'Ахлах комиссар - 28 хувь',28,3),(4,4,'Комиссар - 25 хувь',25,4),(5,5,'Дэд комиссар - 20 хувь',20,5);

/*Table structure for table `ref_salary_edu` */

DROP TABLE IF EXISTS `ref_salary_edu`;

CREATE TABLE `ref_salary_edu` (
  `RefEduID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefEduTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefEduPercent` smallint(6) DEFAULT NULL,
  `RefEduOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefEduID`),
  KEY `SalaryEduOrder` (`RefEduOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ref_salary_edu` */

insert  into `ref_salary_edu`(`RefEduID`,`RefEduTitle`,`RefEduPercent`,`RefEduOrder`) values (1,'Шинжлэх ухааны доктор - 15 хувь',15,1),(2,'Боловсролын доктор - 10 хувь',10,2);

/*Table structure for table `ref_salary_percent` */

DROP TABLE IF EXISTS `ref_salary_percent`;

CREATE TABLE `ref_salary_percent` (
  `RefPercentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefPercentTitle` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefPercentStart` smallint(6) DEFAULT '0',
  `RefPercentEnd` smallint(6) DEFAULT '0',
  `RefPercent` smallint(6) DEFAULT NULL,
  `RefPercentOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefPercentID`),
  KEY `SalaryPercentOrder` (`RefPercentOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `ref_salary_percent` */

insert  into `ref_salary_percent`(`RefPercentID`,`RefPercentTitle`,`RefPercentStart`,`RefPercentEnd`,`RefPercent`,`RefPercentOrder`) values (1,'5-10 жил (61-120 сар) - 5%',60,119,5,1),(2,'11-15 жил (121-180 сар) - 10%',120,179,10,2),(3,'16-20 жил (181-240 сар) - 15%',180,239,15,3),(4,'21-25 жил (241-300 сар) - 20%',240,299,20,4),(5,'26, түүнээс дээш (301 ба түүнээс дээш сар) - 25%',300,2400,25,5);

/*Table structure for table `ref_soldier` */

DROP TABLE IF EXISTS `ref_soldier`;

CREATE TABLE `ref_soldier` (
  `RefSoldierID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefSoldierTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefSoldierOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefSoldierID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `ref_soldier` */

insert  into `ref_soldier`(`RefSoldierID`,`RefSoldierTitle`,`RefSoldierOrder`) values (1,'Хугацаат цэргийн алба',1),(2,'Цэргийн гэрээт алба',2),(3,'Оюутан цэрэг',3),(4,'Цэргийн дүйцүүлэх алба',4);

/*Table structure for table `ref_study_direction` */

DROP TABLE IF EXISTS `ref_study_direction`;

CREATE TABLE `ref_study_direction` (
  `RefDirectionID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDirectionTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDirectionOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefDirectionID`),
  KEY `DirectionOrder` (`RefDirectionOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `ref_study_direction` */

insert  into `ref_study_direction`(`RefDirectionID`,`RefDirectionTitle`,`RefDirectionOrder`) values (1,'Гадаад хэл',4),(2,'Мөрдөн шалгах',7),(3,'Хууль эрх зүй',6),(4,'Санхүү, аудит',8),(5,'Мэдээллийн технологи',9),(6,'Бусад',10),(7,'Удирдлагын Академид зохион байгуулагдсан сургалт',1),(8,'Бусад байгууллагаас зохион байгуулсан сургалт ',2),(9,'Гадаад сургалт',3),(10,'Магистр, докторын сургалт',5);

/*Table structure for table `ref_study_direction_sub` */

DROP TABLE IF EXISTS `ref_study_direction_sub`;

CREATE TABLE `ref_study_direction_sub` (
  `RefDirSubID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDirSubDirectionID` int(11) unsigned DEFAULT NULL,
  `RefDirSubTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDirSubOrder` smallint(6) DEFAULT NULL,
  `RefDirSubCountSub1` smallint(6) DEFAULT '0',
  PRIMARY KEY (`RefDirSubID`),
  KEY `RefDirSubDirectionID` (`RefDirSubDirectionID`),
  KEY `RefDirSubOrder` (`RefDirSubOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ref_study_direction_sub` */

insert  into `ref_study_direction_sub`(`RefDirSubID`,`RefDirSubDirectionID`,`RefDirSubTitle`,`RefDirSubOrder`,`RefDirSubCountSub1`) values (1,7,'Богино болон дунд хугацааны сургалт',1,3),(2,7,'Мэргэшүүлэх багц сургалт',2,3),(3,7,'Засгийн газар, төрийн албаны төв байгууллага болон төрийн бусад байгууллагын захиалга, цаг үеийн эрэлт шаардлагад үндэслэн явуулсан зорилтот сургалт',3,3),(4,8,'Хуульчдын холбооноос зохион байгуулсан сургалт',1,1),(5,8,'Сангийн яамнаас зохион байгуулсан сургалт',2,4),(6,10,'Байгууллагын захиалгаар',1,2),(7,10,'Өөрсдийн санаачлагаар',2,2);

/*Table structure for table `ref_study_direction_sub1` */

DROP TABLE IF EXISTS `ref_study_direction_sub1`;

CREATE TABLE `ref_study_direction_sub1` (
  `RefDirSub1ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefDirSub1DirectionID` int(11) unsigned DEFAULT NULL,
  `RefDirSub1DirSubID` int(11) unsigned DEFAULT NULL,
  `RefDirSub1Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefDirSub1Order` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefDirSub1ID`),
  KEY `RefDirSub1DirectionID` (`RefDirSub1DirectionID`),
  KEY `RefDirSub1Order` (`RefDirSub1Order`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `ref_study_direction_sub1` */

insert  into `ref_study_direction_sub1`(`RefDirSub1ID`,`RefDirSub1DirectionID`,`RefDirSub1DirSubID`,`RefDirSub1Title`,`RefDirSub1Order`) values (1,7,1,'Чиглүүлэх богино хугацааны сургалт',1),(2,7,1,'Мэргэшүүлэх дунд хугацааны сургалт',2),(3,7,1,'Мэргэшүүлэх дунд хугацааны давтан сургалт',3),(4,7,2,'Ахлах түшмэлийн сургалт',1),(5,7,2,'Эрхэлсэн түшмэлийн сургалт',2),(6,7,2,'Тэргүүн түшмэлийн сургалт',3),(7,7,3,'Төрийн албан хаагчийн богино хугацааны зорилтот сургалт',1),(8,7,3,'Төрийн албаны зөвлөлийн зохион байгуулсан сургалт',2),(9,7,3,'Мэргэжлийн бусад байгууллагын сургалт',3),(10,8,4,'Хуульчийн үргэлжилсэн сургалт ',1),(11,8,5,'Суурь мэдлэг олгох сургалт /А3-3 өдөр/',1),(12,8,5,'Сургагч багшийн сургалт /Б5-5 өдөр/',2),(13,8,5,'Суурь мэдлэг олгох цахим сургалт /А3-Цахим/',3),(14,8,5,'Бусад сургалт',4),(15,9,0,'АТГ-аас зохион байгуулсан',1),(16,9,0,'Бусад байгууллагаас зохион байгуулсан',2),(17,1,0,'Англи',1),(18,1,0,'Орос',2),(19,1,0,'Хятад',3),(20,1,0,'Бусад\r\n\r\n',4),(21,10,6,'Магистрын \r\n\r\n',1),(22,10,6,'Докторын \r\n\r\n',2),(23,10,7,'Магистрын \r\n\r\n',1),(24,10,7,'Докторын \r\n\r\n',2);

/*Table structure for table `ref_trip` */

DROP TABLE IF EXISTS `ref_trip`;

CREATE TABLE `ref_trip` (
  `RefTripID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RefTripTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `RefTripOrder` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`RefTripID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ref_trip` */

insert  into `ref_trip`(`RefTripID`,`RefTripTitle`,`RefTripOrder`) values (1,'Орон нутаг',1),(2,'Гадаад оронд',2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
