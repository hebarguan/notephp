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
    // 数据库CURD操作类型，默认是PDO
    public $curdType = null;
    // 定义构造函数
    public function __construct( $tab = null ) {
        // 初始化数据库信息
        $this->mysqlInfo['HOSTS']    = C('DB_HOST');
        $this->mysqlInfo['ROOT']     = C('DB_USER');
        $this->mysqlInfo['PASSWORD'] = C('DB_PASSWORD');
        $this->mysqlInfo['DB_NAME']  = C('DB_NAME');
        $this->connectEncoding       = C('MYSQL_CONNECT_ENCODING');
        $this->curdType              = C('CURD_TYPE');
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
            /*
             *var_dump(strtolower(explode("Model",$callModel)[0]));
             */
            trigger_error("找不到数据库表",E_USER_ERROR);
        }
    }
    // 数据库链接函数
    private function connect() {
        $curdType = ucfirst($this->curdType);
        $this->curd = new $curdType($this, $this->mysqlInfo['HOSTS'], 
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
    // 组合query 语句查询
    public function full_query_string( $handle ) {
        // 定义返回查询字符串
        $returnString ;
        $table    = $this->dbTable;
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
    public function full_query_where(&$string) {
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
            $string = "SELECT ".$cond['fid']." FROM {$this->dbTable} GROUP BY ".$cond['gro']." ";
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
