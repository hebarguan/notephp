#框架说明


Notephp以Smarty作为模板引擎的简约型php mvc框架,同时结合Mysql+Nginx(或Apache)+Mencached个人或小型网站开发提供支持,风格结构吸取国内优秀的Thinkphp框架,你可以轻松的阅读Noetphp 核心类文件里面的每行代码。可根据自己的需要更改里面的核心文件,或把你的想法Email给我hebarguan@gmail.com,有疑问 [这里](https://github.com/hebarguan/notephp/issues),也欢迎大家Pull Request！

#框架目录结构


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


#安装/配置

####在Linux下：
```ppm
$ git clone git@github.com:hebarguan/notephp.git ~/根目录
$ chmod -R 777 根目录
```
####在windows下：

点击 [Download ZIP](https://github.com/hebarguan/notephp/archive/master.zip)解压直接将目录文件拷贝到你的根目录下

_*若要隐藏路由中*_`index.php`：

**Apache下:** 不用配置,根目录下有.htaccess文件

**Nginx下:**
```nginx
location / {

    if (!-f $request_filename) {
        rewrite ^(.*)$ /index.php?$1 last;
    }

}
```
#框架使用手册

* [入口文件](#入口文件)
* [配置文件](#配置文件)
* [模块](#模块)
* [子域名部署](#子域名部署)
* [路由模式](#路由)
* [控制器](#控制器)
* [模型](#模型)
* [视图/模板](#视图-模板)
* [储存/缓存](#储存/缓存)
* [内置函数](#内置函数)
* [附录](#附录)

##入口文件

**描述:** 框架的入口文件,在这例可以添加自己的设置和常量

**常量:**

`APP_NAME`模块的名称,默认是Home,若有多模块,该值将是默认模块

`DEBUG_ON`调试选项,开发阶段要显示错误信息建议设置为`true`,项目结束后再设置为`false` 

`ERROR_IGNORE_TYPE`不显示的错误类型,多个错误类型以`,`分开,设置后将不捕捉此类型的错误

**提示:** 更改`APP_NAME`的值,重新运行,可创建新模块,多模块通过该方法创建

##配置文件

**描述:** 项目的公共配置文件是目录`./Webapp/Common/Conf`下的`configure.php`

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
);
```
**配置提示:**

配置数据键必须是大写字母`DB_NAME`不能写成`db_name`.

更多配置选项请查看核心默认配置文件`./notephp/Common/Conf/default.php`

##模块

####单模块

**描述:** 第一次运行时自动创建的模块,多模块也同样使用该方法创建,只需要更改入口文件内的`APP_NAME`即可

**提示:**开启路由自动隐藏模块功能`URL_HIDE_MODULE => true`
路由访问由`http://localhost/Home/Index/index`变为`http://localhost/Index/index`

####多模块

**描述:** 多用于功能块分发,例如前台模块,后台模块

**配置参数:** 如果存在多个模块,请在配置文件里添加`MODULE_LIST`和`MODULE_DEFAULT`两个选项

**访问方式:** `http://localhost/Admin(模块名)/Index/index`

_**配置示例:**_
```php
    "MODULE_LIST"         => "Home,Admin,Manage", // 模块列表
    "MODULE_DEFAULT"      => "Home",       // 默认模块,可以不设置默认是入口文件定义的APP_NAME
```

_**注意**_

如果开启了路由自动隐藏模块即`URL_HIDE_MODULE => true(默认是false)`,这里只对默认模块进行路由模块隐藏,将访问不了其它模块

若要开启自动隐藏模块,又要使用多模块功能,请参考[子域名部署](#子域名部署)



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
当访问admin.example.com时相当于访问www.example.com/admin/
```

**提示:** 开发阶段可以先用`http://localhost/admin/`代替访问测试

##路由模式

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

**命名规则:** 以驼峰式命名控制器类文件，如`IndexController.class.php`且类名与控制器类名必须相同

**配置参数:** 配置参数有默认控制器`DEFAULT_CONTROLLER`，默认操作方法`DEFAULT_METHOD`;

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
- `show()` 输出数据内容,可以是HTML实体，或普通字符串

_*实例*_
```php
public function index() 
{
    $html = "&lt;h1&gt; 标题 &lt;/h1&gl";
    // 或html字符串 $html = "<h1>标题</h1>";
    // 或
    $this->show($html);
}
```
- `dataReturn()` 返回数据,配置`DATA_RETURN_TYPE`,默认是`json`

*示例*
```php
public function index() 
{
    $data = array('states' => 1, 'msg' => 'success');
    $this->dataReturn($data);
}
```
- `redirect()` 路由重定向,可以是完整路由，也可以是控制器操作方法

*示例*
```php
public function index() 
{
   /*
    * @param $url 重定向路由
    * @param $msg 跳转页面的提示
    * @param $time 延迟时间
    * 跳转模板对应的三个变量{$redirectMsg} {$delayedTime} {$redirectUrl}
    * 重定向跳转模板文件为配置 REDIRECT_FILE, 默认是./notephp/Tpl/redirect.tpl
    */
    $this->redirect('https://www.baidu.com');
    // 跳转提示,5秒跳转到http://localhost/index/dita
    $this->redirect('/index/dita', '跳转中...', 5);
    
}
```

##模型

**描述:** 用于数据库操作

**配置参数示例:**
```php
return array(
    "DB_TYPE"               => "mysql",      // 数据库类型
    "DB_USER"               => "root" ,      // 数据库用户名
    "DB_HOST"               => "localhost",  // 数据库主机
    "DB_NAME"               => "notephp",    // 数据库名
    "DB_PASSWORD"           => "123456",     // 数据库密码
    "DB_PERSISTENT_LINK"    => false,        // 数据库持久连接,默认为false,不持久连接
    "CURD_TYPE"             => "mysql",      // 数据库操作扩展 mysql,DatabaseObject(PDO)两种，默认是mysql
    "MYSQL_CONNECT_ENCODING"=> "utf8" ,      // 数据库链接编码,默认是utf8防止中文乱码
);
```

**实例该类:**
```php
public function index()
{
   /* 使用内置函数M()实例方法
    * M函数有两个参数$table,$bool
    * @param $table 要实例的模型或数据库表
    * @param $bool 用户模型是否存在,默认是true,说明存在文件./Webapp/Home/Model/($table)Model.class.php
    */
    $mode = M('user');
    // 如User模型不存在
    $mode = M('user', false); // 将直接实例user数据库表,且数据库表是由小写字母组成
    $data = $mode->execute(1); // 查找id=2的数据，返回一个一维数组
    var_dump($data);
}
```
**提示:**
```php
public function index()
{
   /* 若想使用自定义数据库操作
    * 模型提供自定义数据库操作链接柄
    * @param $curd 
    */
    $mode = M('user', false); // 或使用$mode = new Model();
    $curd = $mode->curd;
    $result = $curd->query('SELECT * FROM user WHERE id = 1');
    $data = $result->fetch_assoc();
    var_dump($data);
}
```

###连贯操作

1. [条件方法](#条件方法)
    * [字段查询/fields](#fields)
    * [行数限制/limit](#limit)
    * [条件组合/where](#where)
    * [依据排序/order](#order)
    * [查询数据/data](#data)
    * [组合查询/group](#group)
    * [包含条件/having](#having)
    * [检测条件/check](#check)
    * [事务滚动/trans](#trans)
    * [预处理/stmt](#stmt)
1. [终止方法](#终止方法)
    * [选择查询/execute](#execute)
    * [修改查询/save](#save)
    * [插入数据/add](#add)
    * [删除数据/delete](#delete)
    * [返回SQL语句/returnSql](#returnSql)

####条件方法

**描述:** 该类方法用于数据库CURD筛选条件,不区分调用顺序,即`$mode->fields()->trans()`与`$mode->trans()->fields()`等效

**提示:** 使用条件方法查找获取数据时，将返回一个二维数组且每个元素代表一个字段,值对应的数据行

#####fields

**描述:** 查找数据行指定字段

**示例:**
```php
public function index()
{
   /* 不调用该方法,表示查找所有字段
    * 参数为字符串类型,多个字段以`,`分开
    */
    $mode = M('employee', false);
    $data = $mode->fields('name,salary')->execute();
    // 等效的SQL语句为SELECT name,salary FROM employee
    $data = $mode->fields('DISTINCT department')->execute();
   /* fields('COUNT(*) AS members')
    * fields('SUM(salary)')
    * 这里可以添加各种数据库字段查询函数
    */
}
```

#####limit

**描述:** 限制查询的行数

**示例**
```php
public function index()
{
   /* 该方法有连个参数$offset起始行,$rows行数
    * 当只有一个参数的时候,表示查找前$param 行
    */
    $rows = $mode->limit(6)->execute(); // 将返回前6行
    $rows = $mode->limit(3, 4)->execute(); // 从第3行起，返回4行
}
```
#####where

**描述:** 数据库条件查询

**示例:**
```php
public function index()
{
   /* (字符串)方式
    * 字符串类型更接近源生的Where条件,所以将不对数据进行过滤
    * 要使用字符串类型需要自己手动过滤数据,且要求掌握Mysql语句风格防止语法错误或漏洞
    * 建议只对简单的数字型数据提供查询
    * 例如Where('id=2') 大多数情况建议使用数组模式
    */
    $userInput = intval($_GET['id']);
    $query = $mode->where("id=$userInput")->execute();

   /* (单字段)数组模式
    * 下面将对各种(单字段)组合模式进行举例 
    */
    $condition = array('id' => 2);
    $query = $mode->where($condition)->execute(); 
    // 对应的SQL语句是 'SELECT * FROM employee WHERE id=2 ' 
    $condition = array('id' => array('>', 10));
    // 对应的SQL语句是 'SELECT * FROM employee WHERE id>2 ' 
    $condition = array('id' => array(array('>', 3), array('<', 20), 'AND'));
    // 对应SQL语句 'SELECT * FROM employee WHERE id > 3  AND id < 20' 
    // 特殊条件符号NOT IN, IN
    $condition = array('id' => array('IN', '2,5,8')); 
    // 字符串必须要用数组模式
    $condition = array('name' => array('IN', array('li', 'zhang', 'wu'))); 
    // BETWEEN NOT BETWEEN举例
    $condition = array('id' => array('BETWEEN', '1,3'));
    // 更多特殊查询
    $condition = array('name' => array('LIKE', '%e\n'\%''));
    $condition = array('name' => array('REGEXP', 'guan$'));

   /* (多字段)数组模式
    * 提示：多字段与单字段唯一不同在于,多字段条件数组可以使用多个元素
    * 注意：多字段的个数无限制,最后一个元素必须为并列符号，即AND或OR
    * 当只有两个字段时，并列符号可以不填，默认是AND
    * 下面对多字段进行举例
    */
    $condition = array(
        'department' => 'hr',
        'salary' => array(array('>', 5000), array('<', 20000)),
        'id' => array(array('BETWEEN', '2,16'), array('NOT IN', '5,11')),
        'AND'
    );
    // 对应SQL语句
    'SELECT * FROM employee 
        WHERE 
        (department='hr') AND 
        (salary > 5000  AND salary < 20000 ) AND
        (id BETWEEN  2 AND 16  AND id NOT IN (5,11) ) 
    ' 
}
```
#####order

**描述:** 对数据行按指定字段排序

**示例:**
```php
public function index() 
{
   /* 参数为字符串
    * 多个字段以','分开
    * 常见ASC升序，DESC降序
    */
    $query = $mode->order('salary desc')->execute();
    // 多个字段排序
    $query = $mode->order('on_duty asc,salary desc')->execute();
}
```
#####data

**描述:** 对数据进行过滤

**示例:**
```php
public function index
{
   /* 参数为数组
    * 通常用于数据修改和数据写入过滤
    */
    $inputData = array('name' => 'zhao', 'sex' => 1, 'salary' => 6000, 'department' => 'it');
    $insertID = $mode->data($inputData)->add();
    // 对数据修改
    $updataData = array('department' => 'hr');
    $affectedRows = $mode->data($updataData)->where(['name' => 'zhao'])->save();
    // 对于数字型数据自增,建议使用下面方法
    $increment = array('salary=salary+500' => '');
    $affectedRows = $mode->data($increment)->where(['department' => 'hr'])->execute();
}
```
#####group

**描述:** 对字段进行按组查询

**示例:**
```php
public function index()
{
   /* 参数为字符串
    * 对于非ENUM()设置的字段同样有效
    */
    $query = $mode->group('name,salary')->execute();
    // 也可以结合having使用
    $query = $mode->fields('COUNT(*) AS members')->group('sex')->having(array('sex' => 'F'))->execute();
}
```
