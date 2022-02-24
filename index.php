<?php
include_once "config.php";

//untuk keperluan datatables server-side
if(isset($_GET['api']))
{
    include_once "ajax/".$_GET['api'].".php";
    exit;
}

//request halaman sekaligus include template
include_once "header.php";
if(isset($_GET['page']))
{
    include_once "pages/".$_GET['page'].".php";
}else{
    include_once "pages/beranda.php";
}
include_once "footer.php";