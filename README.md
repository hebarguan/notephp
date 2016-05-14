#框架说明


Notephp以Smarty作为模板引擎的简约型php mvc框架，同时结合Mysql+Nginx(或Apache)+Mencached个人或小型网站开发提供支持,风格结构吸取国内优秀的Thinkphp框架,你可以轻松的阅读Noetphp 核心类文件里面的每行代码。可根据自己的需要更改里面的核心文件，或把你的想法Email给我hebarguan@gmail.com，有疑问 [这里](https://github.com/hebarguan/notephp/issues)，也欢迎大家Pull Request！

#框架结构与运行

##项目目录结构
* 项目总目录`./Webapp`
* 模块目录 `./Webapp/Home(入口文件默认定义)`
* 项目日志记录目录 `./Webapp/Log`
* 项目自定义扩展目录 `./Webapp/Extends`
* 项目公共配置目录 `./Webapp/Common/Conf`
* 项目公共函数目录 `./Webapp/Common/Function(默认遍历里面所有php文件)`
* 模块模型目录 `./Webapp/Home/Model`
* 模块控制器目录 `./Webapp/Home/Controller`
* 模块模板文件目录 `./Webapp/Home/View`
* 模块模板编译目录 `./Webapp/Home/Runtime/Compile`
* 模块模板缓存目录 `./Webapp/Home/Runtime/Cache`
* 模块数据缓存目录 `./Webapp/Home/Runtime/Data`

##框架文件运行顺序

同大多数框架一样这里采用单入口文件,框架的运行文件顺序 :

```
(浏览器请求)

index.php 框架入口文件
Tunnel.php 环境初始化文件

[核心类库文件]
Notphp.class.php 框架核心初始化类文件
Url.class.php 核心路由处理类文件
ControllerDriver.class.php 控制器驱动文件
IndexController.class.php 用户默认控制器类文件
Model.class.php 核心模型类文件

[视图模板]
View.class.php 核心视图类文件
Smarty.class.php 模板引擎初始化文件

(页面或数据返回)

```
#安装/配置

在Linux下：

```ppm
$ git clone git@github.com:hebarguan/notephp.git ~/根目录
$ chmod -R 777 根目录
```
在windows下：

点击`Download ZIP`解压直接将目录拷贝到你的根目录下

若要隐藏路由中的`index.php`：

Apache下不用配置,根目录下有.htaccess文件
Nginx下：
```nginx
location / {

    if (!-f $request_filename) {
        rewrite ^(.*)$ /index.php?$1 last;
    }

}
```

