<?php
class Depan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "Hello World. Today is " . date('Y-m-d H:i:s');
    }
}
