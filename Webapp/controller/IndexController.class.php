<?php 
class IndexController extends Controller {
    public function index () {
        $m = M('test',false);
        /*
         *$datq = $m->fields("name")->where("id=1")->execute();
         */
        $datq = $m->execute(1);
        var_dump($datq);
        var_dump($GLOBALS['PROJECT_REQUEST_MODULE']);
        $data = array("name"=>"HebarGuan" ,"hello"=> "Hello World !");
        $this->assign($data);
        $this->display();
    }
} 
?>
