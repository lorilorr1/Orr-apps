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
        /*$th_month = new OrThdate();
        for ($i = 1; $i <= 12; $i++) {
            $this->set_axis_lable($i, $th_month->get_month($i,1));
        }*/
        //$this->set_axis_thday();
        $this->set_axis_thmonth();
        $this->set_chart_series('ยอดประจำเดือน - 2551', '[2000,5005,7020,8500,6520,9600,8500,12500,20300,8500,13000,24300]');
        $this->set_chart_series('ยอดประจำเดือน - 2552', '[10000,9200,11811,12000,7662,13887,14200,12222,12000,10009,11288,12099]');
        $this->set_chart_series('ยอดประจำเดือน - 2553', '[8000,10000,11900,10009,12300,9999,8700,11200,12569,13509,13989,13001]');
        $this->set_chart_series('ยอดประจำเดือน - 2554', '[3000,12000,17733,9876,12783,12899,13888,13277,14299,12345,12345,15763]');
        $this->show();
    }

}

$my = new my_page('ตัวอย่างการสร้างหน้าเพจแสดงกราฟ');
?>
