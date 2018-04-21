-- phpMyAdmin SQL Dump
-- version 3.0.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2011 at 04:56 PM
-- Server version: 5.0.67
-- PHP Version: 5.2.6-2ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `or!config`
--

-- --------------------------------------------------------

--
-- Table structure for table `my_shoutbox`
--

CREATE TABLE IF NOT EXISTS `my_shoutbox` (
  `id` int(11) NOT NULL auto_increment,
  `nick_name` varchar(50) NOT NULL COMMENT 'ชื่อแสดง',
  `detail` text NOT NULL,
  `sec_user` varchar(20) NOT NULL default '',
  `sec_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sec_ip` varchar(20) NOT NULL default '',
  `sec_script` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `sec_time` (`sec_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='ข้อความต่างๆ';

--
-- Dumping data for table `my_shoutbox`
--

INSERT INTO `my_shoutbox` (`id`, `nick_name`, `detail`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES
(1, 'ฝ่ายสารสนเทศ', 'กล่องข้อความ เพื่อฝากข้อมูลถึงผู้ใช้ระบบทุกคน<br />', 'orr', '2011-06-11 16:33:10', '10.1.16.16', 'my_shoutbox.php');
