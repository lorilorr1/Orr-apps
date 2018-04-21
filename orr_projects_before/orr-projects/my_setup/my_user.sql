-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- โฮสต์: localhost
-- เวลาในการสร้าง: 22 เม.ย. 2011  19:40น.
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
-- dump ตาราง `my_user`
--

INSERT INTO `my_user` (`id`, `user`, `val_pass`, `prefix`, `fname`, `lname`, `status`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES
(1, 'root', 0x3161316463393163393037333235633639323731646466306339343462633732, 'คุณ', 'ผู้ดูแลระบบ', 'ทดสอบ', 0, 'root', '2006-06-03 20:03:06', '', '');
