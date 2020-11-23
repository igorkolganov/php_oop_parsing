<?php

class ConnectDB{
    private $host = '127.0.0.1';
    private $user = 'mysql';
    private $password = 'mysql';
    private $nameDB = 'test_base';
    private $link;
    private $sql;

    protected function connect(){
        $this->link = mysqli_connect($this->host, $this->user, $this->password, $this->nameDB);
        if (mysqli_connect_errno()) {
            echo 'Error connection to DB (' . mysqli_connect_errno() . ')' . mysqli_connect_error();
            exit();
        }
        mysqli_set_charset($this->link, 'utf8');
    }

    protected function insertData($editData, $editLink, $dataBody, $editDate){
        $this->connect();
        $this->sql = "INSERT INTO parsing_one (title, link, body, dated) VALUES ('$editData', '$editLink', '$dataBody', '$editDate')";
        mysqli_query($this->link, $this->sql);
    }
}
$connect = new ConnectDB();