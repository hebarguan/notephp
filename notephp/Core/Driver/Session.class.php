<?php
/*
 *  Editer hebarguan in 2016-1-30
 *  Email hebarguan@hotmail.com
 *  若要使用Session驱动，请在数据库上创建session数据表
 *  session字段表
 *  CREATE TABLE `session` (
 *       `session_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *       `session_expires` int(32) NOT NULL,
 *       `session_data` text COLLATE utf8_unicode_ci,
 *       PRIMARY KEY (`session_id`)
 *       ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 *    );
 *  开启session驱动后session数据将保存在数据上
 *  更多详细的内容请参考http://php.net/session_set_save_handler
 */

class Session implements SessionHandlerInterface
{
    // 数据库链接柄
    private $conn = null;
    // session过期时间
    private $sessionExpires = null;
    // 创建Session数据库链接
    public function open( $savePath, $sessionName ) {
        $this->sessionExpires = C("SESSION_EXPIRE");
        $sessionTable = C("SESSION_TABLE");
        $this->conn   = M($sessionTable, false);
        if ($this->conn) {
            return true;
        } else {
            return false;
        }
    }
    public function close()
    {
        // Session关闭前的最后操作
        return true;
    }
    // 读取session数据
    public function read($session_id)
    {
        $now = time();
        $searchData = array(
            "session_id" => $session_id,
            "session_expires" => array(">", $now)
        );
        // 查找过期session_id
        $expiresData = array("session_expires" => array("<", $now));
        $getExpiresData = $this->conn->fields("session_id")->where($expiresData)->execute();
        if ($getExpiresData) {
            // 批量删除过期数据
            foreach ($getExpiresData as $key => $sess) {
                $this->gc($sess['session_id']);
            }
        } 
        $getSessionData = $this->conn->fields("session_data")->where($searchData)->execute();
        if ($getSessionData) {
            return $getSessionData[0]["session_data"];
        } else {
            return false;
        }
    }
    // 写入Session数据到数据库
    public function write($sessionId, $sessionData)
    {
        $sessionExpires = time() + $this->sessionExpires;
        if (empty($sessionData)) return false;
        $sessionDataArray = array(
            "session_id" => trim($sessionId), 
            "session_expires" => $sessionExpires, 
            "session_data" => $sessionData
        );
        // 检测是否存在session_id，存在则修改
        $checked = $this->conn->where(["session_id" => $sessionId])->execute();
        if ($checked) {
            $update = $this->conn->data($sessionDataArray)->save();
            if ($update) {
                return true;
            } else {
                return false;
            }
        } else {
            $result = $this->conn->data($sessionDataArray)->add();
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }
    // 删除session数据
    public function destroy $session_id)
    {
        $deleteResult = $this->conn->where(['session_id' => $session_id])->delete();
        if ($deleteResult) {
            return true;
        } else {
            return false;
        }
    }
    // 回收检测过期数据
    public function gc ($lifetime)
    {
        $gcResult = $this->conn->where(array(
            "session_expires" => array("<", time())
        ))->delete();
        if ($gcResult) {
            return true;
        } else {
            return false;
        }
    }
}

