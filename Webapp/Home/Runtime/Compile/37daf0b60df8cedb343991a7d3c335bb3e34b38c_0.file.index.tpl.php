<?php
/* Smarty version 3.1.29, created on 2016-06-01 18:19:59
  from "/home/hebar/notephp/Webapp/Home/View/Index/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574eb6cf1b3948_90208018',
  'file_dependency' => 
  array (
    '37daf0b60df8cedb343991a7d3c335bb3e34b38c' => 
    array (
      0 => '/home/hebar/notephp/Webapp/Home/View/Index/index.tpl',
      1 => 1464776395,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574eb6cf1b3948_90208018 ($_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
    <style >
    .testdiv { position:relative;padding:20px 50px; width:300px;height:300px;background:#000; }
    </style>
</head>
<body>    
<h2><?php echo __PUBLIC__;?>
</h2>
<div class="testdiv"></div>

 <form method="post" action="/index/login" enctype="multipart/form-data">
     <input name="username" type="text" />
     <input name="password" type="password" />
     <input type="submit" value="send" />
 </form>
 
</body>
</html>
<?php }
}
