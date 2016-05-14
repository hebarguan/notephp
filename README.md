#框架说明


Notephp以Smarty作为模板引擎的简约型php mvc框架,同时结合Mysql+Nginx(或Apache)+Mencached个人或小型网站开发提供支持,风格结构吸取国内优秀的Thinkphp框架,你可以轻松的阅读Noetphp 核心类文件里面的每行代码。可根据自己的需要更改里面的核心文件,或把你的想法Email给我hebarguan@gmail.com,有疑问 [这里](https://github.com/hebarguan/notephp/issues),也欢迎大家Pull Request！

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

####在Linux下：

```ppm
$ git clone git@github.com:hebarguan/notephp.git ~/根目录
$ chmod -R 777 根目录
```
####在windows下：

点击 [Download ZIP](https://github.com/hebarguan/notephp/archive/master.zip)解压直接将目录文件拷贝到你的根目录下

_*若要隐藏路由中*_`index.php`：

Apache下不用配置,根目录下有.htaccess文件

Nginx下：

```nginx
location / {

    if (!-f $request_filename) {
        rewrite ^(.*)$ /index.php?$1 last;
    }

}
```
#框架使用手册

##入口文件常量说明

`APP_NAME`模块的名称,默认是Home,多模块请参见 [模块配置](#模块配置)

`DEBUG_ON`调试选项,开发阶段要显示错误信息建议设置为`true`,项目结束后再设置为`false` 

`ERROR_IGNORE_TYPE`不显示的错误类型,多个错误类型以`,`分开,设置后将不捕捉此类型的错误


##配置文件说明

项目的公共配置文件是目录`./Webapp/Common/Conf`下的`configure.php`

_*配置例子:*_

```php
<?php

return array(
    "DB_TYPE"               => "mysql",      // 数据库类型
    "DB_USER"               => "root" ,      // 数据库用户名
    "DB_HOST"               => "localhost",  // 数据库主机
    "DB_NAME"               => "notephp",    // 数据库名
    "DB_PASSWORD"           => "guan",       // 数据库密码
    "URL_HIDE_MODULE"       => true,         // 开启自动隐藏模块
    "URL_MODE"              => 1 ,
    "URL_MAP_RULES"         => array(        // 模式2路由重写
        "/view/:id/:var/:ps/:oc/:cop"  => "/index/index",
    ),
    "URL_REWRITE_RULES"     => array(        // 模式1的路由重写
        "/^(\/ps)/"  => "/Index/out",
        "/^(\/emp)/" => "/Index/index",
    ),
    "SUB_DOMAIN_RULES"     => array(         // 子域名部署
        "manage.example.com"  => "Admin"     // 访问manage.example.com将指向Admin模块
    ),
);
```
_**配置提示:**_

配置数据键必须是大写字母`DB_NAME`不能写成`db_name`.

更多配置选项请查看核心默认配置文件`./notephp/Common/Conf/default.php`

##模块配置

_**单模块:**_

开启路由自动隐藏模块功能`URL_HIDE_MODULE => true`
路由访问由`http://localhost/Home/Index/index`变为`http://localhost/Index/index`

_**多模块**_：

如果存在多个模块,请在配置文件里添加`MODULE_LIST`和`MODULE_DEFAULT`两个选项

_*配置例子:*_

```php
    "MODULE_LIST"         => "Home,Admin,Manage", // 模块列表
    "MODULE_DEFAULT"      => "Home",       // 默认模块,可以不设置默认是入口文件定义的APP_NAME
```
访问指定模块`http://localhost/Admin(模块名)/Index/index`

*注意*

如果开启了路由自动隐藏模块即`URL_HIDE_MODULE => true(默认是false)`,这里只对默认模块进行路由模块隐藏,将访问不了其他模块

若要开启自动隐藏模块,又要使用多模块功能,请参考[子域名部署](#子域名部署)

_**创建多模块:**_

在入口文件中更改`APP_NAME`配置选项的值,访问`http://localhost`即可创建成功

##子域名部署



