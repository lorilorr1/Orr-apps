<?php
//ค่าเชื่อมต่อฐานข้อมูลเริ่มต้น
$my_cfg_db[host] = 'localhost';
$my_cfg_db[user] = 'orr-projects';
$my_cfg_db[password] = '';
$my_cfg_db[charset] = 'utf8'; //utf8 tis620for mysql5

//ค่าระบบการควบคุมสิทธิ์
$my_cfg_sec[db] = 'or!config';
$my_cfg_sec[ki] = 'linux';

//ค่าตัวเลือกอื่นๆ
$my_cfg_opt[global_register] = 'off'; //on  off
$my_cfg_opt[login_page] = '';//page เริ่มต้นกรณีไม่ได้ login
$my_cfg_opt[error_page] = ''; //page ที่ต้องการข้อผิดพลาด
$my_cfg_opt[after_login_page] = 'welcome.php'; //page ที่ต้องการแสดงหลังการ Login
//$my_cfg_opt[error_page] = 'error.php';//page ที่ต้องการแสดงกรณีเกิดข้อผิดพลาด
$my_cfg_opt[language] = 'thai'; //ภาษาที่ใช้ในระบบ
$my_cfg_opt[charset] = 'UTF-8'; //UTF-8 , TIS-620
$my_cfg_opt[debug] = 'off'; //on off
?>
