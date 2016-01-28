<?php
 /*
  * Edited by hebarguan 2016-1-20
  * Email hebarguan@hotmail.com
  * 这个模型只能运行在mysql数据库内
  * 暂时不支持用在其它数据库上
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
        "HOSTS"    => $Userconf["HOSTS"] ,// 服务器名称或iP地址
        "ROOT"     => $Userconf["ROOT"] ,// 数据库用户名
        "PASSWORD" => $Userconf["password"] ,// 数据库用户密码
        "DB_NAME"  => $Userconf["DB_NAME"] , //数据库名
        "DB_TABLE" => $Userconf['DB_TABLE'] ,//数据库表
    );

    // 定义构造函数
    public function __construct() {

        // 链接数据库，失败退出进程
        $this->connect();

    }

    // 数据库链接函数
    private function connect() {

        $this->conn = mysql_connect( $this->_mysqlinfo['HOSTS'] , $this->_mysqlinfo['ROOT'] , $this->_mysqlinfo['PASSWORD'] )
            OR die("can't not connect mysql :".mysql_error()) ;
    }
    
    // 数据库链接柄
    $conn = false ;

    // 字段选择过滤
    public function fields ( $field_string ) {

        $fid = $this->mysql_filter($field_string);
        $this->_sql['fid'] = empty($fid) ? $this->_sql['fid'] : $fid ;
        return $this ;

    }

    // 查找行数限制
    public function limit ( $offset ,$rows ) {

        list( $set , $row  ) = array( $this->filter($offset) , $this->filter($rows ) );
        if( isset($set) && isset($row)  ) {
            $this->_sql['num'] => array( $set ,$row ) ;
        }

        if( isset($set) && !isset($row)  ) {
            $this->_sql['num'] = $set ;
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
        foreach ( $data as $k => $v ){

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
    public function gronp( $qstr ) {
        $this->_sql['gro'] = $this->mysql_filter($qstr);
        return $this;
    }

    // 过滤having 数据
    public function having ($hstr) {
        $this->_sql['hav'] = $this->mysql_filter($hstr);
        return $this;
    }

    // 对数据库进行读操作
    public function execute ($id) {

        // 定义返回结果数组
        $returndata = array() ;
        $result ;
        // 非组合查询id
        if( is_numeric($id)  ) {
            $result = $this->query("SELECT * FROM {$this->_mysqlinfo['DB_TABLE']} WHERE id='{$id}'");
            $returndata[0] = mysql_fetch_assoc($result);
        } else (empty($id)) { 
                   
            //组合查询语句
            $queryString = $this->full_query_string('SELECT');
            $result = $this->query($queryString) ;
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
        $return = $this->query($updateString);
        if( $result ) {

            // 如果修改正确返回影响行数
            return mysql_affected_rows($row);
        }else{
            return false;
        }
        
    }

    // 删除表数据
    public function delete ($id) {

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
            return mysql_affected_rows($result);
        }else{
            return false;
        }

    } 

    // 添加表数据
    public function add ($data) {

        //过滤data数据
        $filterData ;
        $HEAD        = $this->data($data);
        $filterData  = $HEAD->_sql['data'];
        $queryString = "INSERT INTO {$this->_mysqlinfo['DB_TABLE']} (".implode(',',$filterData)." ) VALUES (".implode(',',$filterData).")";
        $result      = $this->query($queryString) ;
        // 获取刚输入数据的ID
        $insertID    = mysql_insert_id($result) ;
        if is_int($insertID) && $insertID ) {
            return $insertID;
        }else{
            return false;
        }
    }

    // 组合query 语句查询
    public function full_query_string( $handle ) {

        // 定义返回查询字符串
        $returnString ;
        $table    = $this->_mysqlinfo['DB_TABLE'];
        // 字段数组
        $sql      = $this->_sql;
        switch ($handle) {
        case "SELECT": // 查询模式
            $returnString = "SELECT {$sql['fid']} FORM {$table} ";
            $this->full_query_where( $returnString );
            break;
        case "UPDATE": // 修改模式
            $returnString = "UPDATE {$table} SET ";
            $setData[] = array();
            foreach($sql['dat'] as $key => $val) {
                $setData[] = $key."='{$val}'";
            }
            $returnString .= implode(',',$setData)." ";
            $this->full_query_where( $returnString );
            break;
        case "DELETE": //删除模式
            $returnString = "DELETE * FROM {$table} ";
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
                $string .= "WHERE ".$w;
            }elseif(is_array($w)) {
                $fieldArr = array_keys($w);
                $valueArr = array_values($w);
                $len  = count($w) ;
                switch ($len) {
                case 1:
                    if(is_string($valueArr[0])) {
                        $string .= "WHERE {$fieldArr[0]}='{$valueArr[0]}' ";
                    }elseif( is_array($valueArr[0]) ) {
                        
                        $string .= "WHERE {$fieldArr[0]} {$valueArr[0]} '{$valueArr[1]}' ";
                    }
                    break;
                default:
                    $ao = $len > 2 ? "OR" : "AND";
                    if(is_string($valueArr[0]) ) {
                        $string .= "WHERE ({$fieldArr[0]}='{$valueArr[0]}') {$ao} ({$fieldArr[1]}='{$valueArr[1]}') ";
                    }elseif(is_array($valueArr[0])) {
                        $string .= "WHERE  ({$fieldArr[0]} {$valueArr[0][0]} '{$valueArr[0][1]}') {$ao} ({$fieldArr[1]} {$valueArr[1][0]} '{$valueArr[1][1]}') ";
                    }
                }
            }
        }
        // 组查询GROUP BY
        if( !empty($cond['gro'])) {
            $string = "SELECT ".$cond['fid']." GROUP BY ".$cond['gro']." ";
            if($h = $cond['hav']) {
                $string .= $h ;
            }
        }
        if(!empty($cond['ord'])) {
            $string .= "ORDER BY "$cond['ord']." ";
        }
        if(!empty($cond['lit'])) {
            $linenu = explode("," , $cond['lit']);;
            if(count($linenu) >1) {
                $string .= "LIMIT ".intval($linenu[0])." ,".intval($linenu[1]);
            }else{
                $string .= "LIMIT ".intval($linenu[0]);
            }
        }
    } 

    // 随用户输入数据进行mysql_escape_string过滤
    public function mysql_filter($filsr) {

        $s =  mysql_escape_string($filsr);
        return $s;
    }

    // 执行数据库操作
    public function query ($quer) {

        $results = mysql_query($quer , $this->conn);
        return $results;
    }

    //断开数据库链接
    public function close () {

        mysql_close($this->conn);

    }



} 

?>
