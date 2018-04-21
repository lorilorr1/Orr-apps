-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- โฮสต์: localhost
-- เวลาในการสร้าง: 22 เม.ย. 2011  19:42น.
-- รุ่นของเซิร์ฟเวอร์: 5.1.49
-- รุ่นของ PHP: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- ฐานข้อมูล: `or!config`
--

--
-- dump ตาราง `my_sys`
--

INSERT INTO `my_sys` (`sys_id`, `any_use`, `aut_user`, `aut_group`, `aut_any`, `aut_god`, `aut_can_from`, `title`, `description`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES
('my_can.php', 0, 2, 2, 0, 1, '', 'สิทธิ์การใช้โปรแกรม', '', 'root', '2006-07-08 14:52:47', '10.1.16.4', 'my_sys.php'),
('my_group.php', 0, 2, 0, 0, 1, '', 'กลุ่มผู้ใช้ระบบ', '', 'root', '2006-07-06 21:38:24', '10.1.16.4', 'my_sys.php'),
('my_sys.php', 0, 2, 0, 0, 1, '', 'โปรแกรม', '', 'root', '2006-07-16 13:27:56', '', ''),
('my_user.php', 1, 2, 2, 0, 1, '', 'ผู้ใช้ระบบ', '', 'root', '2006-07-08 20:49:28', '', ''),
('my_datafield.php', 0, 2, 1, 0, 1, '', 'เขตข้อมูล', 'กำหนดรายละเอียด ของ Field ข้อมูลที่ใช้งานในระบบ', 'root', '2006-07-08 15:17:53', '127.0.0.1', 'my_sys.php'),
('my_can_list.php', 0, 0, 0, 0, 1, '', 'รายการผู้มีสิทธิ์ใช้ระบบ', 'แสดงข้อมูลผู้ใช้ระบบ ที่ระบุให้สามารถเข้าใช้โปรแกรมได้', 'root', '2006-07-10 16:22:27', '127.0.0.1', 'my_sys.php'),
('my_sys_list.php', 1, 0, 0, 0, 1, '', 'รายการโปรแกรม', '', 'root', '2011-01-10 14:47:16', '127.0.0.1', 'my_sys.php'),
('ex_page_list.php', 1, 0, 0, 0, 0, '', 'page list testing', '', 'root', '2011-02-21 19:58:56', '127.0.0.1', 'my_sys.php'),
('ex_popup_list.php', 1, 0, 0, 0, 0, '', 'หน้าจอป็อบอัพ', '', 'root', '2011-02-21 20:33:16', '127.0.0.1', 'my_sys.php'),
('my_activity_list.php', 0, 0, 0, 0, 0, '', 'รายงานกิจกรรมในระบบ', '<b> รายงานเกี่ยวกับการเกิดกิจกรรมในระบบ</b><br /><ol><li>การเข้าใช้งานระบบ</li><li>การบันทึก แก้ไข ลบ ข้อมูลจากโปรแกรม</li></ol>', 'root', '2011-04-14 22:04:14', '127.0.0.1', 'my_sys.php'),
('my_sys_popup_list.php', 1, 1, 1, 1, 1, '', 'รายการโปรแกรมที่ลงทะเบียน', 'เพื่อแสดงรายชื่อโปรแกรมที่มีลงทะเบียนสำหรับเลือกใช้งาน', 'root', '2011-04-19 10:10:17', '127.0.0.1', 'my_sys.php'),
('my_user_popup_list.php', 1, 1, 1, 1, 1, '', 'รายการผู้ใช้ระบบที่ลงทะเบียน', '<br _moz_editor_bogus_node="TRUE" />', 'root', '2011-04-20 01:51:17', '127.0.0.1', 'my_sys.php'),
('my_user_list.php', 1, 1, 1, 1, 1, '', 'รายการผู้ใช้ระบบที่ลงทะเบียน', '<br _moz_editor_bogus_node="TRUE" />', 'orr', '2011-04-20 10:14:23', '127.0.0.1', 'my_sys.php'),
('ex_cross_tab_list.php', 1, 1, 1, 1, 1, '', 'รายงานตัวอย่าง Cross Tab', '<br _moz_editor_bogus_node="TRUE" />', 'root', '2011-04-21 16:26:31', '127.0.0.1', 'my_sys.php'),
('ex_chart_list.php', 1, 1, 1, 1, 1, '', 'ตัวอย่างการสร้างหน้ากราฟจากฐานข้อมูล', '<br _moz_editor_bogus_node="TRUE" />', 'root', '2011-04-21 18:03:38', '127.0.0.1', 'my_sys.php'),
('my_statistics.php', 1, 2, 2, 1, 1, '', 'บันทึกสถิติประจำปี', '<br _moz_editor_bogus_node="TRUE" />', 'root', '2011-04-21 20:22:11', '127.0.0.1', 'my_sys.php');
