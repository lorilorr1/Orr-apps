-- phpMyAdmin SQL Dump
-- version 2.9.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 30, 2006 at 09:22 PM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `or!sample`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_`
-- 

CREATE TABLE `tbl_` (
  `id` int(11) NOT NULL auto_increment,
  `sec_user` varchar(20) NOT NULL default '',
  `sec_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sec_ip` varchar(20) NOT NULL default '',
  `sec_script` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tbl_`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_basic`
-- 

CREATE TABLE `tbl_basic` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `check` varchar(1) NOT NULL,
  `check1` varchar(1) NOT NULL,
  `check2` varchar(1) NOT NULL,
  `begin_date` date NOT NULL,
  `sec_user` varchar(20) NOT NULL default '',
  `sec_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sec_ip` varchar(20) NOT NULL default '',
  `sec_script` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tbl_basic`
-- 

INSERT INTO `tbl_basic` (`id`, `name`, `check`, `check1`, `check2`, `begin_date`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES 
(1, 'ทดสอบชื่อ', '1', '', '', '2006-11-08', 'root', '2006-11-30 21:21:22', '127.0.0.1', 'my_page.php');
