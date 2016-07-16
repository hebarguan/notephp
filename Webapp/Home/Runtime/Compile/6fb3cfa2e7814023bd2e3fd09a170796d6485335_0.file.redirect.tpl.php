<?php
/* Smarty version 3.1.29, created on 2016-05-31 18:22:57
  from "/home/hebar/notephp/notephp/Tpl/redirect.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574d66019700c8_39903838',
  'file_dependency' => 
  array (
    '6fb3cfa2e7814023bd2e3fd09a170796d6485335' => 
    array (
      0 => '/home/hebar/notephp/notephp/Tpl/redirect.tpl',
      1 => 1464690174,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574d66019700c8_39903838 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>重定向</title>
    <meta charset="utf-8">
</head>
<body>
<?php echo '<script'; ?>
 type="text/javascript">
var time = <?php echo $_smarty_tpl->tpl_vars['delayedTime']->value;?>
;
window.onload = function ()
{
    setInterval(rollTime, 1000);
}
function rollTime()
{
    var nowTime = time--;
    if (nowTime) {
        document.getElementById('time').innerHTML = nowTime;
    } else {
        window.location.href = "<?php echo $_smarty_tpl->tpl_vars['redirectUrl']->value;?>
";
    }
}
<?php echo '</script'; ?>
>

<center><h2><?php echo $_smarty_tpl->tpl_vars['redirectMsg']->value;?>
 <span id="time"></span></h2></center>
<center>页面没有自动跳转或想手动跳转?  <a href="<?php echo $_smarty_tpl->tpl_vars['redirectUrl']->value;?>
">这里</a></center>

</body>
</html>
<?php }
}
