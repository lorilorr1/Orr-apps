<!DOCTYPE html>
<!--
Orr projects Home
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title><?php echo $page_value['title']; ?></title>
        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        <?php foreach ($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><?php echo $page_value['title']; ?></a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Page 1</a></li>
                    <li><a href="#">Page 2</a></li>
                    <li><a href="#">Page 3</a></li>
                </ul>
            </div>
        </nav>
        <?php
        // put your code here
        ?>
        <div>
            <?php echo anchor(site_url(), 'หน้าหลัก', ['title' => 'ไปที่หน้าหลักของเว๊บไซต์ หรือโหลดหน้าหลักใหม่']) ?> |
            <?php echo anchor(site_url('Project'), 'อ๋อโปรเจค', ['title' => 'ไปที่หน้าหลักของโปรเจค หรือโหลดหน้าโปรเจคใหม่']) ?> |
            <?php echo anchor(base_url("user_guide"), 'คู่มือ', ['title' => "คู่มือการใช้งาน"]) ?> |
            <?php echo anchor(site_url('Welcome/sign_in_page'), 'เข้าสู่ระบบ', ['title' => 'ไปที่หน้าเข้าระบบอื่นๆ']) ?>
            <?php echo $page_value['sign_status']; ?>
        </div> <div style='height:20px;'></div>  
        <div>
            <?php echo $page_value['topic']; ?>
        </div>
        <div class="jumbotron text-center">
            <h1>หน้าหลักของ อ๋อโปรเจค</h1>
            <p>ปรับขนาดหน้าเพจเพื่อดูผลการจัดหน้าจอ!</p> 
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <h3>Column 1</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
                </div>
                <div class="col-sm-4">
                    <h3>Column 2</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
                </div>
                <div class="col-sm-4">
                    <h3>Column 3</h3>        
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
                </div>
            </div>
        </div>

    </body>
</html>
