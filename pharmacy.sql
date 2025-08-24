-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2025 at 07:38 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `EXPIRY`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EXPIRY` ()  NO SQL BEGIN
SELECT p_id,sup_id,med_id,p_qty,p_cost,pur_date,mfg_date,exp_date FROM purchase where exp_date between CURDATE() and DATE_SUB(CURDATE(), INTERVAL -6 MONTH);
END$$

DROP PROCEDURE IF EXISTS `SEARCH_INVENTORY`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SEARCH_INVENTORY` (IN `search` VARCHAR(255))  NO SQL BEGIN
DECLARE mid DECIMAL(6);
DECLARE mname VARCHAR(50);
DECLARE mqty INT;
DECLARE mcategory VARCHAR(20);
DECLARE mprice DECIMAL(6,2);
DECLARE location VARCHAR(30);
DECLARE exit_loop BOOLEAN DEFAULT FALSE;
DECLARE MED_CURSOR CURSOR FOR SELECT MED_ID,MED_NAME,MED_QTY,CATEGORY,MED_PRICE,LOCATION_RACK FROM MEDS;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop=TRUE;
CREATE TEMPORARY TABLE IF NOT EXISTS T1 (medid decimal(6),medname varchar(50),medqty int,medcategory varchar(20),medprice decimal(6,2),medlocation varchar(30));
OPEN MED_CURSOR;
med_loop: LOOP
FETCH FROM MED_CURSOR INTO mid,mname,mqty,mcategory,mprice,location;
IF exit_loop THEN
LEAVE med_loop;
END IF;

IF(CONCAT(mid,mname,mcategory,location) LIKE CONCAT('%',search,'%')) THEN
INSERT INTO T1(medid,medname,medqty,medcategory,medprice,medlocation)
VALUES(mid,mname,mqty,mcategory,mprice,location);
END IF;
END LOOP med_loop;
CLOSE MED_CURSOR;
SELECT medid,medname,medqty,medcategory,medprice,medlocation FROM T1; 
END$$

DROP PROCEDURE IF EXISTS `STOCK`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `STOCK` ()  NO SQL BEGIN
SELECT med_id, med_name,med_qty,category,med_price,location_rack FROM meds where med_qty<=50;
END$$

DROP PROCEDURE IF EXISTS `TOTAL_AMT`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `TOTAL_AMT` (IN `ID` INT, OUT `AMT` DECIMAL(8,2))  NO SQL BEGIN
UPDATE SALES SET S_DATE=SYSDATE(),S_TIME=CURRENT_TIMESTAMP(),TOTAL_AMT=(SELECT SUM(TOT_PRICE) FROM SALES_ITEMS WHERE SALES_ITEMS.SALE_ID=ID) WHERE SALES.SALE_ID=ID;
SELECT TOTAL_AMT INTO AMT FROM SALES WHERE SALE_ID=ID;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `P_AMT`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `P_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) DETERMINISTIC NO SQL BEGIN
DECLARE PAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(P_COST) INTO PAMT FROM PURCHASE WHERE PUR_DATE >= start AND PUR_DATE<= end;
RETURN PAMT;
END$$

DROP FUNCTION IF EXISTS `S_AMT`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `S_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) NO SQL BEGIN
DECLARE SAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(TOTAL_AMT) INTO SAMT FROM SALES WHERE S_DATE >= start AND S_DATE<= end;
RETURN SAMT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID` decimal(7,0) NOT NULL,
  `A_USERNAME` varchar(50) NOT NULL,
  `A_PASSWORD` varchar(50) NOT NULL,
  PRIMARY KEY (`A_USERNAME`),
  UNIQUE KEY `USERNAME` (`A_USERNAME`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `A_USERNAME`, `A_PASSWORD`) VALUES
(1, 'admin', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `med_id` decimal(6,0) NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `med_id` (`med_id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `C_ID` decimal(6,0) NOT NULL,
  `C_FNAME` varchar(30) NOT NULL,
  `C_LNAME` varchar(30) DEFAULT NULL,
  `C_AGE` int NOT NULL,
  `C_SEX` varchar(6) NOT NULL,
  `C_PHNO` decimal(10,0) NOT NULL,
  `C_MAIL` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`C_ID`),
  UNIQUE KEY `C_PHNO` (`C_PHNO`),
  UNIQUE KEY `C_MAIL` (`C_MAIL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`C_ID`, `C_FNAME`, `C_LNAME`, `C_AGE`, `C_SEX`, `C_PHNO`, `C_MAIL`) VALUES
(987101, 'Safia', 'Malik', 22, 'Female', 9632587415, 'safia@gmail.com'),
(987102, 'Varun', 'Ilango', 24, 'Male', 9987565423, 'varun@gmail.com'),
(987103, 'Suja', 'Suresh', 45, 'Female', 7896541236, 'suja@hotmail.com'),
(987104, 'Agatha', 'Elizabeth', 30, 'Female', 7845129635, 'agatha@gmail.com'),
(987105, 'Zayed', 'Shah', 40, 'Male', 6789541235, 'zshah@hotmail.com'),
(987106, 'Vijay', 'Kumar', 60, 'Male', 8996574123, 'vijayk@yahoo.com'),
(987107, 'Meera', 'Das', 35, 'Female', 7845963259, 'meera@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_FNAME` varchar(30) NOT NULL,
  `E_LNAME` varchar(30) DEFAULT NULL,
  `BDATE` date NOT NULL,
  `E_AGE` int NOT NULL,
  `E_GENDER` varchar(6) NOT NULL,
  `E_TYPE` varchar(20) NOT NULL,
  `E_JDATE` date NOT NULL,
  `E_SAL` decimal(8,2) NOT NULL,
  `E_PHNO` decimal(10,0) NOT NULL,
  `E_MAIL` varchar(40) DEFAULT NULL,
  `E_ADD` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`E_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`E_ID`, `E_FNAME`, `E_LNAME`, `BDATE`, `E_AGE`, `E_GENDER`, `E_TYPE`, `E_JDATE`, `E_SAL`, `E_PHNO`, `E_MAIL`, `E_ADD`) VALUES
(1, 'Admin', '-', '1989-05-24', 30, 'Female', 'Admin', '2009-06-24', 95000.00, 9874563219, 'admin@pharmacia.com', 'Chennai'),
(4567001, 'Varsha', 'Ajith', '1995-10-05', 25, 'Female', 'Pharmacist', '2017-11-12', 26000.00, 9967845123, 'evarsh@hotmail.com', 'Trivandrum'),
(4567002, 'Anita', 'Hari', '2000-10-03', 20, 'Female', 'Pharmacist', '2012-10-06', 45000.00, 8546123566, 'anita@gmail.com', 'Kochi'),
(4567003, 'Varsha', 'Rajan', '1998-02-01', 22, 'Male', 'Pharmacist', '2019-07-06', 21000.00, 7854123694, 'harishraja@live.com', 'Kottayam'),
(4567005, 'Amaya', 'P', '1992-01-02', 28, 'Female', 'Pharmacist', '2017-05-16', 32000.00, 7894532165, 'amaya@gmail.com', 'Kollam'),
(4567006, 'Shoaib', 'Ahmed', '1999-12-11', 20, 'Male', 'Pharmacist', '2018-09-05', 28000.00, 7896541234, 'shoaib@hotmail.com', 'Changnassery'),
(4567009, 'Shayla', 'Hussain', '1980-02-28', 40, 'Female', 'Pharmacist', '2010-05-06', 80000.00, 7854123695, 'shaylah@gmail.com', 'Muthukulam'),
(4567010, 'Daniel', 'James', '1993-04-05', 27, 'Male', 'Pharmacist', '2016-01-05', 30000.00, 7896541235, 'daniels@gmail.com', 'Allapuzha');

-- --------------------------------------------------------

--
-- Table structure for table `meds`
--

DROP TABLE IF EXISTS `meds`;
CREATE TABLE IF NOT EXISTS `meds` (
  `MED_ID` decimal(6,0) NOT NULL,
  `MED_NAME` varchar(50) NOT NULL,
  `MED_QTY` int NOT NULL,
  `CATEGORY` varchar(20) DEFAULT NULL,
  `MED_PRICE` decimal(6,2) NOT NULL,
  `LOCATION_RACK` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`MED_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `meds`
--

INSERT INTO `meds` (`MED_ID`, `MED_NAME`, `MED_QTY`, `CATEGORY`, `MED_PRICE`, `LOCATION_RACK`) VALUES
(123001, 'Dolo 650 MG', 413, 'Tablet', 1.00, 'rack 5'),
(123002, 'Panadol Cold & Flu', 200, 'Tablet', 2.50, 'rack 6'),
(123003, 'Livogen', 200, 'Capsule', 5.00, 'rack 3'),
(123004, 'Gelusil', 353, 'Tablet', 1.25, 'rack 4'),
(123005, 'Cyclopam', 199, 'Tablet', 6.00, 'rack 2'),
(123006, 'Benadryl 200 ML', 200, 'Syrup', 50.00, 'rack 10'),
(123007, 'Lopamide', 200, 'Capsule', 5.00, 'rack 7'),
(123008, 'Vitamic C', 200, 'Tablet', 3.00, 'rack 8'),
(123009, 'Omeprazole', 200, 'Capsule', 4.00, 'rack 3'),
(123010, 'Concur 5 MG', 201, 'Tablet', 3.50, 'rack 9'),
(123011, 'Augmentin 250 ML', 200, 'Syrup', 80.00, 'rack 7'),
(123012, 'Paracetamol 500 MG', 189, 'Tablet', 1.00, 'rack 1'),
(123013, 'Azithromycin 500 MG', 200, 'Tablet', 12.00, 'rack 1'),
(123014, 'Ciprofloxacin 500 MG', 200, 'Tablet', 10.00, 'rack 1'),
(123015, 'Cetirizine 10 MG', 200, 'Tablet', 2.00, 'rack 2'),
(123016, 'Allegra 120 MG', 146, 'Tablet', 7.00, 'rack 2'),
(123017, 'Crocin Advance', 200, 'Tablet', 1.00, 'rack 2'),
(123018, 'Zincovit', 200, 'Tablet', 3.00, 'rack 2'),
(123019, 'Limcee Vitamin C', 200, 'Tablet', 2.50, 'rack 2'),
(123020, 'Disprin', 200, 'Tablet', 1.50, 'rack 3'),
(123021, 'Ascoril Cough Syrup', 150, 'Syrup', 60.00, 'rack 10'),
(123022, 'Grilinctus', 150, 'Syrup', 70.00, 'rack 10'),
(123023, 'Ambrodil', 90, 'Syrup', 55.00, 'rack 10'),
(123024, 'Tixylix Baby Syrup', 100, 'Syrup', 50.00, 'rack 10'),
(123025, 'Zandu Pancharishta', 60, 'Syrup', 120.00, 'rack 10'),
(123026, 'Becosules', 200, 'Capsule', 5.00, 'rack 3'),
(123027, 'Rantac 150', 200, 'Capsule', 3.00, 'rack 3'),
(123028, 'Evion 400', 200, 'Capsule', 4.00, 'rack 3'),
(123029, 'Doxycycline', 200, 'Capsule', 6.00, 'rack 3'),
(123030, 'Vitamin B12 Injection', 200, 'Injection', 30.00, 'rack 12'),
(123031, 'Diclofenac Injection', 50, 'Injection', 15.00, 'rack 12'),
(123032, 'Insulin 100 IU', 86, 'Injection', 200.00, 'rack 12'),
(123033, 'Tetanus Toxoid', 50, 'Injection', 10.00, 'rack 12'),
(123034, 'Digene', 200, 'Tablet', 2.00, 'rack 4'),
(123035, 'ENO Lemon', 200, 'Tablet', 5.00, 'rack 4'),
(123036, 'Gaviscon', 100, 'Syrup', 90.00, 'rack 4'),
(123037, 'Ibuprofen 400 MG', 200, 'Tablet', 1.50, 'rack 5'),
(123038, 'Combiflam', 200, 'Tablet', 2.50, 'rack 5'),
(123039, 'Brufen 400', 200, 'Tablet', 2.00, 'rack 5'),
(123040, 'Glucon-D', 100, 'Powder', 30.00, 'rack 8'),
(123041, 'Glucometer Strips', 100, 'Device', 300.00, 'rack 8'),
(125000, 'Stethoscope XL', 370, 'Device', 424.46, 'rack 10'),
(125001, 'Pulse Oximeter', 334, 'Device', 337.23, 'rack 13'),
(125002, 'Cetirizine XL', 109, 'Tablet', 58.72, 'rack 6'),
(125003, 'Telmisartan 100mg', 347, 'Tablet', 93.62, 'rack 12'),
(125004, 'Telmisartan 200mg', 281, 'Tablet', 466.72, 'rack 7'),
(125005, 'Digital Thermometer', 297, 'Device', 204.33, 'rack 1'),
(125006, 'Nebulizer Machine', 137, 'Device', 1225.00, 'rack 14'),
(125007, 'Omron BP Monitor', 147, 'Device', 1880.50, 'rack 14'),
(125008, 'Lansoprazole 15mg', 128, 'Capsule', 87.45, 'rack 2'),
(125009, 'Paracetamol 500mg', 890, 'Tablet', 1.50, 'rack 5'),
(125010, 'Metformin 500mg', 570, 'Tablet', 2.10, 'rack 5'),
(125012, 'Ibuprofen 400mg', 669, 'Tablet', 2.25, 'rack 6'),
(125013, 'Azithromycin 250mg', 420, 'Tablet', 14.00, 'rack 6'),
(125014, 'Azithromycin 500mg', 388, 'Tablet', 28.00, 'rack 6'),
(125015, 'Cough Syrup 100ml', 300, 'Syrup', 55.00, 'rack 3'),
(125016, 'Multivitamin Tablets', 640, 'Tablet', 5.00, 'rack 8'),
(125017, 'Iron Folic Acid', 500, 'Tablet', 4.00, 'rack 8'),
(125018, 'Calcium + D3', 450, 'Tablet', 6.50, 'rack 8'),
(125019, 'ORS Sachets', 900, 'Sachet', 2.00, 'rack 9'),
(125020, 'Insulin Injection', 209, 'Injection', 150.00, 'rack 11'),
(125021, 'Bandages Pack', 700, 'Bandage', 25.00, 'rack 4'),
(125022, 'Antiseptic Liquid 100ml', 550, 'Liquid', 35.00, 'rack 4'),
(125023, 'Surgical Gloves (Pair)', 320, 'Device', 15.00, 'rack 10'),
(125024, 'Face Masks (Box of 50)', 200, 'Device', 100.00, 'rack 10'),
(125025, 'Betadine Ointment 15g', 230, 'Ointment', 38.00, 'rack 4'),
(125026, 'Diclofenac Gel 30g', 290, 'Gel', 45.00, 'rack 4'),
(125027, 'Pantoprazole 40mg', 610, 'Tablet', 6.00, 'rack 6'),
(125028, 'Amoxicillin 500mg', 391, 'Capsule', 4.00, 'rack 6'),
(125030, 'Levocetirizine 5mg', 430, 'Tablet', 3.00, 'rack 6'),
(125031, 'Chlorpheniramine 4mg', 380, 'Tablet', 1.50, 'rack 6'),
(125032, 'Ranitidine 150mg', 280, 'Tablet', 2.20, 'rack 5'),
(125033, 'Dicyclomine 10mg', 360, 'Tablet', 3.50, 'rack 5'),
(125034, 'Nimesulide 100mg', 310, 'Tablet', 3.00, 'rack 5'),
(125035, 'Zincovit', 470, 'Tablet', 5.00, 'rack 8'),
(125037, 'ORS Liquid 200ml', 480, 'Liquid', 12.00, 'rack 9'),
(125038, 'Cotton Roll 500g', 340, 'Bandage', 60.00, 'rack 4'),
(125039, 'Adhesive Plaster (small)', 31, 'Bandage', 1.00, 'rack 4'),
(125040, 'Hydrogen Peroxide 100ml', 240, 'Liquid', 25.00, 'rack 4'),
(125041, 'Clotrimazole Cream 15g', 300, 'Cream', 30.00, 'rack 4'),
(125042, 'Syringe 5ml (pack of 10)', 400, 'Device', 40.00, 'rack 11'),
(125043, 'Glucometer Strips (50)', 150, 'Device', 550.00, 'rack 11'),
(125044, 'Pregnancy Test Kit', 180, 'Device', 35.00, 'rack 11'),
(125045, 'Ear Drops 10ml', 270, 'Drops', 22.00, 'rack 3'),
(125046, 'Eye Drops 10ml', 260, 'Drops', 32.00, 'rack 3'),
(125047, 'Nasal Spray 10ml', 190, 'Spray', 45.00, 'rack 3'),
(125048, 'Saline Water 100ml', 220, 'Liquid', 20.00, 'rack 3'),
(125049, 'Hand Sanitizer 100ml', 390, 'Liquid', 45.00, 'rack 10'),
(125050, 'Vicks Vapo Rub', 409, 'Balm', 85.00, 'rack 4'),
(125052, 'Digene Tablets', 530, 'Tablet', 1.50, 'rack 5'),
(125053, 'Dolo 500mg', 619, 'Tablet', 1.20, 'rack 5'),
(125054, 'Cetrimide Powder 100g', 190, 'Powder', 60.00, 'rack 4'),
(125055, 'Multivitamin Syrup 200ml', 160, 'Syrup', 75.00, 'rack 3'),
(125056, 'Ciprofloxacin 500mg', 410, 'Tablet', 8.00, 'rack 6'),
(125057, 'Erythromycin 250mg', 230, 'Tablet', 6.00, 'rack 6'),
(125058, 'Folic Acid 5mg', 320, 'Tablet', 2.00, 'rack 8'),
(125059, 'Vitamin B Complex', 460, 'Tablet', 3.50, 'rack 8'),
(125060, 'Cough Lozenges', 300, 'Lozenge', 1.50, 'rack 3'),
(125062, 'Boric Acid Powder 25g', 180, 'Powder', 15.00, 'rack 4'),
(125063, 'Scissors Surgical', 100, 'Device', 90.00, 'rack 10'),
(125064, 'Surgical Blade (box)', 110, 'Device', 140.00, 'rack 10'),
(125065, 'Disposable Apron', 150, 'Device', 50.00, 'rack 10'),
(125066, 'Micropore Tape 1in', 190, 'Device', 18.00, 'rack 4'),
(125067, 'Digital Weighing Scale', 70, 'Device', 680.00, 'rack 14'),
(125068, 'IV Set', 200, 'Device', 25.00, 'rack 11'),
(125069, 'Cannula 24G', 250, 'Device', 15.00, 'rack 11'),
(125070, 'Blood Collection Tube', 300, 'Device', 5.00, 'rack 11'),
(125071, 'Test Tube Pack', 180, 'Device', 55.00, 'rack 11'),
(125072, 'Insulin Pen Needle', 199, 'Device', 20.00, 'rack 11'),
(125073, 'Surgical Cap', 300, 'Device', 8.00, 'rack 10'),
(125074, 'Surgical Mask N95', 120, 'Device', 25.00, 'rack 10'),
(125075, 'Blood Pressure Cuff', 90, 'Device', 130.00, 'rack 14'),
(125076, 'Urine Strip Test', 180, 'Device', 15.00, 'rack 11'),
(125077, 'Glucose Powder 100g', 260, 'Powder', 40.00, 'rack 9'),
(125078, 'Electrolyte Powder', 240, 'Powder', 35.00, 'rack 9'),
(125080, 'Antacid Liquid 150ml', 268, 'Liquid', 60.00, 'rack 3'),
(125081, 'Antacid Tablet', 409, 'Tablet', 1.50, 'rack 5'),
(125082, 'Gaviscon Liquid 200ml', 160, 'Liquid', 85.00, 'rack 3'),
(125085, 'Paracetamol Syrup 60ml', 303, 'Syrup', 25.00, 'rack 3'),
(125087, 'Sleep Aid Tablet', 120, 'Tablet', 6.00, 'rack 6'),
(125088, 'Laxative Syrup 150ml', 220, 'Syrup', 70.00, 'rack 3'),
(125089, 'Hair Growth Oil 100ml', 139, 'Oil', 95.00, 'rack 4'),
(125091, 'Mouthwash 250ml', 180, 'Liquid', 90.00, 'rack 3'),
(125092, 'Colgate Herbal 100g', 209, 'Paste', 115.00, 'rack 4'),
(125093, 'Toothbrush Soft', 300, 'Device', 25.00, 'rack 4'),
(125094, 'Diabetic Socks', 160, 'Accessory', 110.00, 'rack 8'),
(125095, 'Anti-Dandruff Shampoo 100ml', 190, 'Liquid', 75.00, 'rack 4'),
(125096, 'Handwash 250ml', 250, 'Liquid', 50.00, 'rack 4'),
(125097, 'Burnol Ointment 20g', 180, 'Ointment', 40.00, 'rack 4'),
(125098, 'Antifungal Powder 100g', 138, 'Powder', 70.00, 'rack 4'),
(125099, 'Nail Cutter Stainless', 140, 'Device', 35.00, 'rack 10'),
(123101, 'Ibuprofen 400 MG', 200, 'Tablet', 1.50, 'rack 11'),
(123102, 'Naproxen 250 MG', 150, 'Tablet', 2.00, 'rack 11'),
(123103, 'Metformin 500 MG', 200, 'Tablet', 3.50, 'rack 11'),
(123104, 'Losartan 50 MG', 180, 'Tablet', 4.00, 'rack 11'),
(123105, 'Amlodipine 5 MG', 142, 'Tablet', 2.50, 'rack 11'),
(123106, 'Becosules Syrup', 120, 'Syrup', 45.00, 'rack 12'),
(123107, 'Lacto Calamine Lotion', 100, 'Syrup', 60.00, 'rack 12'),
(123108, 'Dextromethorphan Syrup', 140, 'Syrup', 55.00, 'rack 12'),
(123109, 'Koflet Cough Syrup', 100, 'Syrup', 58.00, 'rack 12'),
(123110, 'Expectorant DX', 100, 'Syrup', 65.00, 'rack 12'),
(123111, 'Multivitamin Capsule', 200, 'Capsule', 5.00, 'rack 13'),
(123113, 'Doxycycline 100 MG', 150, 'Capsule', 8.00, 'rack 13'),
(123114, 'Rabeprazole 20 MG', 190, 'Capsule', 6.50, 'rack 13'),
(123115, 'Pantoprazole 40 MG', 200, 'Capsule', 6.00, 'rack 13'),
(123116, 'Betadine Ointment', 120, 'Ointment', 25.00, 'rack 14'),
(123117, 'Burnol', 110, 'Ointment', 35.00, 'rack 14'),
(123118, 'Volini Gel', 130, 'Ointment', 90.00, 'rack 14'),
(123119, 'Moov Cream', 140, 'Ointment', 85.00, 'rack 14'),
(123120, 'Boroline', 100, 'Ointment', 20.00, 'rack 14'),
(123121, 'Digital Thermometer', 50, 'Device', 120.00, 'rack 15'),
(123122, 'Glucometer', 30, 'Device', 900.00, 'rack 15'),
(123123, 'BP Monitor', 25, 'Device', 1500.00, 'rack 15'),
(123124, 'Nebulizer', 194, 'Device', 1800.00, 'rack 15'),
(123125, 'Pulse Oximeter', 40, 'Device', 700.00, 'rack 15'),
(123126, 'Vitamin B12 Injection', 80, 'Injection', 40.00, 'rack 16'),
(123127, 'Insulin Injection', 70, 'Injection', 150.00, 'rack 16'),
(123128, 'Rabies Vaccine', 30, 'Injection', 300.00, 'rack 16'),
(123129, 'TT Injection', 90, 'Injection', 50.00, 'rack 16'),
(123130, 'Hepatitis B Vaccine', 50, 'Injection', 600.00, 'rack 16'),
(123131, 'Nasivion Nasal Drops', 100, 'Drops', 35.00, 'rack 17'),
(123132, 'Zinc Acetate Drops', 100, 'Drops', 38.00, 'rack 17'),
(123133, 'Otrivin Pediatric Drops', 90, 'Drops', 45.00, 'rack 17'),
(123134, 'Eye Moisture Drops', 80, 'Drops', 42.00, 'rack 17'),
(123135, 'Ear Drops Ciplox', 60, 'Drops', 48.00, 'rack 17'),
(123136, 'ORS Sachets', 150, 'Powder', 12.00, 'rack 18'),
(123137, 'Proteinex Powder', 80, 'Powder', 300.00, 'rack 18'),
(123138, 'Pediasure Powder', 70, 'Powder', 450.00, 'rack 18'),
(123139, 'Womens Horlicks', 0, 'Supplement', 280.00, 'rack 18'),
(123140, 'Bournvita', 90, 'Powder', 250.00, 'rack 18');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) DEFAULT '0.00',
  `pharmacist_id` decimal(7,0) DEFAULT NULL,
  `action_date` timestamp NULL DEFAULT NULL,
  `action_notes` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT 'COD',
  `payment_status` varchar(20) DEFAULT 'unpaid',
  `payment_id` varchar(100) DEFAULT NULL,
  `email_sent` tinyint(1) DEFAULT '0',
  `order_type` varchar(20) DEFAULT 'online',
  `ready_for_pickup` tinyint DEFAULT '0',
  `delivery_status` varchar(20) DEFAULT NULL,
  `order_closed` tinyint(1) DEFAULT '0',
  `dispatched` tinyint DEFAULT '0',
  `prescription_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
CREATE TABLE IF NOT EXISTS `order_history` (
  `order_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `pharmacist_id` int DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `action_notes` text,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `email_sent` tinyint DEFAULT '0',
  `order_type` varchar(20) DEFAULT 'delivery',
  `ready_for_pickup` tinyint(1) DEFAULT '0',
  `delivery_status` varchar(20) DEFAULT 'pending',
  `order_closed` tinyint(1) DEFAULT '0',
  `dispatched` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`order_id`, `user_id`, `order_date`, `status`, `total_amount`, `pharmacist_id`, `action_date`, `action_notes`, `latitude`, `longitude`, `payment_method`, `payment_status`, `payment_id`, `email_sent`, `order_type`, `ready_for_pickup`, `delivery_status`, 

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `med_id` decimal(6,0) NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(6,2) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `med_id` (`med_id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items_history`
--

DROP TABLE IF EXISTS `order_items_history`;
CREATE TABLE IF NOT EXISTS `order_items_history` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `med_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items_history`
--

INSERT INTO `order_items_history` (`order_item_id`, `order_id`, `med_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 123003, 1, 5.00, 5.00),
(2, 1, 123011, 4, 80.00, 320.00),
(3, 2, 123011, 9, 80.00, 720.00),
(4, 3, 125012, 1, 2.25, 2.25),
(5, 3, 123105, 3, 2.50, 7.50),
(6, 3, 125085, 3, 25.00, 75.00),
(7, 4, 125086, 1, 3.00, 3.00),
(8, 4, 123016, 4, 7.00, 28.00),
(9, 5, 123016, 1, 7.00, 7.00),
(10, 6, 125029, 3, 7.00, 21.00),
(11, 7, 125039, 4, 1.00, 4.00),
(12, 8, 125086, 12, 3.00, 36.00),
(13, 9, 123023, 6, 55.00, 330.00),
(14, 10, 123016, 3, 7.00, 21.00),
(15, 11, 125011, 1, 1.80, 1.80),
(16, 12, 123016, 7, 7.00, 49.00),
(17, 13, 125080, 6, 60.00, 360.00),
(18, 14, 125089, 1, 95.00, 95.00),
(19, 14, 123023, 1, 55.00, 55.00),
(20, 14, 123016, 1, 7.00, 7.00),
(21, 15, 125039, 35, 1.00, 35.00),
(22, 16, 123016, 4, 7.00, 28.00),
(23, 17, 125028, 8, 4.00, 32.00),
(24, 18, 123032, 14, 200.00, 2800.00),
(25, 19, 125014, 10, 28.00, 280.00),
(26, 20, 123012, 11, 1.00, 11.00),
(27, 21, 123016, 1, 7.00, 7.00),
(28, 21, 125075, 90, 130.00, 11700.00),
(29, 22, 123023, 6, 55.00, 330.00),
(30, 22, 123016, 6, 7.00, 42.00),
(31, 23, 123023, 1, 55.00, 55.00),
(32, 23, 123016, 4, 7.00, 28.00),
(33, 24, 123105, 1, 2.50, 2.50),
(34, 25, 125086, 1, 3.00, 3.00),
(35, 26, 125039, 1, 1.00, 1.00),
(36, 27, 123023, 2, 55.00, 110.00),
(37, 28, 123016, 3, 7.00, 21.00),
(38, 29, 123016, 1, 7.00, 7.00),
(39, 30, 125086, 1, 3.00, 3.00),
(40, 31, 123112, 5, 7.00, 35.00),
(41, 32, 125028, 5, 4.00, 20.00),
(42, 33, 123112, 4, 7.00, 28.00),
(43, 34, 125086, 1, 3.00, 3.00),
(44, 35, 123124, 2, 1800.00, 3600.00),
(45, 36, 123023, 3, 55.00, 165.00),
(46, 37, 125086, 12, 3.00, 36.00),
(47, 38, 123016, 5, 7.00, 35.00),
(48, 39, 125011, 1, 1.80, 1.80),
(49, 40, 125039, 7, 1.00, 7.00),
(50, 41, 123105, 6, 2.50, 15.00),
(51, 42, 125080, 5, 60.00, 300.00),
(52, 43, 125080, 5, 60.00, 300.00),
(53, 44, 125086, 1, 3.00, 3.00),
(54, 44, 123016, 1, 7.00, 7.00),
(55, 44, 125039, 1, 1.00, 1.00),
(56, 45, 125086, 6, 3.00, 18.00),
(57, 45, 125011, 1, 1.80, 1.80),
(58, 46, 125080, 5, 60.00, 300.00),
(59, 48, 123023, 5, 55.00, 275.00),
(60, 47, 123023, 3, 55.00, 165.00),
(61, 49, 125011, 3, 1.80, 5.40),
(62, 49, 123016, 3, 7.00, 21.00),
(63, 50, 125011, 1, 1.80, 1.80),
(64, 50, 123023, 1, 55.00, 55.00),
(65, 51, 123112, 3, 7.00, 21.00),
(66, 51, 125039, 1, 1.00, 1.00),
(67, 51, 125086, 3, 3.00, 9.00),
(68, 52, 123105, 2, 2.50, 5.00),
(69, 52, 123130, 1, 600.00, 600.00),
(70, 52, 123124, 5, 1800.00, 9000.00),
(71, 53, 125086, 1, 3.00, 3.00),
(72, 53, 123023, 1, 55.00, 55.00),
(73, 53, 123105, 1, 2.50, 2.50),
(74, 54, 125028, 1, 4.00, 4.00),
(75, 56, 125028, 3, 4.00, 12.00),
(76, 55, 125086, 1, 3.00, 3.00),
(77, 55, 125051, 1, 65.00, 65.00),
(78, 58, 125007, 5, 1880.50, 9402.50),
(79, 57, 123023, 1, 55.00, 55.00),
(80, 57, 123016, 3, 7.00, 21.00),
(81, 57, 125011, 1, 1.80, 1.80),
(82, 59, 123023, 7, 55.00, 385.00),
(83, 60, 125080, 1, 60.00, 60.00),
(84, 60, 125039, 1, 1.00, 1.00),
(85, 60, 125020, 1, 150.00, 150.00),
(86, 60, 125072, 1, 20.00, 20.00),
(87, 61, 123112, 1, 7.00, 7.00),
(88, 61, 123023, 1, 55.00, 55.00),
(89, 62, 125085, 4, 25.00, 100.00),
(90, 63, 123124, 1, 1800.00, 1800.00),
(91, 64, 123112, 6, 7.00, 42.00),
(92, 64, 123023, 3, 55.00, 165.00),
(93, 65, 123023, 1, 55.00, 55.00),
(94, 65, 125011, 1, 1.80, 1.80),
(95, 65, 125028, 1, 4.00, 4.00),
(96, 66, 125039, 7, 1.00, 7.00),
(97, 68, 123023, 1, 55.00, 55.00),
(98, 68, 125029, 1, 7.00, 7.00),
(99, 68, 125080, 1, 60.00, 60.00),
(100, 68, 125086, 1, 3.00, 3.00),
(101, 67, 125039, 1, 1.00, 1.00),
(102, 67, 125086, 1, 3.00, 3.00),
(103, 67, 125029, 1, 7.00, 7.00),
(104, 67, 125080, 4, 60.00, 240.00),
(105, 69, 125028, 1, 4.00, 4.00),
(106, 69, 125011, 1, 1.80, 1.80),
(107, 71, 125053, 1, 1.20, 1.20),
(108, 71, 123023, 1, 55.00, 55.00),
(109, 71, 125086, 1, 3.00, 3.00),
(110, 71, 125039, 17, 1.00, 17.00),
(111, 70, 125028, 1, 4.00, 4.00),
(112, 73, 123105, 1, 2.50, 2.50),
(113, 74, 125028, 2, 4.00, 8.00),
(114, 74, 123016, 6, 7.00, 42.00),
(115, 73, 125061, 1, 48.00, 48.00),
(116, 73, 123023, 1, 55.00, 55.00),
(117, 72, 125039, 1, 1.00, 1.00),
(118, 74, 123023, 10, 55.00, 550.00),
(119, 74, 123105, 3, 2.50, 7.50),
(120, 74, 125098, 20, 70.00, 1400.00),
(121, 74, 125081, 1, 1.50, 1.50),
(122, 74, 125050, 1, 85.00, 85.00),
(123, 75, 125092, 1, 115.00, 115.00),
(124, 75, 125098, 1, 70.00, 70.00),
(125, 75, 125028, 1, 4.00, 4.00),
(126, 76, 123016, 4, 7.00, 28.00),
(127, 76, 123023, 1, 55.00, 55.00),
(128, 77, 123023, 5, 55.00, 275.00),
(129, 77, 125095, 13, 75.00, 975.00),
(130, 77, 125039, 1, 1.00, 1.00),
(131, 77, 123006, 4, 50.00, 200.00),
(132, 77, 125060, 1, 1.50, 1.50),
(133, 77, 123023, 5, 55.00, 275.00),
(134, 77, 125095, 13, 75.00, 975.00),
(135, 77, 125039, 1, 1.00, 1.00),
(136, 77, 123006, 4, 50.00, 200.00),
(137, 77, 125060, 1, 1.50, 1.50),
(138, 78, 123118, 1, 90.00, 90.00),
(139, 78, 123126, 1, 40.00, 40.00),
(140, 78, 123132, 1, 38.00, 38.00),
(141, 79, 123023, 3, 55.00, 165.00),
(142, 80, 123105, 6, 2.50, 15.00),
(143, 81, 123105, 1, 2.50, 2.50),
(144, 82, 125028, 1, 4.00, 4.00),
(145, 83, 123023, 1, 55.00, 55.00),
(146, 84, 123023, 1, 55.00, 55.00),
(147, 85, 125028, 1, 4.00, 4.00),
(148, 86, 123023, 1, 55.00, 55.00),
(149, 87, 125095, 3, 75.00, 225.00),
(150, 88, 123105, 1, 2.50, 2.50),
(151, 88, 123023, 1, 55.00, 55.00),
(152, 89, 125098, 1, 70.00, 70.00);

-- --------------------------------------------------------

--
-- Table structure for table `pharmlogin`
--

DROP TABLE IF EXISTS `pharmlogin`;
CREATE TABLE IF NOT EXISTS `pharmlogin` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_USERNAME` varchar(20) NOT NULL,
  `E_PASS` varchar(30) NOT NULL,
  PRIMARY KEY (`E_USERNAME`),
  KEY `E_ID` (`E_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pharmlogin`
--

INSERT INTO `pharmlogin` (`E_ID`, `E_USERNAME`, `E_PASS`) VALUES
(4567005, 'amaya', 'pass1'),
(4567002, 'anita', 'pass2'),
(4567010, 'daniel', 'pass3'),
(4567003, 'harish', 'pass4'),
(4567009, 'shayla', 'pass5'),
(4567006, 'shoaib', 'pass6'),
(4567001, 'varshini', 'pass7');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `P_ID` decimal(4,0) NOT NULL,
  `SUP_ID` decimal(3,0) NOT NULL,
  `MED_ID` decimal(6,0) NOT NULL,
  `P_QTY` int NOT NULL,
  `P_COST` decimal(8,2) NOT NULL,
  `PUR_DATE` date NOT NULL,
  `MFG_DATE` date NOT NULL,
  `EXP_DATE` date NOT NULL,
  PRIMARY KEY (`P_ID`,`MED_ID`),
  KEY `SUP_ID` (`SUP_ID`),
  KEY `MED_ID` (`MED_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`P_ID`, `SUP_ID`, `MED_ID`, `P_QTY`, `P_COST`, `PUR_DATE`, `MFG_DATE`, `EXP_DATE`) VALUES
(1001, 136, 123010, 201, 1520.50, '2020-03-01', '2019-05-05', '2021-05-10'),
(1002, 123, 123002, 1000, 3000.00, '2020-02-01', '2018-06-01', '2020-12-05'),
(1003, 145, 123006, 20, 800.00, '2020-04-22', '2017-02-05', '2020-07-01'),
(1004, 156, 123004, 250, 1000.00, '2020-04-02', '2020-05-06', '2023-05-06'),
(1005, 123, 123005, 200, 1200.00, '2020-02-01', '2019-08-02', '2021-04-01'),
(1006, 162, 123010, 500, 1500.00, '2019-04-22', '2018-01-01', '2020-05-02'),
(1007, 123, 123001, 500, 450.00, '2020-01-02', '2019-01-05', '2022-03-06');

--
-- Triggers `purchase`
--
DROP TRIGGER IF EXISTS `QTYDELETE`;
DELIMITER $$
CREATE TRIGGER `QTYDELETE` AFTER DELETE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `QTYINSERT`;
DELIMITER $$
CREATE TRIGGER `QTYINSERT` AFTER INSERT ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `QTYUPDATE`;
DELIMITER $$
CREATE TRIGGER `QTYUPDATE` AFTER UPDATE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=new.MED_ID;
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `SALE_ID` int NOT NULL AUTO_INCREMENT,
  `C_ID` decimal(6,0) NOT NULL,
  `S_DATE` date DEFAULT NULL,
  `S_TIME` time DEFAULT NULL,
  `TOTAL_AMT` decimal(8,2) DEFAULT NULL,
  `E_ID` decimal(7,0) NOT NULL,
  PRIMARY KEY (`SALE_ID`),
  KEY `C_ID` (`C_ID`),
  KEY `E_ID` (`E_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SALE_ID`, `C_ID`, `S_DATE`, `S_TIME`, `TOTAL_AMT`, `E_ID`) VALUES
(1, 987101, '2020-04-15', '13:23:03', 180.00, 4567009),
(2, 987106, '2020-04-21', '20:19:31', 585.00, 1),
(3, 987103, '2020-04-15', '11:23:53', 120.00, 4567010),
(4, 987104, '2020-04-14', '18:20:00', 955.00, 4567006),
(5, 987103, '2020-04-21', '15:24:43', 45.00, 1),
(6, 987102, '2020-03-11', '10:24:43', 140.00, 4567001),
(7, 987105, '2020-04-24', '00:25:54', 350.00, 1),
(8, 987104, '2020-04-24', '00:47:47', 35.00, 4567001),
(12, 987103, '2020-04-24', '19:33:16', 60.00, 1),
(13, 987104, '2020-04-24', '21:15:56', 62.50, 4567001),
(15, 987107, '2020-12-04', '18:39:46', 420.00, 1),
(16, 987106, '2020-12-04', '18:52:21', 30.00, 1),
(17, 987103, '2020-12-04', '19:35:56', 57.50, 1),
(18, 987105, '2020-12-04', '19:36:56', 160.00, 4567001),
(20, 987103, '2020-12-04', '22:53:18', 150.00, 4567001),
(21, 987102, NULL, NULL, NULL, 1),
(22, 0, NULL, NULL, NULL, 4567005),
(23, 0, NULL, NULL, NULL, 4567005),
(24, 0, NULL, NULL, NULL, 4567005),
(25, 0, NULL, NULL, NULL, 4567005),
(26, 0, NULL, NULL, NULL, 4567005),
(27, 0, NULL, NULL, NULL, 4567005),
(28, 987101, NULL, NULL, NULL, 4567005),
(29, 0, NULL, NULL, NULL, 4567005),
(30, 0, NULL, NULL, NULL, 4567005),
(31, 0, NULL, NULL, NULL, 4567005),
(32, 0, NULL, NULL, NULL, 4567005),
(33, 0, NULL, NULL, NULL, 4567005),
(34, 0, NULL, NULL, NULL, 4567005),
(35, 0, NULL, NULL, NULL, 4567005),
(36, 0, NULL, NULL, NULL, 4567005),
(37, 0, NULL, NULL, NULL, 4567005),
(38, 987101, NULL, NULL, NULL, 4567005),
(39, 987102, NULL, NULL, NULL, 4567005),
(40, 987103, NULL, NULL, NULL, 4567005),
(41, 987104, NULL, NULL, NULL, 4567005);

--
-- Triggers `sales`
--
DROP TRIGGER IF EXISTS `SALE_ID_DELETE`;
DELIMITER $$
CREATE TRIGGER `SALE_ID_DELETE` BEFORE DELETE ON `sales` FOR EACH ROW BEGIN
DELETE from sales_items WHERE sales_items.SALE_ID=old.SALE_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

DROP TABLE IF EXISTS `sales_items`;
CREATE TABLE IF NOT EXISTS `sales_items` (
  `SALE_ID` int NOT NULL,
  `MED_ID` decimal(6,0) NOT NULL,
  `SALE_QTY` int NOT NULL,
  `TOT_PRICE` decimal(8,2) NOT NULL,
  PRIMARY KEY (`SALE_ID`,`MED_ID`),
  KEY `MED_ID` (`MED_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`SALE_ID`, `MED_ID`, `SALE_QTY`, `TOT_PRICE`) VALUES
(1, 123001, 20, 20.00),
(1, 123011, 2, 160.00),
(2, 123003, 75, 225.00),
(2, 123005, 60, 360.00),
(3, 123008, 40, 120.00),
(4, 123010, 250, 875.00),
(4, 123011, 1, 80.00),
(5, 123001, 45, 45.00),
(6, 123006, 2, 100.00),
(6, 123009, 10, 40.00),
(7, 123001, 100, 100.00),
(7, 123003, 50, 250.00),
(8, 123001, 10, 10.00),
(8, 123002, 10, 25.00),
(12, 123005, 10, 60.00),
(13, 123002, 25, 62.50),
(15, 123005, 45, 270.00),
(15, 123006, 3, 150.00),
(16, 123008, 10, 30.00),
(17, 123004, 10, 12.50),
(17, 123007, 5, 25.00),
(17, 123009, 5, 20.00),
(18, 123011, 2, 160.00),
(20, 123005, 25, 150.00),
(37, 123006, 10, 500.00),
(41, 123005, 1, 6.00);

--
-- Triggers `sales_items`
--
DROP TRIGGER IF EXISTS `SALEDELETE`;
DELIMITER $$
CREATE TRIGGER `SALEDELETE` AFTER DELETE ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+old.SALE_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `SALEINSERT`;
DELIMITER $$
CREATE TRIGGER `SALEINSERT` AFTER INSERT ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-new.SALE_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `SUP_ID` decimal(3,0) NOT NULL,
  `SUP_NAME` varchar(25) NOT NULL,
  `SUP_ADD` varchar(30) NOT NULL,
  `SUP_PHNO` decimal(10,0) NOT NULL,
  `SUP_MAIL` varchar(40) NOT NULL,
  PRIMARY KEY (`SUP_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SUP_ID`, `SUP_NAME`, `SUP_ADD`, `SUP_PHNO`, `SUP_MAIL`) VALUES
(123, 'XYZ Pharmaceutical', 'Ernakulam', 8745632145, 'xyz@xyzpharma.com'),
(136, 'ABC PharmaSupply', 'Vadakkanchery', 7894561235, 'abc@pharmsupp.com'),
(156, 'MedAlls', 'Thrissur', 9874585236, 'mainid@medall.com'),
(162, 'MedHead Pharmaceuticals', 'Kollam', 7894561335, 'abc@pharmsupp.com'),
(245, 'SEA pharma', 'Thiruvalla', 7859652564, 'expertAn@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('customer','pharmacist','admin') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text,
  `phone` varchar(15) DEFAULT NULL,
  `profile_picture` longblob,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_locations`
--

DROP TABLE IF EXISTS `user_locations`;
CREATE TABLE IF NOT EXISTS `user_locations` (
  `location_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `med_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `notified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `med_id` (`med_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `med_id`, `created_at`, `notified`) VALUES
(6, 2, 123139, '2025-07-19 23:42:38', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
