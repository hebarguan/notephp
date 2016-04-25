<?php
 /*
  * Edited by hebarguan 2016-1-20
  * Email hebarguan@hotmail.com
  * 这个模型只能运行在mysql数据库内
  * 暂时不支持用在其它数据库上
  */
 /*
  * @param $m = M("employee",false); 
  * @param $d = $m->where(array("id"=>array("IN",array(1,3))))->execute();
  * @param $d = $m->where(array("id"=>array("BETWEEN","2,4")))->execute();
  * @param $d = $m->where(array("name"=>"zhao"))->execute();
  * @param $d = $m->add(array("id"=>5,"name" => "老王"));
  * @param $d = $m->where("id=5")->delete();
  * @param $d = $m->fields("sex,COUNT(sex)")->group("sex")->having(array("sex"=>array("=","M")))->execute();
  * @param $d = $m->data(array("name"=>"赵"))->where("id=2")->save();
  */

class Model {
    // 定义要执行的逻辑查询类成员$_sql
    public $_sql = array(
        "fid" => "*" , //默认为查询所有字段
        "lit" => "" ,  //查询行限制
        "ord" => "" ,  //查询排序
        "wre" => "" ,  // 条件查询
        "exe" => "" , // 执行查询
        "num" => "" , // 表行数
        "dat" => "" , // 输入数据
        "gro" => "" , // 组查询
        "hav" => "" , // 与组查询并用
    );
    // 定义数据库的用户条件权限
    private  $mysqlInfo = array(
        "HOSTS"    => "" ,// 服务器名称或iP地址
        "ROOT"     => "" ,// 数据库用户名
        "PASSWORD" => "" ,// 数据库用户密码
        "DB_NAME"  => "" , //数据库名
    );
    // 数据库链接表
    public $dbTable = null;
    // 数据链接编码
    public $connectEncoding = null;
    // 数据库表列表
    public $dbTableList = array();
    // 数据库链接柄 用户自定义数据库操作
    public $curd = false;
    // 数据库CURD操作类型，默认是MYSQL
    public $curdType = null;
    // 是否只是执行查询，为true只返回查询影响行数
    public $dbCheck    = false;
    // 是否开启是否开启事务滚动
    public $transaction = false;
    // 是否开启预处理
    public $pretreatment = false;
    // 是否返回sql语句
    public $returnSqlStent = false;
    // 特殊字段查询符号
    public $specialQuerySymbol = array('NOT IN', 'IN', 'BETWEEN', 'NOT BETWEEN');
    // 是否开启持久链接
    // 定义构造函数
    public function __construct( $tab = null ) {
        // 初始化数据库信息
        $this->mysqlInfo['HOSTS']    = C('DB_HOST');
        $this->mysqlInfo['ROOT']     = C('DB_USER');
        $this->mysqlInfo['PASSWORD'] = C('DB_PASSWORD');
        $this->mysqlInfo['DB_NAME']  = C('DB_NAME');
        $this->mysqlInfo['DB_TYPE']  = C('DB_TYPE');
        $this->connectEncoding       = C('MYSQL_CONNECT_ENCODING');
        $this->curdType              = C('CURD_TYPE');
        $this->dbPersistentLink      = C('DB_PERSISTENT_LINK');
        // 开始数据库连接
        $this->connect();
        // 获取调用该基类的子类
        $callModel = get_called_class();
        // 获取数据库表列表
        $this->dbTableList = $this->curd->getTables();
        if (!is_null($tab) AND in_array(strtolower($tab), $this->dbTableList)) {
            $this->dbTable = $tab ;
        }elseif( $callModel !== "Model" AND !empty($callModel) ) {
            $this->dbTable = strtolower(explode("Model",$callModel)[0]);
        }else{
            trigger_error("找不到数据库表",E_USER_ERROR);
        }
    }
    // 数据库链接函数
    private function connect() {
        $curdType = ucfirst($this->curdType);
        $this->curd = new $curdType($this, $this->mysqlInfo['DB_TYPE'], $this->mysqlInfo['HOSTS'], 
            $this->mysqlInfo['DB_NAME'], $this->mysqlInfo['ROOT'], $this->mysqlInfo['PASSWORD']);
    }
    // 字段选择过滤
    public function fields( $field_string ) {
        $fid = $this->curd->mysqlFilter($field_string);
        $this->_sql['fid'] = empty($fid) ? $this->_sql['fid'] : $fid ;
        return $this;
    }
    // 查找行数限制
    public function limit( $offset = 0 ,$rows = 0 ) {
        if( !is_numeric($offset) AND !is_numeric($rows) ) return false;
        if( $offset && $rows ) {
            $this->_sql['lit'] = array( $offset ,$rows ) ;
        }else{
            $this->_sql['lit'] = $offset;
        }
        return $this;
    }
    // 依据排列顺序order by
    public function order( $order_word ) {
        // filter the user inputing 
        $word = $this->curd->mysqlFilter($order_word) ;
        $this->_sql['ord'] = $word;
        return $this;
    }
    // 条件组合查询或字符串查询
    public function where( $condition  ) {
        if ( is_string($condition) ) {
            $this->_sql['wre'] = $this->curd->mysqlFilter($condition) ;
        }
        if ( is_array($condition) ) {
            // 过滤keys 与 values 
            $newCondition = array();
            foreach( $condition as $key => $value ) {
                $newCondition[$this->curd->mysqlFilter($key)] = $this->curd->mysqlFilter($value) ;
            }
            //返回新条件数组
            $this->_sql['wre'] = $newCondition ;
        }
        return $this ;
    }
    // 过滤用户输入数据
    public function data( $data ) {
        if( !is_array($data) ) return false;
        $data = $this->curd->mysqlFilter($data);
        // 返回到sql
        $this->_sql['dat'] = $data;
        return $this;
    }
    // 过滤group by 数据
    public function group( $qstr = null ) {
        if( is_null($qstr) ) return false;
        $this->_sql['gro'] = $this->curd->mysqlFilter($qstr);
        return $this;
    }
    // 过滤having 数据
    public function having( $hstr = null ) {
        if( is_null($hstr) ) return false;
        $this->_sql['hav'] = $this->curd->mysqlFilter($hstr);
        return $this;
    }
    // 是否只执行查询
    public function check ($val = false) 
    {
        $this->dbCheck = $val;
        return $this;
    }
    // 是否开启事务滚动
    public function trans ($trans = false) 
    {
        $this->transaction = $trans;
        return $this;
    }
    // 是否开启预处理
    public function stmt ($statement = false) 
    {
        $this->pretreatment = $statement;
        return $this;
    }
    // 返回sql语句
    public function returnSql ($bool = false) {
        $this->returnSqlStent = $bool;
        return $this;
    }
    // 组合query 语句查询
    public function fullQueryString( $handle ) {
        // 要查询的数据表
        $table = $this->dbTable;
        $dataCondition = $this->_sql['dat'];
        $fieldsCondition = $this->_sql['fid'];
        $conditionString = $this->fullQueryWhere();
        switch ($handle) {
        case "INSERT":
            $returnString = "INSERT INTO $table 
            (".implode(',',array_keys($dataCondition))." ) VALUES ('".implode("','",$dataCondition)."')";
            break;
        case "SELECT": // 查询模式
            $returnString = "SELECT $fieldsCondition FROM $table ";
            break;
        case "UPDATE": // 修改模式
            $returnString = "UPDATE $table SET ";
            foreach($dataCondition as $key => $val) {
                if ($val) 
                {
                    $val = is_numeric($val) ? $val : "'$val'";
                    $setData[] = "$key=$val";
                } else {
                    // 若要使用score = score + 1,数据data数组是['score=score+1' => ''];
                    $setData[] = $key;
                }
            }
            $returnString .= implode(',',$setData)." ";
            break;
        case "DELETE": //删除模式
            $returnString = "DELETE FROM {$table} ";
            break;
            
        }
        $returnString .= $conditionString;
        //die($returnString);
        return $returnString;
    }
    // 整合查询条件字符串
    public function fullQueryWhere () {
        $sqlString = '';
        $condition = $this->_sql;
        $whereCondition = $condition['wre'];
        $gc = $condition['gro'];
        $hc = $condition['hav'];
        $oc = $condition['ord'];
        $lc = $condition['lit'];
        $afterWreSql = $this->afterWhereQuery($gc, $hc, $oc, $lc);
        $queryCommand = "WHERE";
        if(!empty($whereCondition)) {
            if (is_string($whereCondition)) 
            {
                return "$queryCommand $whereCondition $afterWreSql";
            }
            if (is_array($whereCondition))
            {
                // 计算数据长度判断是否为多字段查询
                $whereArrayLen = count($whereCondition);
                if ($whereArrayLen > 1) {
                    $sqlString = $this->multiFields($whereCondition);
                    return "$queryCommand $sqlString $afterWreSql";
                }
                $combineSql = $this->singleFieldCombine($whereCondition);
                $sqlString = "$queryCommand $combineSql $afterWreSql"; 
                return $sqlString;
            }
        }
    } 
    // 组合多字段查询
    public function multiFields ($condition, $rollbackField = null) 
    {
        if (count($condition) == 2)
        {
            $querySymbol = "AND";
        }
        $querySymbol = isset($querySymbol) ? $querySymbol : array_pop($condition);
        while (list($fieldName, $arrayValue) = each($condition))
        {
            if (is_numeric($fieldName))
            {
                if (!is_null($rollbackField))
                {
                    $queryBlock[] = $this->symbolToValue($rollbackField, $arrayValue);
                }
            } else {
                $conditionBlock = $this->singleFieldCombine([$fieldName => $arrayValue]);
                $queryBlock[]  = "($conditionBlock)";
            }
        }
        $sqlWhereStentence = join(" $querySymbol ", $queryBlock);
        return $sqlWhereStentence;
    }
    // 组合Where条件后的查询
    public function afterWhereQuery ($groupCondition, $havingCondition, $orderCondition, $limitCondition) {
        // 组查询GROUP BY
        if( !empty($groupCondition)) {
            $sqlString .= "GROUP BY $groupCondition ";
        }
        if($havingCondition) {
            // having条件与group 组合使用
            list($havingCdiKey ,$havingCdiVal) = each($havingCondition);
            $sqlString .= "HAVING $havingCdiKey = '$havingCdiVal' ";
        }
        // 条件排序
        if(!empty($orderCondition)) {
            $sqlString .= "ORDER BY $orderCondition ";
        }
        // 条件限制
        if(!empty($limitCondition)) {
            $sqlString .= "LIMIT $limitCondition ";
        }
        return $sqlString;
    }
    // 单字段组合条件
    public function singleFieldCombine ($whereCondition) 
    {
        list($checkField, $fieldCondition) = each($whereCondition);
        // 不是数组为一般字段查询
        if (!is_array($fieldCondition))
        {
            $sqlString = is_numeric($fieldCondition) ? $checkField."=".$fieldCondition : $checkField."='$fieldCondition'";
            return $sqlString;
        }
        // 计算字段是否为并列查询
        if (is_array($fieldCondition[0])) {
            $sqlString = $this->multiFields($fieldCondition, $checkField);
            return $sqlString;
        }
        return $this->symbolToValue($checkField, $fieldCondition);
    }
    // 把字段 符号 值连接在一起
    public function symbolToValue ($checkField, $fieldCondition) 
    {
        $querySymbol = strtoupper($fieldCondition[0]);
        $querySymbolValue = $fieldCondition[1];
        // 使用特性字段查询符号检测
        $specialSymbolArray = $this->specialQuerySymbol;
        $integrateCondition = "$checkField $querySymbol ";
        if (in_array($querySymbol, $specialSymbolArray))
        {
            switch ($querySymbol)
            {
                case "IN" :
                case "NOT IN" :
                    if (is_string($querySymbolValue))
                    {
                        $sqlString = $integrateCondition."($querySymbolValue) ";
                    } else {
                        $arrayToString = join("','", $querySymbolValue);
                        $sqlString = $integrateCondition."('$arrayToString') ";
                    }
                break;
                case "BETWEEN" :
                case "NOT BETWEEN" :
                    $querySymbolValueString = str_replace(',', ' AND ', $querySymbolValue);
                    $sqlString = "$integrateCondition $querySymbolValueString ";
                break;
            }
        } else {
            if (is_numeric($querySymbolValue)) {
                $sqlString = $integrateCondition.$querySymbolValue." ";
            } else {
                $sqlString = $integrateCondition."'$querySymbolValue' ";
            }
        }
        return $sqlString;
    }
    public function execute( $id = null ) {
        // 定义返回结果数组
        $result = $this->curd->R($id);
        return $result;
    }
    // 修改表数据
    public function save() {
        // 定义修改query语句
        $result = $this->curd->U();
        return $result;
    }
    // 删除表数据
    public function delete($id = null) {
        // 通过id删除表数据
        $result = $this->curd->D($id);
        return $result;
    } 
    // 添加表数据
    public function add($data = null) {
        //过滤data数据
        $result = $this->curd->C($data);
        return $result;
    }
} 
