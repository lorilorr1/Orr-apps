<?php

/*
 * ตัวอย่างการสร้างหน้าจอ HTML
 */
require_once('../../orr_lib/Or.php');
require_once('config.inc.php');

class my_page extends OrDojoChart {

    function __construct($title = '') {
        global $my_cfg;
        parent::__construct($title);
        $this->set_series_data();
        /*$this->set_chart_series('ยอดประจำเดือน - 2552', '[10000,9200,11811,12000,7662,13887,14200,12222,12000,10009,11288,12099]');
        $this->set_chart_series('ยอดประจำเดือน - 2553', '[8000,10000,11900,10009,12300,9999,8700,11200,12569,13509,13989,13001]');
        $this->set_chart_series('ยอดประจำเดือน - 2554', '[3000,12000,17733,9876,12783,12899,13888,13277,14299,12345,12345,15763]');*/
        $this->show();
    }

    function set_series_data() {
        global $my_cfg;
        $my_sec = new OrSec();
        $my_db = new OrMysql($my_cfg[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT * FROM `my_statistics`"; //(กำหนด SQL ตามเงื่อนไขที่ต้องการ)
        $my_db->get_query($sql);
        while($my_db->get_record()){
            $this->set_chart_series($my_db->record[name], '[' . $my_db->record[month_01] . ','. $my_db->record[month_02] . ','. $my_db->record[month_03] . ','. $my_db->record[month_04] . ','. $my_db->record[month_05]
                    . ','. $my_db->record[month_06] . ','. $my_db->record[month_07] . ','. $my_db->record[month_08] . ','. $my_db->record[month_09] . ','. $my_db->record[month_10] . ','. $my_db->record[month_11] . ','. $my_db->record[month_12] .']');
        }
        unset($my_db);
        return null;
    }

}

$my = new my_page('ตัวอย่างการสร้างหน้ากราฟจากฐานข้อมูล');
?>
