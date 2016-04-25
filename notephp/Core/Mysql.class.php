<?php
 /*
  * Edited by hebarguan 2016-1-20
  * Email hebarguan@hotmail.com
  * 此类为mysql数据库源生加强版的API提供支持,即MYSQLI
  * 此外源生的MYSQL_CONNECT将在未来的php版本中移除
  * 更多请参考php的官网http://php.net/manual/zh/book.mysql.php
  * 暂时不支持用在其它数据库上
  */
class Mysql {
    // 定义模型的实例
    private $modeInstance = null;
    // 数据库链接柄
    public $dbLink = false;
    // 定义构造函数
    public function __construct(Model $mode, $dbType, $dbHost, $dbName, $dbRoot, $dbPwd) {
        $this->modeInstance = $mode;
        $this->dbLink = new mysqli($dbHost, $dbRoot, $dbPwd, $dbName) 
            OR trigger_error("数据库链接错误".mysqli_error(), E_USER_ERROR);
        // 获取数据库客户端编码
        $clientEncoding = $this->dbLink->character_set_name();
        if( $clientEncoding !== $this->modeInstance->connectEncoding ) {
            // 自定义用户链接查询字符集编码
            mysqli_query($this->dbLink, "SET NAMES {$this->modeInstance->connectEncoding}");
        }
    }
    // 数据库创建CREATE
    public function C( $data = null ) {
        if( !is_null($data) ) {
            // 数据不为空则过滤数据
            $this->modeInstance->data($data);
        }
        $sqlStentence = $this->modeInstance->fullQueryString('INSERT');
        if ($this->modeInstance->returnSqlStent) {
            return $sqlStentence;
        }
        $result = $this->query($sqlStentence) ;
        if( !$result ) return false;
        // 获取刚输入数据的ID
        $insertID    = $this->dbLink->insert_id;
        if( is_int($insertID) AND $insertID ) {
            return $insertID;
        }else{
            return $result;
        }
    }
    // 数据库修改UPDATE
    public function U() {
        $updateString = $this->modeInstance->fullQueryString('UPDATE') ;
        if ($this->modeInstance->returnSqlStent) {
            return $updateString;
        }
        $result = $this->query($updateString);
        if( $result ) {
            // 如果修改正确返回影响行数
            return $result->num_rows;
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
            $sqlSentence = "SELECT * FROM {$this->modeInstance->dbTable} WHERE id={$id}";
            if ($this->modeInstance->returnSqlStent) {
                return $sqlSentence;
            }
            $result = $this->query($sqlSentence);
            if ($this->modeInstance->dbCheck) return $result->num_rows;
            $returndata[0] = $result->fetch_array(MYSQLI_ASSOC);
        }elseif(is_null($id)) { 
            //组合查询语句
            $queryString = $this->modeInstance->fullQueryString('SELECT');
            if ($this->modeInstance->returnSqlStent) {
                return $queryString;
            }
            $result = $this->query($queryString) ;
            if( !$result ) return false;
            if ($this->modeInstance->dbCheck) return $result->num_rows;
            while( $row = $result->fetch_assoc() ) {
                $returndata[] = $row ;
            }
        }
        mysqli_free_result($result);
        // 没有查询结果返回false
        if( empty($returndata) ) {
            return false ;
        }else{
            return $returndata;
        } 
    }
    // 数据库删除DELETE
    public function D( $id = null ) {
        if( is_numeric($id) ) {
            $result = $this->query("DELETE * FROM {$this->modeInstance->dbTable} WHERE id={$id}");
        }else{ 
            //定义删除query语句
            $deleteString = $this->modeInstance->fullQueryString('DELETE') ;
            if ($this->modeInstance->returnSqlStent) {
                return $deleteString;
            }
            $result = $this->query($deleteString) ;
        }
        if( $result ) {
            return $result->num_rows;
        }else{
            return false;
        }
    }
    // 随用户输入数据进行mysql_escape_string过滤
    public function mysqlFilter( $filterData ) {
        if( is_string($filterData) ) return $this->dbLink->real_escape_string($filterData);
        if( is_numeric($filterData) ) return $filterData;
        if( is_array($filterData) ) {
            $filterData = $this->filterArray($filterData);
        }
        return $filterData;
    }
    // 遍历过滤数组
    public function filterArray( $data ) {
        foreach($data as $key => $val) {
            if( is_string($val)) {
                $data[$key] = $this->dbLink->real_escape_string($val);
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
    public function query( $quer ) {
        /*
         *die($quer);
         */
        $results = mysqli_query($this->dbLink, $quer);
        if( !$results ) trigger_error(mysqli_error(),E_USER_ERROR);
        return $results;
    }
    // 获取数据库表列表
    public function getTables() {
        $tables = $this->dbLink->query("SHOW TABLES") OR trigger_error("error:".mysqli_error(), E_USER_ERROR);
        while( $row = $tables->fetch_array()) {
            $tableData[] = $row[0];
        }
        mysqli_free_result($tables);
        return $tableData;
    }
}
