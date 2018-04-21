<?php
$event_link = '<a href="my_group.php?val_filter[group]=' . $EV_record[group] . '&val_compare[group]==&val_filter[user]=' . $EV_record[user] .'&val_compare[user]==&val_msg[btn_filter]=Filter" target="_self" >' . $EV_record[user] . '</a>';
$this->controls[user]->OP_[text]->set($event_link);
?>
