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
    private  $_sql = array(
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
    private  $_mysqlinfo = array(
        "HOSTS"    => "" ,// 服务器名称或iP地址
        "ROOT"     => "" ,// 数据库用户名
        "PASSWORD" => "" ,// 数据库用户密码
        "DB_NAME"  => "" , //数据库名
        "DB_TABLE" => "" , // 数据库表
    );

    // 数据链接编码
    public $connectEncoding = null;
    // 数据库表列表
    public $dbTableList = array();
    // 数据库链接柄
    // 方便用户自定义数据库操作
   public  $conn = false;

    // 定义构造函数
    public function __construct($tab = '') {
        // 初始化数据库信息
        $this->_mysqlinfo['HOSTS'] = (SERVER_HOST) ? SERVER_HOST : SERVER_IP ;
        $this->_mysqlinfo['ROOT']  = C('DB_USER');
        $this->_mysqlinfo['PASSWORD'] = C('DB_PASSWORD');
        $this->_mysqlinfo['DB_NAME']  = C('DB_NAME');
        $this->connectEncoding = C('MYSQL_CONNECT_ENCODING');
        // 链接数据库，失败退出进程
        $this->connect();
        // 获取数据库客户端编码
        $clientEncoding = mysql_client_encoding($this->conn);
        if( $clientEncoding !== $this->connectEncoding ) {
            // 自定义用户链接查询字符集编码
            mysql_query("SET NAMES {$this->connectEncoding}",$this->conn);
        }
        $callModel = get_called_class();
        // 获取数据库表列表
        $this->getTables();
        if ($tab AND in_array(strtolower($tab) ,$this->dbTableList) ) {
            $this->_mysqlinfo['DB_TABLE'] = $tab ;
        }elseif( ($callModel !== "Model") AND (!empty($callModel)) ) {
            $this->_mysqlinfo['DB_TABLE'] = strtolower(explode("Model",$callModel)[0]);
        }else{
            var_dump(strtolower(explode("Model",$callModel)[0]));
            trigger_error("找不到数据库表",E_USER_ERROR);
        }

    }

    // 数据库链接函数
    private function connect() {

        $this->conn = mysql_connect( $this->_mysqlinfo['HOSTS'] ,$this->_mysqlinfo['ROOT'] ,$this->_mysqlinfo['PASSWORD']);
        if(!$this->conn) {
            die("can't not connect mysql :".mysql_error()) ;
        }
        mysql_select_db($this->_mysqlinfo['DB_NAME']);
    }
    

    // 字段选择过滤
    public function fields ( $field_string ) {

        $fid = $this->mysql_filter($field_string);
        $this->_sql['fid'] = empty($fid) ? $this->_sql['fid'] : $fid ;
        return $this ;

    }

    // 查找行数限制
    public function limit ( $offset = 0 ,$rows = 0 ) {

        if( !is_numeric($offset) AND !is_numeric($rows) ) return false;
        if( $offset && $rows ) {
            $this->_sql['lit'] = array( $offset ,$rows ) ;
        }else{
            $this->_sql['lit'] = $offset;
        }
        return $this;
    }

    // 依据排列顺序order by
    public function order ( $order_word ) {

        // filter the user inputing 
        $word = $this->mysql_filter($order_word) ;
        $this->_sql['ord'] = $word;
        return $this;
    }

    // 条件组合查询或字符串查询
    public function where ( $condition  ) {

        if ( is_string($condition) ) {
            $this->_sql['wre'] = $this->mysql_filter($condition) ;
        }

        if ( is_array($condition) ) {

            // 过滤keys 与 values 
            $newcondition = array();
            foreach( $condition as $key => $value ) {
                $newcondition[$this->mysql_filter($key)] = $this->mysql_filter($value) ;
            }
            //返回新条件数组
            $this->_sql['wre'] = $newcondition ;
        }
        return $this ;
    }

    // 过滤用户输入数据
    public function data ( $data ) {
        if( !is_array($data) ) return false;
        foreach( $data as $k => $v ){

            // $v 为数组则是多行插入数据
            if ( is_array($v)  ) {
                while( list($field , $value) = each ($v)  ) {
                    $v[$field] = $this->mysql_filter($value) ;
                }

            }else{
                $data[$k] = $this->mysql_filter($v);
            }
        }
         // 返回到sql
        $this->_sql['dat'] = $data;
        return $this;
    }

    // 过滤group by 数据
    public function group( $qstr = null ) {
        if( is_null($qstr) ) return false;
        $this->_sql['gro'] = $this->mysql_filter($qstr);
        return $this;
    }

    // 过滤having 数据
    public function having ($hstr = null) {
        if( is_null($hstr) ) return false;
        $this->_sql['hav'] = $this->mysql_filter($hstr);
        return $this;
    }

    // 对数据库进行读操作
    public function execute ($id = null) {

        // 定义返回结果数组
        $returndata = array() ;
        $result ;
        // 非组合查询id
        if( is_numeric($id)  ) {
            $result = $this->query("SELECT * FROM {$this->_mysqlinfo['DB_TABLE']} WHERE id='{$id}'");
            $returndata[0] = mysql_fetch_assoc($result);
        }elseif(is_null($id)) { 
            //组合查询语句
            $queryString = $this->full_query_string('SELECT');
            $result = $this->query($queryString) ;
            if( !$result ) return false;
            while( $row = mysql_fetch_assoc($result) ) {
                $returndata[] = $row ;
            }
        }
        mysql_free_result($result);
        $this->close();
        // 没有查询结果返回false
        if( empty($returndata) ) {
            return false ;
        }else{
            return $returndata;
        } 
    }

    // 修改表数据
    public function save () {

        // 定义修改query语句
        $updateString = $this->full_query_string('UPDATE') ;
        $result = $this->query($updateString);
        if( $result ) {

            // 如果修改正确返回影响行数
            return mysql_affected_rows();
        }else{
            return false;
        }
        
    }

    // 删除表数据
    public function delete ($id = null) {

        // 通过id删除表数据
        $result ;
        if( is_numeric($id) ) {
            $result = $this->query("DELETE * FROM {$this->_mysqlinfo['DB_TABLE']} WHERE id='{$id}'");
        }else{ 

            //定义删除query语句
            $deleteString = $this->full_query_string('DELETE') ;
            $result = $this->query($deleteString) ;
        }
        if( $result ) {
            return mysql_affected_rows();
        }else{
            return false;
        }

    } 

    // 添加表数据
    public function add ($data = null) {

        //过滤data数据
        $filterData ;
        if( !is_null($data) ) {
            // 数据不为空则过滤数据
            $HEAD        = $this->data($data);
            $filterData  = $HEAD->_sql['dat'];
        }
        $filterData = isset($filterData) ? $filterData : $this->_sql['dat'];
        $queryString = "INSERT INTO {$this->_mysqlinfo['DB_TABLE']} (".implode(',',array_keys($filterData))." ) VALUES ('".implode("','",$filterData)."')";
        $result      = $this->query($queryString) ;
        if( !$result ) return false;
        // 获取刚输入数据的ID
        $insertID    = mysql_insert_id() ;
        if (is_int($insertID) && $insertID ) {
            return $insertID;
        }else{
            return $result;
        }
    }

    // 组合query 语句查询
    public function full_query_string ( $handle ) {

        // 定义返回查询字符串
        $returnString ;
        $table    = $this->_mysqlinfo['DB_TABLE'];
        // 字段数组
        $sql      = $this->_sql;
        switch ($handle) {
        case "SELECT": // 查询模式
            $returnString = "SELECT {$sql['fid']} FROM {$table} ";
            $this->full_query_where( $returnString );
            break;
        case "UPDATE": // 修改模式
            $returnString = "UPDATE {$table} SET ";
            $setData = array();
            foreach($sql['dat'] as $key => $val) {
                $setData[] = $key."='{$val}'";
            }
            $returnString .= implode(',',$setData)." ";
            $this->full_query_where( $returnString );
            break;
        case "DELETE": //删除模式
            $returnString = "DELETE FROM {$table} ";
            $this->full_query_where( $returnString );
            break;
            
        }
        return $returnString;
    }

    // 整合查询条件字符串
    public function full_query_where (&$string) {
        $cond = $this->_sql;
        if(!empty($w = $cond['wre'])) {
            if(is_string($w)) {
                $string .= "WHERE $w ";
            }elseif(is_array($w)) {
                // where(array("id"=>array(array(">",2),array("<",10),"OR")));
                // $fieldArr[0] = "id";
                $fieldArr = array_keys($w);
                /*
                * $valueArr[0] = array(array(">",2),array("<",10),"OR");
                */
                $valueArr = array_values($w);
                $assembleCond  = $valueArr[0];
                $conditionLen  = count($assembleCond);
                switch ($conditionLen) {
                case 1:
                case 2:
                /*
                 * $valueArr[0] = array("=",10);
                 * $valueArr[0] = 10;
                 */
                    if(is_string($assembleCond)) {
                        $string .= "WHERE {$fieldArr[0]}='{$assembleCond}' ";
                    }elseif( is_array($assembleCond) ) {
                        list($key ,$val)  = $assembleCond ;
                        switch ($key = strtoupper($key)) {
                        case "NOT IN" :
                        case "IN" :
                            $string .= "WHERE {$fieldArr[0]} {$key} ('".join("','",$val)."')";
                            break;
                        case "BETWEEN" :
                        case "NOT BETWEEN" :
                            $val = explode(",",$val);
                            $string .= "WHERE {$fieldArr[0]} {$key} ".join(" AND ",$val)." ";
                            break;
                        default :
                            $string .= "WHERE {$fieldArr[0]} {$key} '{$val}' ";
                        }

                    }
                    break;
                default:
                    /*
                     * $valueArr[0] = array(array(),array());
                     * $valueArr[0] = array(array(),array(),'OR');
                     * $range = OR ,AND ,BETWEEN ,IN ,NOT IN ;
                     */
                    $range = $assembleCond[2];
                    $groupContainer = array();
                    for($i=0 ;$i < $conditionLen; $i++) {
                        if( is_string($assembleCond[$i]) ) continue;
                        list($arrayOneKey ,$arrayOneVal) = $assembleCond[$i];
                        switch(strtoupper($arrayOneKey)) {
                        case "NOT IN" :
                        case "IN" :
                            $groupContainer[] = "({$fieldArr[0]} {$arrayOneKey} ('".join("','",$arrayOneVal)."'))";
                            break;
                        case "BETWEEN" :
                        case "NOT BETWEEN" :
                            $explanBet = explode(",",$arrayOneVal);
                            $groupContainer[] = "({$fieldArr[0]} {$arrayOneKey} ".join(" AND ",$explanBet).')';
                            break;
                        default :
                            $groupContainer[] = "({$fieldArr[0]} {$arrayOneKey} "."'{$arrayOneVal}'".")";
                        }
                    }
                    $string .= "WHERE ".join(" $range ",$groupContainer)." ";
                }
            }
        }
        // 组查询GROUP BY
        if( !empty($cond['gro'])) {
            $string = "SELECT ".$cond['fid']." FROM {$this->_mysqlinfo['DB_TABLE']} GROUP BY ".$cond['gro']." ";
            if($h = $cond['hav']) {
                // having条件与group 组合使用
                list($havingCdiKey ,$havingCdiVal) = each($cond['hav']);
                $string .= "HAVING $havingCdiKey {$havingCdiVal[0]} '{$havingCdiVal[1]}' ";
            }else{
                return false;
            }
        }
        // 条件排序
        if(!empty($cond['ord'])) {
            $string .= "ORDER BY ".$cond['ord']." ";
        }
        // 条件限制
        if(!empty($cond['lit'])) {
            if( is_array($cond['lit']) ) {
                $linenu = join("," ,$cond['lit']);
                $string .= "LIMIT ".$linenu." ";
            }else{
                $string .= "LIMIT ".intval($linenu[0])." ";
            }
        }
    } 
    // 获取数据库表列表
    public function getTables () {
        $tables = mysql_query("SHOW TABLES" ,$this->conn);
        while ($row = mysql_fetch_array($tables)) {
            $this->dbTableList[] = $row[0];
        }
        mysql_free_result($tables);
    }

    // 随用户输入数据进行mysql_escape_string过滤
    public function mysql_filter($fd) {
        if( is_string($fd) ) return mysql_real_escape_string($fd);
        if( is_numeric($fd) ) return $fd;
        if( is_array($fd) ) {
            $fd = $this->filterArray($fd);
        }
        return $fd;
    }
    // 遍历过滤数组
    public function filterArray ($data) {
        foreach($data as $key => $val) {
            if( is_string($val)) {
                $data[$key] = mysql_real_escape_string($val);
            }
            if( is_numeric($val) ) {
                $data[$key] = $val;
            }
            if( is_array($val) ) {
                $this->filterArray($val);
            }
        }
        return $data;
    }

    // 执行数据库操作
    public function query ($quer) {
        /*
         *die($quer);
         */
        $results = mysql_query($quer , $this->conn);
        if( !$results ) trigger_error(mysql_error(),E_USER_ERROR);
        return $results;
    }

    //断开数据库链接
    public function close () {

        mysql_close($this->conn);

    }
} 
?>
