-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 08:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostpital`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `ID` int(11) NOT NULL,
  `accessCode` varchar(80) NOT NULL,
  `accessName` varchar(80) NOT NULL,
  `accessIsDelete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`ID`, `accessCode`, `accessName`, `accessIsDelete`) VALUES
(1, '65', 'ผู้ดูแลระบบ', 0),
(4, '61', 'หัวหน้าธุรการ', 0),
(5, '62', 'หัวหน้าแผนก', 0),
(6, '63', 'พนักงานในแผนก', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contect`
--

CREATE TABLE `contect` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contect`
--

INSERT INTO `contect` (`id`, `name`, `email`, `subject`, `message`) VALUES
(1, 'อรอนงค์ เป็นไทย', '63309030009@srvc.ac.th', 'ลางาน', 'ขออนุญาติลางาน วันที่ 21 ธันวาคม 2566 เนื่องจากแม่เสีย');

-- --------------------------------------------------------

--
-- Table structure for table `cotton`
--

CREATE TABLE `cotton` (
  `id` int(11) NOT NULL,
  `cotCode` varchar(80) NOT NULL,
  `cotName` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cotton`
--

INSERT INTO `cotton` (`id`, `cotCode`, `cotName`) VALUES
(2, '121212', 'mm');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `departCode` varchar(80) NOT NULL,
  `departName` varchar(80) NOT NULL,
  `departIsDelete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `departCode`, `departName`, `departIsDelete`) VALUES
(8, '121451', 'ผู้ป่วยนอก', 0),
(9, '121452', 'แผนกฉุกเฉิน', 0),
(10, '121453', ' แผนกหอผู้ป่วยวิกฤต', 0),
(11, '121454', ' แผนกหอผู้ป่วยใน', 0),
(12, '121455', 'แผนกสูติ - นรีเวชกรรม', 0),
(13, '121456', 'ห้องผ่าตัด', 0);

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE `personal` (
  `id` int(11) NOT NULL,
  `Personal_ID` varchar(255) NOT NULL,
  `prefixCode` varchar(80) NOT NULL,
  `Fname` varchar(100) NOT NULL,
  `Lname` varchar(100) NOT NULL,
  `positCode` varchar(100) NOT NULL,
  `departCode` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `accessCode` varchar(100) NOT NULL,
  `is_leader` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`id`, `Personal_ID`, `prefixCode`, `Fname`, `Lname`, `positCode`, `departCode`, `Password`, `accessCode`, `is_leader`, `is_active`, `is_delete`) VALUES
(1, '111111', '633030', 'อรอนงค์', 'เป็นไทย', '313133', '121451', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '65', 1, 1, 0),
(2, '606060', '636320', 'เกียรติศักดิ์', 'เนียมหอม', '313131', '121451', '023f5351b94db0bdcde8dd21da240ac75adc1fc82371c516543b25485cb900de', '63', 0, 1, 0),
(3, '333333', '633030', 'จิราภรณ์', 'ไหมทอง', '313131', '121451', '6460662e217c7a9f899208dd70a2c28abdea42f128666a9b78e6c0c064846493', '61', 0, 1, 0),
(4, '852963', '633030', 'นริศรา', 'ขอชมกลาง', '313131', '121451', '472bbe83616e93d3c09a79103ae47d8f71e3d35a966d6e8b22f743218d04171d', '63', 0, 1, 0),
(6, '001212', '633030', 'ญาณิศา', 'ฉัตรไชยสิทธิกูล', '313131', '121451', '472bbe83616e93d3c09a79103ae47d8f71e3d35a966d6e8b22f743218d04171d', '63', 0, 1, 0),
(7, '444444', '652301', 'สาวินี', 'เดือนขาว', '313131', '121451', '69f7f7a7f8bca9970fa6f9c0b8dad06901d3ef23fd599d3213aa5eee5621c3e3', '62', 0, 1, 0),
(8, '55555', '633030', 'วราภรณ์', 'ศรไชย', '313131', '121451', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '63', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `petitions`
--

CREATE TABLE `petitions` (
  `id_petitions` int(11) NOT NULL,
  `Personal_ID` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `duty` varchar(255) NOT NULL,
  `day` int(11) NOT NULL,
  `month` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `request` text NOT NULL,
  `substitute` varchar(255) NOT NULL,
  `substitute_day` int(11) NOT NULL,
  `substitute_month` varchar(50) NOT NULL,
  `substitute_year` int(11) NOT NULL,
  `reason` text NOT NULL,
  `return_day` int(11) NOT NULL,
  `return_month` varchar(50) NOT NULL,
  `return_year` int(11) NOT NULL,
  `status` int(10) NOT NULL,
  `date_upload` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petitions`
--

INSERT INTO `petitions` (`id_petitions`, `Personal_ID`, `subject`, `name`, `position`, `duty`, `day`, `month`, `year`, `request`, `substitute`, `substitute_day`, `substitute_month`, `substitute_year`, `reason`, `return_day`, `return_month`, `return_year`, `status`, `date_upload`) VALUES
(6, '111111', 'asdas', 'dasdas', 'dasdas', 'dasdasd', 1, 'มกราคม', 1, 'asdasd', 'asdasd', 1, 'มกราคม', 1, 'asdsa', 1, 'มกราคม', 1, 2, '2024-02-07 23:57:38'),
(7, '111111', 'asdas', 'dasdas', 'dasdas', 'dasdasd', 1, 'มกราคม', 1, 'asdasd', 'asdasd', 1, 'มกราคม', 1, 'asdsa', 1, 'มกราคม', 1, 1, '2024-02-06 23:57:38'),
(8, '111111', 'asdas', 'dasdas', 'dasdas', 'dasdasd', 1, 'มกราคม', 2566, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'eeee', 1, 'มกราคม', 1, 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq', 1, 'มกราคม', 1, 1, '2024-02-09 23:57:38'),
(9, '111111', 'asd', 'asdasdas', 'dasdas', 'das', 1, 'มกราคม', 1, 'dasdasd', 'asd', 11, 'มกราคม', 111, '1asdasdasd', 111, 'มกราคม', 11, 1, '2024-02-08 14:06:17'),
(10, '111111', 'ฟหกหฟกฟหกฟห', 'กฟหกฟหก', 'หฟกฟหก', 'ฟหกฟหกฟหก', 11, 'มกราคม', 11111, 'asdasdasdasd', 'กหๆกฟหกฟหก', 12, 'มกราคม', 123, 'ฟหกหฟกหฟกฟห', 12, 'กรกฎาคม', 111, 1, '2024-02-08 14:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `positCode` varchar(80) NOT NULL,
  `positName` varchar(80) NOT NULL,
  `positIsDelete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `positCode`, `positName`, `positIsDelete`) VALUES
(1, '313131', 'แพทย์', 0),
(3, '313132', 'พยาบาล', 0),
(4, '313133', 'ผู้ช่วยพยาบาล', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prefix`
--

CREATE TABLE `prefix` (
  `id` int(11) NOT NULL,
  `prefixCode` varchar(80) NOT NULL,
  `prefixName` varchar(80) NOT NULL,
  `prefixIsDelete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prefix`
--

INSERT INTO `prefix` (`id`, `prefixCode`, `prefixName`, `prefixIsDelete`) VALUES
(1, '633030', 'นางสาว', 0),
(2, '636320', 'นาย', 0),
(3, '652301', 'นาง', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shift_schedule`
--

CREATE TABLE `shift_schedule` (
  `id` int(11) NOT NULL,
  `Personal_ID` varchar(255) NOT NULL,
  `positCode` varchar(80) NOT NULL,
  `departCode` varchar(80) NOT NULL,
  `shift_date` date NOT NULL,
  `is_shift` tinyint(4) NOT NULL DEFAULT 0,
  `shift_type` char(2) DEFAULT NULL COMMENT 'M = เช้า, A = บ่าย, N = ดึก',
  `is_case` tinyint(4) NOT NULL COMMENT '0 ไม่ใช่เคส, 1 เป็นเคส',
  `shift_status` tinyint(4) NOT NULL COMMENT '1 ใช้งาน, 0 ยกเลิก',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift_schedule`
--

INSERT INTO `shift_schedule` (`id`, `Personal_ID`, `positCode`, `departCode`, `shift_date`, `is_shift`, `shift_type`, `is_case`, `shift_status`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(1, '111111', '313133', '121451', '2024-01-25', 1, 'M', 0, 1, 1, '2024-01-24 19:14:31', 1, '2024-01-24 12:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `shift_setting`
--

CREATE TABLE `shift_setting` (
  `position_code` varchar(80) NOT NULL,
  `is_shift` tinyint(4) NOT NULL,
  `moring_shift_count` int(11) NOT NULL,
  `afternoon_shift_count` int(11) DEFAULT NULL,
  `night_shift_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift_setting`
--

INSERT INTO `shift_setting` (`position_code`, `is_shift`, `moring_shift_count`, `afternoon_shift_count`, `night_shift_count`) VALUES
('313131', 0, 1, NULL, NULL),
('313132', 1, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `staCode` varchar(80) NOT NULL,
  `staName` varchar(80) NOT NULL,
  `staIsDelete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `staCode`, `staName`, `staIsDelete`) VALUES
(1, '000001', 'ไม่ว่าง', 0),
(2, '000002', 'ว่าง', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `accessCode` (`accessCode`);

--
-- Indexes for table `contect`
--
ALTER TABLE `contect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cotton`
--
ALTER TABLE `cotton`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cotCode` (`cotCode`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departCode` (`departCode`);

--
-- Indexes for table `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Personal_ID` (`Personal_ID`);

--
-- Indexes for table `petitions`
--
ALTER TABLE `petitions`
  ADD PRIMARY KEY (`id_petitions`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `positCode` (`positCode`);

--
-- Indexes for table `prefix`
--
ALTER TABLE `prefix`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prefixCode` (`prefixCode`);

--
-- Indexes for table `shift_schedule`
--
ALTER TABLE `shift_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_setting`
--
ALTER TABLE `shift_setting`
  ADD PRIMARY KEY (`position_code`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staCode` (`staCode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contect`
--
ALTER TABLE `contect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cotton`
--
ALTER TABLE `cotton`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal`
--
ALTER TABLE `personal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `petitions`
--
ALTER TABLE `petitions`
  MODIFY `id_petitions` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prefix`
--
ALTER TABLE `prefix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shift_schedule`
--
ALTER TABLE `shift_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
