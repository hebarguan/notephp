<?php
/* Smarty version 3.1.29, created on 2016-06-01 17:51:49
  from "/home/hebar/notephp/Webapp/Home/View/Index/dp.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574eb0353fdd28_06814405',
  'file_dependency' => 
  array (
    'dafb36282fa39cb690328190117a39d1881b6b4f' => 
    array (
      0 => '/home/hebar/notephp/Webapp/Home/View/Index/dp.tpl',
      1 => 1464774671,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574eb0353fdd28_06814405 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '805307848574eb03539a1a8_83682486';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Bootstrap 实例</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/Public/plugin/btp/css/bootstrap.min.css">  
  <?php echo '<script'; ?>
 src="/Public/javascript/jquery-1.12.2.min.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="/Public/plugin/btp/js/bootstrap.min.js"><?php echo '</script'; ?>
>
</head>
<body>

<div class="jumbotron">
  <div class="container">
    <h1>我的第一个 Bootstrap 页面</h1>
    <p>重置窗口大小，查看响应式效果！</p> 
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
      <h3>第一列</h3>
      <p>学的不仅是技术，更是梦想！</p>
      <p>再牛逼的梦想,也抵不住你傻逼似的坚持！</p>
    </div>
    <div class="col-sm-4">
      <h3>第二列</h3>
      <p>学的不仅是技术，更是梦想！</p>
      <p>再牛逼的梦想,也抵不住你傻逼似的坚持！</p>
    </div>
    <div class="col-sm-4">
      <h3>第三列</h3>        
      <p>学的不仅是技术，更是梦想！</p>
      <p><span class="glyphicon glyphicon-user visible-lg"></span></p>
    </div>
  </div>
  <table class="table table-hover">
      <thead>
          <tr>
              <td>列1</td>
              <td>列2</td>
              <td>列3</td>
              <td>列4</td>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>001$</td>
              <td>002$</td>
              <td>003$</td>
              <td>004$</td>
          </tr>
          <tr>
              <td>001$</td>
              <td>002$</td>
              <td>003$</td>
              <td>004$</td>
          </tr>
          <tr>
              <td>001$</td>
              <td>002$</td>
              <td>003$</td>
              <td>004$</td>
          </tr>
      </tbody>
  </table>
  
  <form class="form-horizontal" role="form" action="">
      <div class="form-group">
          <label for="name" class="col-sm-2 control-label">姓名</label>
          <div class="col-sm-10"><input id="name" type="text" class="form-control" placeholder="请输入姓名" /></div>
      </div>
      <div class="form-group">
          <label for="date" class="col-sm-2 control-label">日期</label>
          <div class="col-sm-10"><input id="date" type="date" class="form-control" /></div>
      </div>
      <div class="form-group">
          <div class="col-sm-8 col-sm-offset-2"><button class="btn btn-primary btn-lg btn-block">提交</button></div>
      </div>
  </form>
  <ul class="pagination">
      <li><a href="">&laquo</a></li>
      <li class="active"><a href="">1</a></li>
      <li><a href="">2</a></li>
      <li><a href="">3</a></li>
      <li><a href="">4</a></li>
      <li><a href="">&raquo</a></li>
  </ul>
  <label for="" class="label label-danger">危险标签</label>
  <div class="col-sm-6 col-sm-offset-3"><ul class="nav nav-pills">
      <li class="active"><a href="">首页<span class="badge">32</span><li><a href="">简介</a></li><li><a href="">提示<span class="badge">12</span></a></li></a></li>
  </ul></div>
<div class="row">
    <div class="col-lg-4">
        <div class="thumbnail">
            <img src="/Public/image/test.jpg" alt="" />
            <div class="caption">
                <h3>这是图片标题</h3>
                <p>这是图片的文字内容这里添加更多细节</p>
                <p>
                    <a role="button" href="" class="btn btn-primary">购买产品</a>
                    <a role="button" href="" class="btn btn-default">查看细节</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="thumbnail">
            <img src="/Public/image/test.jpg" alt="" />
            <div class="caption">
                <h3>这是图片标题</h3>
                <p>这是图片的文字内容这里添加更多细节</p>
                <p>
                    <a role="button" href="" class="btn btn-primary">购买产品</a>
                    <a role="button" href="" class="btn btn-default">查看细节</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="thumbnail">
            <img src="/Public/image/test.jpg" alt="" />
            <div class="caption">
                <h3>这是图片标题</h3>
                <p>这是图片的文字内容这里添加更多细节</p>
                <p>
                    <a role="button" href="" class="btn btn-primary">购买产品</a>
                    <a role="button" href="" class="btn btn-default">查看细节</a>
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_smarty_tpl->tpl_vars['end']->value+1 - ($_smarty_tpl->tpl_vars['start']->value) : $_smarty_tpl->tpl_vars['start']->value-($_smarty_tpl->tpl_vars['end']->value)+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = $_smarty_tpl->tpl_vars['start']->value, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration == 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration == $_smarty_tpl->tpl_vars['foo']->total;?>
<li>列表<?php echo $_smarty_tpl->tpl_vars['foo']->value;?>
</li>
<?php }
}
?>

</body>
</html>

<?php }
}
