<?php

/*
 * เหตุการณ์ขณะที่อ่านข้อมูลแต่ละ รายการในตาราง
 * $EV_record[field_name] : ค่าของ field
 * val_filter[field_name] : ค่าที่ต้องการให้กรองข้อมูลของ field
 * ตัวอย่าง การสร้าง LInk เพื่อเชื่อม Page ใหม่
 * $event_link = '<a href="my_group.php?val_filter[group]=' . $EV_record[group] . '&val_filter[user]=' . $EV_record[user] .'&val_msg[btn_filter]=Filter" target="_parent" >' . $EV_record[user] . '</a>';
 * $this->controls[user]->OP_[text]->set($event_link);
 */
$this->controls[user]->set_ajax_tooltip('my_user_id_link.php?val_filter[user]=' . $EV_record[user]);
/*$event_link = '<a href="my_user.php?val_filter[id]=' . $EV_record[id] . '&val_msg[btn_filter]=Filter" target="_parent" >' . $EV_record[user] . '</a>';
$this->controls[user]->OP_[text]->set($event_link);*/

?>
