<?php

class DoneJobe extends ParsingSite {
    private $url;
    private $pages = [];
    private $editData;
    private $editLink;
    private $dataBody;
    private $editDate;

    private function createUrl(){
        if (count($this->pages) === 0){
            $this->url = $this->getUrl();
        }else{
            $this->url = 'https://gkb81.ru/sovety/page/' . (count($this->pages) + 1) . '/';
            $this->setUrl($this->url);
        }
        $this->setPrameters();
        array_push($this->pages, $this->getUrl());
    }

    protected function createParsing(){
        $this->createUrl();
        while ($this->checkUrl($this->url) === 'OK'){
            for ($i = 0; $i < $this->getLenght(); $i++){
                $this->editData = $this->parseTitle($i);
                $this->editDate = $this->parseData($i);
                $this->editLink = $this->parseLink($i);
                $this->dataBody = $this->parseBody($i);
                $this->putData($this->editData, $this->editLink, $this->dataBody, $this->editDate);

            }
            $this->parsePhoto();

            $this->createUrl();
        }
    }

    public function putSomeData(){
        $this->createParsing();
    }
}