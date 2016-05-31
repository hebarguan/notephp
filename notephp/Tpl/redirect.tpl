<!DOCTYPE html>
<html>
<head>
    <title>重定向</title>
    <meta charset="utf-8">
</head>
<body>
<script type="text/javascript">
var time = {$delayedTime};
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
        window.location.href = "{$redirectUrl}";
    }
}
</script>

<center><h2>{$redirectMsg} <span id="time"></span></h2></center>
<center>页面没有自动跳转或想手动跳转?  <a href="{$redirectUrl}">这里</a></center>

</body>
</html>
