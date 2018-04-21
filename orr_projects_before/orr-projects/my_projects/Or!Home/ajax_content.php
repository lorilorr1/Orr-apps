<?php

require_once('../../orr_lib/Or.php');
require_once('config.inc.php');

$my_content = new OrContent("Hello {$_GET['name']}, welcome to the world of Dojo!\n");
$my_content->show();
?>