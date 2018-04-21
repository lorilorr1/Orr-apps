<?php

//
//OrTextCalendar.php
//
//PHP versions 4 and 5
//
//LICENSE: This source file is subject to version 3.0 of the PHP license
//that is available through the world-wide-web at the following URI:
//http://www.php.net/license/3_0.txt.  If you did not receive a copy of
//the PHP License and are unable to obtain it through the web, please
//send a note to license@php.net so we can mail you a copy immediately.
//
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart_bu@yahoo.com>
//@copyright  1997-2005 The PHP Group
//@license    http://www.php.net/license/3_0.txt  PHP License 3.0
//@version    2550
//
//
//Class ช่องข้อมูลวันที่ เวลา
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart_bu@yahoo.com>
//@copyright  1997-2005 The PHP Group
//@license    http://www.php.net/license/3_0.txt  PHP License 3.0
//@version    2550

class OrTextCalendar extends OrControls {

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);

        $this->OP_[db_type]->set('text');
    }

    //
    //get_tag: html tag Query form
    //@param string $value
    //@return string html tag

    function get_tag($value = null) {
        $str_date = new OrMySqlThDate();
        /**
         * Checking $vaule
         */
        if ($value != null) {
            $this->OP_[value]->set($value);
        }
        /**
         * Checking auto_post property
         */
        if ($this->OP_[auto_post]->get()) {
            $this->auto_post();
        }

        $id = $this->get_id_tag();
        $value = $str_date->get_th_str($this->OP_[value]->get());

        //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | value =".$value."<br>";
        if ($value == null) {
            $value = $this->OP_[post_value]->get();
        }

        if ($value == null) {
            $value = $str_date->get_th_str($this->OP_[default_value]->get());
        }

        if ($this->OP_[class_name]->get() == null) {
            $class = null;
        } else {
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        }

        $this->clip_value($str_date->get_mysql_str($value));
        $type = 'type = "text"';
        $value = 'value="' . $value . '"';
        $title = 'title="' . $this->OP_[title]->get() . '"';
        $this_id = $this->OP_[id]->get();
        $this_format = 'dd/mm/bbbb';
        //$my_value = '<input type="text" name="theDate" readonly="" value="2004/02/02"/>';
        $my_value = "<input $id $class  $type $value $title maxlength=\"10\" size=\"10\" >" . $this->get_properties_tag();
        $my_value .= '<button title="ปฏิทิน" value="Cal" class="toolbar" type="button" onclick="displayCalendar(document.forms[0].' . $this_id . ',\'' . $this_format . '\',this)" ><img src="../../lib/calendar/image/calendar.png"/></button>';
        //$my_value .= '<input type="button" onclick="displayCalendar(document.forms[0].' . $this_id . ',\'' . $this_format . '\',this)" value="Cal">';
        return $my_value;
    }

}

?>
