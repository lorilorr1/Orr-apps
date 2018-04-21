-- phpMyAdmin SQL Dump
-- version 3.0.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 04, 2011 at 03:27 PM
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
-- Table structure for table `my_registration`
--

CREATE TABLE IF NOT EXISTS `my_registration` (
  `id` int(11) NOT NULL auto_increment,
  `open_access` datetime NOT NULL COMMENT 'ครั้งแรกที่เริ่มใช้งาน',
  `last_note_id` int(11) NOT NULL COMMENT 'โน๊ตที่อ่านล่าสุด',
  `accept_note` datetime NOT NULL COMMENT 'วันที่เวลาที่อ่านประกาศครั้งล่าสุด',
  `computer_name` varchar(50) NOT NULL COMMENT 'ชื่อเครื่องคอมพิวเตอร์',
  `sec_user` varchar(20) NOT NULL default '',
  `sec_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sec_ip` varchar(20) NOT NULL default '',
  `sec_script` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `sec_ip` (`sec_ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='บันทึกการเข้าใช้งานระบบ' AUTO_INCREMENT=247 ;
