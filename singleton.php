<?php
trait singleton{
    private static $instance = NULL;
    // Convert default constructor/functions private
    private function __construct(){}
    private function __clone(){}

    // Creating a single instance for db connection
    public static function getInstance() {
        if(self::$instance == NULL){
            self::$instance = new static();
        }
        return self::$instance;
    }

}
