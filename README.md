##框架手记
###条件查询Ｗhere
* 字符串 ：```where("id=5");```或 ```where("name='{变量}'") ;```
* 数组：```where(array("id" =>1,"name" => "hebar"))``` 或　```array("department"=>array("=","s10"),"salary"=>array(">",3000));```<br/>


### 条件查询Having
* 正确：```having('SUM(SALARY) > 20');或 having("name ='{变量}'")```;
* 错误：```having("name = $变量名")```;
