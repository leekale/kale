<?php
/**
 * Created by PhpStorm.
 * User: JJBOOM
 * Date: 2016/7/27
 * Time: 14:05
 */

class DB {
    private static $_instance;

    private function __construct(){}

    private function __clone(){}

    public static  function getInstance(){
        if(!(self::$_instance instanceof self)){
//        if(is_null(self::$_instance)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    public function getName($name){
        echo $name;
    }
}

$a=DB::getInstance();
$a->getName('aaa');

$b=DB::getInstance();
$b->getName('asdf');