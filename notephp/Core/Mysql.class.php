<?php
 /*
  * Edited by hebarguan 2016-1-20
  * Email hebarguan@hotmail.com
  * Mysql类支持mysql_connect()链接，在以后的php版本将移除
  * 更多请参考php的官网php.net/mysql_connect
  * 暂时不支持用在其它数据库上
  */
class Mysql {
    // 定义模型的实例
    private $modeInstance = null;
    // 数据库链接柄
    public $dbLink = false;
    // 定义构造函数
    public function __construct(Model $mode, $dbHost, $dbName, $dbRoot, $dbPwd) {
        $this->modeInstance = $mode;
        $this->dbLink = mysql_connect($dbHost, $dbRoot, $dbPwd) 
            OR trigger_error("数据库链接错误".mysql_error(), E_USER_ERROR);
        mysql_select_db($dbName) OR die("can't not select database $dbName".mysql_error());
        // 获取数据库客户端编码
        $clientEncoding = mysql_client_encoding($this->dbLink);
        if( $clientEncoding !== $this->modeInstance->connectEncoding ) {
            // 自定义用户链接查询字符集编码
            mysql_query("SET NAMES {$this->modeInstance->connectEncoding}",$this->dbLink);
        }
    }
    // 数据库创建CREATE
    public function C( $data = null ) {
        $filterData ;
        if( !is_null($data) ) {
            // 数据不为空则过滤数据
            $HEAD        = $this->modeInstance->data($data);
            $filterData  = $HEAD->_sql['dat'];
        }
        $filterData = isset($filterData) ? $filterData : $this->modeInstance->_sql['dat'];
        if( is_string($filterData) ) return false;
        $queryString = "INSERT INTO {$this->modeInstance->dbTable} 
            (".implode(',',array_keys($filterData))." ) VALUES ('".implode("','",$filterData)."')";
        $result = $this->query($queryString) ;
        if( !$result ) return false;
        // 获取刚输入数据的ID
        $insertID    = mysql_insert_id() ;
        if( is_int($insertID) && $insertID ) {
            return $insertID;
        }else{
            return $result;
        }
    }
    // 数据库修改UPDATE
    public function U() {
        $updateString = $this->modeInstance->full_query_string('UPDATE') ;
        $result = $this->query($updateString);
        if( $result ) {
            // 如果修改正确返回影响行数
            return mysql_affected_rows();
        }else{
            return false;
        }
    }
    // 数据库读取READ
    public function R( $id = null ) {
        $returndata = array() ;
        $result = null;
        // 非组合查询id
        if( is_numeric($id)  ) {
            $result = $this->query("SELECT * FROM {$this->modeInstance->dbTable} WHERE id={$id}");
            $returndata[0] = mysql_fetch_assoc($result);
        }elseif(is_null($id)) { 
            //组合查询语句
            $queryString = $this->modeInstance->full_query_string('SELECT');
            $result = $this->query($queryString) ;
            if( !$result ) return false;
            while( $row = mysql_fetch_assoc($result) ) {
                $returndata[] = $row ;
            }
        }
        mysql_free_result($result);
        // 没有查询结果返回false
        if( empty($returndata) ) {
            return false ;
        }else{
            return $returndata;
        } 
    }
    // 数据库删除DELETE
    public function D( $id = null ) {
        $result ;
        if( is_numeric($id) ) {
            $result = $this->query("DELETE * FROM {$this->modeInstance->dbTable} WHERE id={$id}");
        }else{ 
            //定义删除query语句
            $deleteString = $this->modeInstance->full_query_string('DELETE') ;
            $result = $this->query($deleteString) ;
        }
        if( $result ) {
            return mysql_affected_rows();
        }else{
            return false;
        }
    }
    // 随用户输入数据进行mysql_escape_string过滤
    public function mysqlFilter($fd) {
        if( is_string($fd) ) return mysql_real_escape_string($fd);
        if( is_numeric($fd) ) return $fd;
        if( is_array($fd) ) {
            $fd = $this->filterArray($fd);
        }
        return $fd;
    }
    // 遍历过滤数组
    public function filterArray($data) {
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
    public function query($quer) {
        /*
         *die($quer);
         */
        $results = mysql_query($quer, $this->dbLink);
        if( !$results ) trigger_error(mysql_error(),E_USER_ERROR);
        return $results;
    }
    // 获取数据库表列表
    public function getTables() {
        $tables = mysql_query("SHOW TABLES" ,$this->dbLink) OR trigger_error("error:".mysql_error(), E_USER_ERROR);
        while ($row = mysql_fetch_array($tables)) {
            $tableData[] = $row[0];
        }
        mysql_free_result($tables);
        return $tableData;
    }
}
