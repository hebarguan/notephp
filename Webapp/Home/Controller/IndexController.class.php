<?php 
class IndexController extends Controller { 
    public function index () {
        
        $strSize = strlen("bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
        $Request = "hi sala i am hebar";
        $AmazonSecretKey = "i am secert key";
        $text = base64_encode(hash_hmac('sha256', $Request, $AmazonSecretKey, true));
        $this->display();
    } 
    public function test () {

        /*
         *$addArray = ['name' => 'hebarguan', 'department' => "it"];
         *$getArray = ['name', 'department'];
         *var_dump(Cache('email'));
         *var_dump(Cache()->getAllKeys());
         */
        /*
         *$cache = new Memcached();
         *$cache->addServer('localhost',11211);
         *$cache->add('name', "hebar");
         *$cache->add('name', "hebarguan");
         *var_dump($cache->get("name"));
         */
        //$d = Cache::init();
        //$cacheDataArr = array("str_name" => "hebarguan", "list_members" => "li");
        //$s = Cache::set($cacheDataArr);
        //var_dump(Cache::set(['str_name', 'list_members']));
        //header("Content-Type:text/html;charset=utf-8");
        /*
         *$encode = SysCrypt("关怀海", "that is the");
         *echo $encode;
         *$decode = SysDecrypt($encode, "that is the");
         *echo "<br/>";
         *echo $decode;
         */
        /*
         *$redisDB = new RedisStorage();
         *$redisHandle = $redisDB->redisHandle;
         *var_dump($redisHandle->keys('*'));
         */
        //$result = RedisStorage::set(['str_name' => "hebarguan", "list_members" => "li zhou"]);
        //$result = RedisStorage::get('list_members', 0, 2);
        /*
         *var_dump($result);
         *var_dump($redisDB->keys('*'));
         */
        //$set = Cache(array('name' => 'hebar', 'password' => '123'), 10);
        $redis = new RedisStorage();
        $names = array('hebar', 'zhou', 'li', 'xue', 23, 1, 24);
        foreach ($names as $val) {
            $redis->set('set_names', $val);
        }
        $get = $redis->get('set_names', 2, 6);
        var_dump($get);
    }
    public function dout() {
        $m = M("employee",false);
        /*
         *$d = $m->data(['name'=>"关怀海",
         *'sex'=>1,'salary'=>12000,'mailbox'=>'1197726455@qq.com',
         *'on_duty'=>'20160420','department'=>'hr'])->add();
         */
        //$d = $m->execute(2);
        //$d = $m->where(['name' => ['IN',['关怀海','唐马儒']]])->delete();
        //$d = $m->where(['name'=>"唐马儒"])->check(true)->execute();
        //$d = $m->data(['salary=salary+1200' => ''])->where(['name'=>'关怀海'])->returnSql('save');
        //$d = $m->fields('COUNT(*) AS members')->group('sex')->having(array('sex'=>'F'))->execute();
        //$d = $m->trans(true)->stmt(true)->where(array('name'=>'关怀海', 'salary' => array('>',5000)))->execute();
        //$d = $m->where(['id' => ['BETWEEN', '2,4']])->returnSql();
        //$m->dbTable = "mysql.user";// 为跨数据库操作提供支持
        /*
         *$databaseLink = $m->curd->dbLink; // 为手动sql语句操作提供支持
         *$stmt = $databaseLink->prepare('SELECT * FROM employee WHERE name=?');
         *$name = "关怀海";
         *$stmt->bindParam(1, $name, PDO::PARAM_STR);
         *$stmt->execute();
         *$d = $stmt->fetch(PDO::FETCH_ASSOC);
         */
        //$d = $m->where(['name' => ['LIKE', "%\e\n'\%"]])->returnSql("execute");
        //$d = $m->where(['name' => ['REGEXP', 'g$']])->execute();
        /*
         *$d = $m->where([
         *    'id'  => [['<',3], ['>=',10], 'OR'],
         *    'name'=> [['REGEXP', 'g$'], ['LIKE', '%en%'], 'AND'],
         *    'AND'
         *])->execute();
         */
        //$d = $m->where(['salary' => ['>', 1000]])->having(array('name' => '关怀海'))->execute();
        /*
         *$s = new Redis();
         *$s->connect('127.0.0.1',6379);
         *$arr = [1,49,3,21,23];
         */
        /*
         *for($i = 0; $i<count($arr); $i++) {
         *$s->lpush('name', $arr[$i]);
         *}
         */
        //$s->set("name", "hebarguan");
        //$s->expireAt('name', time()+20);
        /*
         *$s->set('members', 1);
         *$s->incr('members');
         *var_dump($s->get('members'));
         */
        //$d = $s->ttl("name");
        //$data = array('salary' => 6000);
        //$d = $m->where(array('name' => 'li李'))->execute();
        //$d = $m->data(array('salary' => 5000))->where('id=5')->returnSql('save');
        $mysqlQuery = $m->curd->dbLink;
        $query = $mysqlQuery->query("SELECT 1 FROM mailserver.virtual_domains WHERE name='calhost.example.com'");
        var_dump($query->fetch_assoc());
    }
    public function dp()
    {
        $this->caching = true;
        $this->cacheLifeTime = 60;
        $this->assign(array('start' => 1, 'end' => 9));
        $this->display();
    }
    public function net()
    {
        //$this->redirect('/index/test', '跳转中...', 10);
        //echo "ok";
        $test = explode(".", "Home.Smarty.Samrty.inc", 3);
        if (file_exists("./Notephp")) echo "ooo";
        var_dump($test);
    }
    public function count() 
    {
        var_dump($_GET);
    }
}
