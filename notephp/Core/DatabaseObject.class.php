<?php
 /*
  * Edited by hebarguan 2016-1-20
  * Email hebarguan@hotmail.com
  * 该类是php为最新的数据库扩展提供支持
  * PDO类是php官网支持的最新的多数据库扩展
  * PDO支持多种数据库的扩展
  * 更多请参考php的官网php.net/pdo
  */

class DatabaseObject
{
    // 模型实例
    private $modeInstance = null;
    // 数据库实例
    public $dbLink = null;
    // 构造函数
    public function __construct(
        Model $mode,
        $dbType,
        $dbHost,
        $dbName,
        $dbRoot,
        $dbPwd
    ){
        $this->modeInstance = $mode;
        $dsn = "$dbType:dbname=$dbName;host=$dbHost";
        // PDO驱动选项
        $pdoDriverOption = array(
            PDO::ATTR_PERSISTENT => $this->modeInstance->dbPersistentLink,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$this->modeInstance->connectEncoding}'",
            PDO::ATTR_ERRMODE   => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->dbLink = new PDO($dsn, $dbRoot, $dbPwd, $pdoDriverOption);
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    } 
    // 数据库读取操作
    public function R($id = null)
    {
        $check = $this->modeInstance->dbCheck;
        if (is_numeric($id)) {
            $stmtSql = "SELECT * FROM {$this->modeInstance->dbTable} WHERE id=$id";
            $stmt = $this->dbLink->prepare($stmtSql);
            if ($stmt->execute()) {
                if ($check) {
                    return $stmt->rowCount();
                } else {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
            } else {
                return false;
            }
        }
        // 组合查询
        $stmtSql = $this->modeInstance->buildQueryString('SELECT');
        $groupCheckStmt = $this->query($stmtSql);
        if ($check) {
            return $groupCheckStmt->rowCount();
        }
        return $groupCheckStmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
    // 数据库写入操作
    public function C($data = null)
    {
        if (!is_null($data)) {
            $this->modeInstance->data($data);
        }
        $queryString = $this->modeInstance->buildQueryString('INSERT');
        $stmt = $this->query($queryString);
        if ($insertId = $this->dbLink->lastInsertId()) {
            return $insertId;
        } else {
            return $stmt->rowCount();
        }
    }
    // 数据库修改操作
    public function U() 
    {
        $stmtSql = $this->modeInstance->buildQueryString('UPDATE');
        $stmtResult = $this->query($stmtSql);
        return $stmtResult->rowCount();
    }
    // 数据库删除数据操作
    public function D()
    {
        $stmtSql = $this->modeInstance->buildQueryString('DELETE');
        $affectedRows = $this->dbLink->exec($stmtSql);
        return $affectedRows;
    }
    // 执行数据库语句
    public function query($sqlSentence) 
    {
        if ($this->modeInstance->transaction) {
            try {
               $this->dbLink->beginTransaction(); 
               if ($this->modeInstance->pretreatment) {
                   $statement = $this->dbLink->prepare($sqlSentence);
                   $statement->execute();
               } else {
                   $statement =  $this->dbLink->query($sqlSentence);
               }
               $this->dbLink->commit();
               return $statement;
            } catch (PDOException $e) {
                $this->dbLink->rollback();
                trigger_error($this->dbLink->errorInfo()[2], E_USER_ERROR);
            }
        }
        $statement = $this->dbLink->query($sqlSentence)
            OR
        trigger_error($this->dbLink->errorInfo()[2], E_USER_ERROR);
        return $statement;
    }
    // 获取数据库表列表
    public function getTables() 
    {
        $sqlSentence = "SHOW TABLES";
        $stmt = $this->query($sqlSentence);
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tableTist[] = $row[0];
        }
        return $tableTist;
    }
    // 随用户输入数据进行转义过滤
    public function mysqlFilter($filterData)
    {
        // 检测是否开启post，get自动过滤
        // 防止出现双重转义
        if (get_magic_quotes_gpc()) return $filterData;
        if (is_string($filterData)) return addslashes($filterData);
        if (is_numeric($filterData)) return $filterData;
        if (is_array($filterData)) {
            $filterData = $this->filterArray($filterData);
        }
        return $filterData;
    }
    // 遍历过滤数组
    public function filterArray($data)
    {
        foreach($data as $key => $val) {
            if (is_string($val)) $data[$key] = addslashes($val);
            if(is_numeric($val)) $data[$key] = $val;
            if(is_array($val)) $this->filterArray($val);
        }
        return $data;
    }
}
