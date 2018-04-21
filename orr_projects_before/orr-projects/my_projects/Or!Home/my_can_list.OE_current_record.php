<?php
$event_link = '<a href="my_can.php?val_filter[sys_id]=' . $EV_record[sys_id] . '&val_filter[user]=' . $EV_record[user] .'&val_msg[btn_filter]=Filter" target="_self" >' . $EV_record[user] . '</a>';
$this->controls[user]->OP_[text]->set($event_link);
?>
