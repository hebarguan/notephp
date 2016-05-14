#框架说明

-----

Notephp以Smarty作为模板引擎的简约型php mvc框架,同时结合Mysql+Nginx(或Apache)+Mencached个人或小型网站开发提供支持,风格结构吸取国内优秀的Thinkphp框架,你可以轻松的阅读Noetphp 核心类文件里面的每行代码。可根据自己的需要更改里面的核心文件,或把你的想法Email给我hebarguan@gmail.com,有疑问 [这里](https://github.com/hebarguan/notephp/issues),也欢迎大家Pull Request！

#框架结构与运行

-----

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

-----

##入口文件常量说明

`APP_NAME`模块的名称,默认是Home,多模块请参见 [模块配置](#模块配置)

`DEBUG_ON`调试选项,开发阶段要显示错误信息建议设置为`true`,项目结束后再设置为`false` 

`ERROR_IGNORE_TYPE`不显示的错误类型,多个错误类型以`,`分开,设置后将不捕捉此类型的错误


##配置文件说明

项目的公共配置文件是目录`./Webapp/Common/Conf`下的`configure.php`

_**配置示例:**_

```php
<?php

return array(
    "DB_TYPE"               => "mysql",      // 数据库类型
    "DB_USER"               => "root" ,      // 数据库用户名
    "DB_HOST"               => "localhost",  // 数据库主机
    "DB_NAME"               => "notephp",    // 数据库名
    "DB_PASSWORD"           => "123456",       // 数据库密码
    "URL_HIDE_MODULE"       => true,         // 开启自动隐藏模块
    "URL_MODE"              => 1 ,
    "URL_MAP_RULES"         => array(        // 模式2路由重写
    ),
);
```
**配置提示:**

配置数据键必须是大写字母`DB_NAME`不能写成`db_name`.

更多配置选项请查看核心默认配置文件`./notephp/Common/Conf/default.php`

##模块配置

####单模块

开启路由自动隐藏模块功能`URL_HIDE_MODULE => true`
路由访问由`http://localhost/Home/Index/index`变为`http://localhost/Index/index`

####多模块

如果存在多个模块,请在配置文件里添加`MODULE_LIST`和`MODULE_DEFAULT`两个选项

_**配置示例:**_

```php
    "MODULE_LIST"         => "Home,Admin,Manage", // 模块列表
    "MODULE_DEFAULT"      => "Home",       // 默认模块,可以不设置默认是入口文件定义的APP_NAME
```
访问指定模块`http://localhost/Admin(模块名)/Index/index`

_**注意**_

如果开启了路由自动隐藏模块即`URL_HIDE_MODULE => true(默认是false)`,这里只对默认模块进行路由模块隐藏,将访问不了其它模块

若要开启自动隐藏模块,又要使用多模块功能,请参考[子域名部署](#子域名部署)

####创建多模块

在入口文件中更改`APP_NAME`配置选项的值,访问`http://localhost`即可创建成功

##子域名部署

**描述:**当访问某个定子域名时,要指定运行特定模块,可以使用子域名部署

_**配置:**_

```php
return array(
    "SUB_DOMAIN_RULES"  => array(
        "admin.example.com"   => "Admin",
        "manage.example.com"  => "Manage"
    ),
);
```

_**示例:**_

```
当访问admin.example.com时相当于访问www.example.com/Admin/
```

**提示:** 开发阶段可以先用`http://localhost/admin/`代替访问测试

##路由设置

**描述:**

路由的基本结构`http://localhost/(模块)/(控制器名)/(操作方法)`,

路由访问映射`http://localhost/  => http://location/(默认模块APP_NAME)/(默认控制器Index)/(默认操作index)`

也就是说`http://localhost/Home/` 等于访问 `http://localhost/Home/(默认控制器)Index/(默认操作)index`

`http://localhost/Home/Test/` 等于访问 `http://localhost/Home/Test/index(默认操作)`

若开启路由自动隐藏模块,访问`http://localhost/Index/index` 等于访问 `http://localhost/(模块名)/Index/index`

_**配置示例:**_

```php
return array(
    "URL_MODE"         => 1,   // 路由模式
    "URL_HIDE_MODULE"  => true, // 路由自动隐藏模块
);
```
####路由模式

#####模式一

_**示例:**_ `http://localhost/Home/Index/index?day=12&month=5&year=2016`

_**路由重写示例:**_

```php
return array(
    "URL_REWRITE_RULES" => array(
        "/^(\/test)/"  => "/index/out",
        // 访问http://localhost/test 等于访问 http://localhost/index/out
    ),
);
```
*提示:*路由重写的内容是路由`http://localhost`后面的字符串,匹配规则为正则表达式

#####模式二

_**示例:**_ `http://localhost/Home/index/index/day/12/month/5/year/2016`

_*路由映射示例:*_

```php
return array(
    "URL_MAP_RULES" => array(
        "/view/:day/:month/:year"  => "/index/test",
        // 访问http://localhost/view/12/5/2016 等同http://localhost/index/test/day/12/motch/5/year/2015
    ),
);
```
**注意:** 路由重写的GET参数是限制个数的,默认是6个即`/view/:day/:month/:year/:hour/:minute/:second/:invaild)`中的`invaild`无效,可以在配置文件添加自定义个数`GET_FIELDS_LENGTH => (int)`

#####路由其它设置

**伪静态:** 配置添加`URL_STATIC_SUFFIX => (string)'xhtml'`,路由`http://localhost/home/index/index`与`http://localhost/home/index/index.xhtml`等效

**区分大小写:** 只对操作方法有效,即`http://localhost/home/index/test`与`http://localhost/home/index/Test`是有区别的,配置选项`URL_CASE_INSENSITIVE => (bool)`,默认为`false`不区分大小写

##控制器

**描述:** 控制器为处理用户数据的逻辑层,一个操作方法最多可对应一个模板文件

**命名规则:** 以驼峰式命名控制器类文件，如`IndexController.class.php`且类名与控制器类名必须相同,配置参数有默认控制器`DEFAULT_CONTROLLER`，默认操作方法`DEFAULT_METHOD`;

_**控制器类示例:**_

```php
<?php

class IndexController extends Controller
{
    public function index() 
    {
        echo "Hi ~ You";
    }
}
```
**控制器内置操作方法**

- `assign()` 模板赋值操作

_*示例*_

```php
class IndexController Extends Controller
{
    public function index()
    {
        $sayHi = "Hi";
        $toWho = "hebar";
        $this->assign('word', $sayHi);
        $this->assign('name', $toWho);
        // 或使用数组模式
        $message = array('word' => $sayHi, 'name' => $toWho);
        $this->assign($message);
    }
}
```
- `display()` 模板显示操作,参数`$template` 默认为空,表示显示当前操作方法对应模板文件

_*示例*_

```php
public function index()
{
    $this->assign('foo', 'hello world !'); 
    // 显示模板
    $this->display();
    // 可以指定要显示模板文件,相对路径
    $templateFile = "./Webapp/Home/Home/View/Index/test.tpl";
    $this->display($templateFile);
}
```

