-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2014 at 09:08 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `or!config`
--

-- --------------------------------------------------------

--
-- Table structure for table `my_menu`
--

CREATE TABLE IF NOT EXISTS `my_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT 'หมวดของเมนู',
  `name` varchar(100) NOT NULL,
  `sort_id` int(11) NOT NULL COMMENT 'เลขเรียงลำดับ',
  `href` varchar(100) NOT NULL COMMENT 'url ของเมนู',
  `href_type` int(11) NOT NULL COMMENT 'ประเภทเมนู',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `sec_user` varchar(20) NOT NULL DEFAULT '',
  `sec_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sec_ip` varchar(20) NOT NULL DEFAULT '',
  `sec_script` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `my_menu`
--

INSERT INTO `my_menu` (`id`, `category_id`, `name`, `sort_id`, `href`, `href_type`, `status`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES
(1, 1, 'งานสารสนเทศเวชสถิติ(เฉพาะทีมIMC)', 1, '../../my_projects/mr_diag/', 0, 0, 'orr', '2013-10-20 09:48:48', '10.1.16.4', 'my_menu.php'),
(2, 1, 'รายงานสนับสนุนงานของโรงพยาบาล', 2, '../../my_projects/Bi!Reports/', 0, 0, 'orr', '2013-10-20 09:48:55', '10.1.16.4', 'my_menu.php'),
(3, 2, 'บันทึกฝึกอบรม(MaxTraining)', 0, 'http://10.1.0.15/', 2, 0, 'orr', '2014-02-20 11:08:59', '10.1.16.4', 'my_menu.php'),
(4, 2, 'ใบขอใช้บริการสารสนเทศ', 0, '../../my_projects/helpdesk/', 0, 0, 'orr', '2014-02-20 11:09:31', '10.1.16.4', 'my_menu.php'),
(5, 2, 'ระบบทรัพย์สิน', 0, '../../my_projects/asset/', 0, 0, 'orr', '2014-02-20 11:09:37', '10.1.16.4', 'my_menu.php'),
(12, 1, ' Data Sheets of Differentiated Thyroid Carainoma', 4, '../../my_projects/cancer_thyroid_dtc/', 0, 1, 'orr', '2014-03-20 02:06:48', '10.1.16.4', 'my_menu.php'),
(6, 2, 'ปฏิทินกิจกรรม', 0, 'https://sites.google.com/a/theptarin.com/events/', 2, 0, 'orr', '2014-02-20 11:09:41', '10.1.16.4', 'my_menu.php'),
(7, 2, 'Gmail', 0, 'http://www.gmail.com/', 1, 0, 'orr', '2014-02-20 11:09:45', '10.1.16.4', 'my_menu.php'),
(8, 2, 'Google +', 0, 'https://plus.google.com/', 1, 0, 'orr', '2014-02-20 11:09:49', '10.1.16.4', 'my_menu.php'),
(9, 0, 'ประกาศเรื่องใหม่ๆ', 0, '../../my_projects/Or!Home/my_note.php', 2, 0, 'orr', '2013-10-18 08:09:51', '10.1.16.4', 'my_menu.php'),
(10, 0, 'ข้อมูลเกี่ยวกับตัวคุณ', 0, '../../my_projects/Or!Home/user_about.php', 2, 0, 'orr', '2013-10-18 08:10:52', '10.1.16.4', 'my_menu.php'),
(11, 0, 'phpMyAdmin', 0, 'http://10.1.99.6/phpmyadmin/', 1, 0, 'orr', '2013-10-18 08:14:02', '10.1.16.4', 'my_menu.php'),
(13, 1, 'หน้าหลัก', 0, '../../my_projects/Or!Home/', 0, 0, 'orr', '2013-10-19 04:54:41', '10.1.107.3', 'my_menu.php'),
(14, 2, 'Google drive', 0, 'https://drive.google.com/', 1, 0, 'orr', '2014-02-20 11:03:43', '10.1.16.4', 'my_menu.php'),
(15, 2, 'อินทราเนต ระบบเดิม', 0, 'http://10.1.0.12/intranet/', 2, 0, 'orr', '2014-02-20 11:03:39', '10.1.16.4', 'my_menu.php'),
(16, 2, 'Thep Wiki', 0, 'http://10.1.99.99/mediawiki/', 2, 0, 'norejitp', '2014-02-20 11:03:36', '10.1.16.4', 'my_menu.php'),
(17, 2, 'E-GroupWare', 0, 'http://10.1.0.12/egroupware/', 2, 0, 'norejitp', '2014-02-20 11:03:33', '10.1.16.4', 'my_menu.php'),
(18, 2, 'FAX', 0, 'http://10.1.2.2/mail', 1, 0, 'norejitp', '2014-02-20 11:03:31', '10.1.16.4', 'my_menu.php'),
(19, 1, 'การตรวจแลป(ก่อนวันที่ 28/02/2557)', 0, 'http://10.1.0.13/LabTheptarin/', 2, 0, 'norejitp', '2014-02-26 11:52:53', '10.1.16.4', 'my_menu.php'),
(20, 2, 'About Samba', 0, 'http://10.1.99.99/mediawiki/index.php/About_Samba', 2, 0, 'norejitp', '2014-03-18 11:50:44', '10.1.16.4', 'my_menu.php'),
(21, 2, 'E-Leaning', 0, 'http://10.1.99.19/moodle/', 1, 0, 'norejitp', '2014-02-20 11:03:21', '10.1.16.4', 'my_menu.php'),
(22, 2, 'LimeSurvey', 0, 'http://10.1.99.99/limesurvey/', 2, 0, 'norejitp', '2014-02-20 11:03:18', '10.1.16.4', 'my_menu.php'),
(23, 2, 'ยืมคืนอุปกรณ์', 0, '../../my_projects/moo!project/', 0, 0, 'orr', '2014-02-20 11:03:14', '10.1.16.4', 'my_menu.php'),
(24, 0, 'ร่วมกับอ๋อโปรเจค', 90, 'https://code.google.com/p/orr-projects/', 1, 0, 'orr', '2013-11-04 06:32:11', '10.1.16.4', 'my_menu.php'),
(25, 1, 'การตรวจแลป', 0, '../../my_projects/labplusone/', 1, 0, 'orr', '2014-02-27 07:04:10', '10.1.16.4', 'my_menu.php'),
(26, 2, 'ใบขอใช้บริการซ่อมบำรุง', 0, 'http://10.1.99.6/it_dev/Helpdesk2550/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:03:00', '10.1.16.13', 'my_menu.php'),
(27, 2, 'ใบขอใช้บริการสื่อสารองค์กร', 0, 'http://10.1.99.6/it_dev/Helpdesk2550/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:02:52', '10.1.16.13', 'my_menu.php'),
(28, 2, 'ระบบจองห้องประชุม', 0, 'http://10.1.99.6/it_dev/hr/meeting/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:02:48', '10.1.16.13', 'my_menu.php'),
(36, 1, 'ระบบตารางการออกตรวจแพทย์	', 0, 'http://10.1.99.6/it_dev/doctor/schedule_doctor/welcome.php', 2, 0, 'norejitp', '2014-02-26 11:50:27', '10.1.16.13', 'my_menu.php'),
(34, 2, 'ใบขอใช้บริการฝ่ายทรัพยากรบุคคล	', 0, 'http://10.1.99.6/it_dev/Helpdesk2550/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:09:15', '10.1.16.13', 'my_menu.php'),
(29, 2, 'MEDAS ITRIS', 0, 'http://medas.itris.ch/itris_en/index.php', 2, 0, 'orr', '2014-02-20 11:02:36', '10.1.16.4', 'my_menu.php'),
(30, 2, 'ระบบดัชนีชี้วัด KPI	', 0, 'http://10.1.99.6/it_dev/pp/kpi/portal.php', 2, 0, 'norejitp', '2014-01-16 03:08:09', '10.1.16.13', 'my_menu.php'),
(31, 2, 'ระบบ IER', 0, 'http://10.1.99.6/it_dev/pp/ier/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:00:06', '10.1.16.13', 'my_menu.php'),
(32, 2, 'ระบบข้อเสนอแนะและร้องเรียน	', 0, 'http://10.1.99.6/it_dev/pp/suggestion/welcome.php', 2, 0, 'norejitp', '2014-02-20 11:01:35', '10.1.16.13', 'my_menu.php'),
(33, 2, 'ระบบเอกสารคุณภาพ	', 0, 'http://10.1.99.6/it_dev/pp/qm_dar/portal.php', 2, 0, 'norejitp', '2014-02-21 03:48:29', '10.1.16.13', 'my_menu.php'),
(35, 2, 'รายงานสำหรับผู้บริหาร	', 0, 'http://10.1.99.6/Bi!Reports/portal.php', 2, 0, 'norejitp', '2014-02-20 11:06:31', '10.1.16.13', 'my_menu.php'),
(37, 1, 'คลินิกเวชกรรม เทพธารินทร์	', 0, 'http://10.1.99.6/it_dev/doctor/doctor_show/welcome.php', 2, 0, 'norejitp', '2014-02-26 11:49:52', '10.1.16.13', 'my_menu.php'),
(38, 1, 'รายงานการขายเวชภัณฑ์	', 0, 'http://10.1.99.6/Bi!Reports/ips_product_list.php', 2, 0, 'norejitp', '2014-02-26 11:50:44', '10.1.16.13', 'my_menu.php'),
(40, 1, 'รายงานผู้ป่วยแยกประเภท(มีค่าใช้จ่าย)	', 0, 'http://10.1.99.6/Bi!Reports/patient_total_list.php', 2, 0, 'norejitp', '2014-02-21 04:45:45', '10.1.16.13', 'my_menu.php'),
(41, 2, 'ระบบ Susceptibility pattern	', 0, 'http://10.1.99.6/Dn!Reports/portal.php', 2, 0, 'norejitp', '2014-02-20 11:15:12', '10.1.16.13', 'my_menu.php'),
(42, 1, 'บริการส่ง SMS ถึงแพทย์	', 0, 'http://smartcomm2.net/smartcomm2/index.jsp', 2, 0, 'norejitp', '2014-02-26 11:50:09', '10.1.16.13', 'my_menu.php'),
(43, 1, 'รายงานรายได้ลูกหนี้ทั่วไป	', 0, 'http://10.1.99.6/Bi!Reports/debtor_income_list.php', 2, 0, 'norejitp', '2014-02-26 11:50:58', '10.1.16.13', 'my_menu.php'),
(44, 1, 'ระบบบันทึกผู้ป่วยเบาหวานไทรอยด์', 0, 'http://10.1.99.6/it_dev/ed/ed!/portal.php', 2, 0, 'norejitp', '2014-02-21 04:12:31', '10.1.16.13', 'my_menu.php');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
