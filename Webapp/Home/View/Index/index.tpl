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
<h2>{__PUBLIC__}</h2>
<div class="testdiv"></div>
{*
 *<img src="/index/echophotopath?name=test" width="200px" height="200px"  class="image" />
 *}
 <form method="post" action="/index/login" enctype="multipart/form-data">
     <input name="username" type="text" />
     <input name="password" type="password" />
     <input type="submit" value="send" />
 </form>
 
</body>
</html>
