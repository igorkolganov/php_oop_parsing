<?php

class ParsingSite extends ConnectDB {
    private $varData;
    private $url;
    private $data;
    private $linksPhoto = [];
    private $dataToReplace;
    private $dataWithReplace;
    private $dateToReplace;
    private $editLink;


    public function __construct($url){
        $this->url = $url;
        $this->dataToReplace = ['<h3>', '</h3>'];
        $this->dataWithReplace = ['', ''];
        $this->dateToReplace = ['<span class="dt">', '</span>'];
    }

    protected function setUrl($url){
        $this->url = $url;
    }

    protected function getUrl(){
        return $this->url;
    }

    protected function setPrameters(){
        $this->varData = curl_init();
        curl_setopt($this->varData, CURLOPT_URL, $this->url);
        curl_setopt($this->varData, CURLOPT_RETURNTRANSFER, true);
        $this->data = curl_exec($this->varData);
    }

    protected function getLenght(){
        preg_match_all('#<h3>(.+?)</h3>#is' ,$this->data, $matches);
        return count($matches[0]);
    }

    protected function checkUrl($url){
        $head = @get_headers($url);
        return strpos($head[0], '200') ? 'OK' : '';
    }

    protected function parseTitle($k){
        preg_match_all('#<h3>(.+?)</h3>#is' ,$this->data, $matches);
        return $editData = str_replace($this->dataToReplace, $this->dataWithReplace, $matches[0][$k]);
    }

    protected function parseData($k){
        preg_match_all('#<span class="dt">(.+?)</span>#is' ,$this->data, $matches_date);
        return $editDate = str_replace($this->dateToReplace, $this->dataWithReplace, $matches_date[0][$k]);
    }

    protected function parseBody($k){
        $varData = curl_init();
        curl_setopt($varData, CURLOPT_URL, $this->editLink);
        curl_setopt($varData, CURLOPT_RETURNTRANSFER, true);
        $dataStrLink = curl_exec($varData);
        if (strpos($this->editLink, 'class="wpb_wrapper"')){
            preg_match_all('#<div class="wpb_wrapper">(.+?)</div>#is' ,$dataStrLink, $matches_body);
        }else{
            preg_match_all('#<div class="text text-page">(.+?)</div>#is' ,$dataStrLink, $matches_body);
        }
        return $dataBody = $matches_body[0][0];
    }

    protected function parseLink($k){
        preg_match_all('#<li class="ltx-icon-date">(.+?)</li>#is' ,$this->data, $matches_date_link);
        $xml = simplexml_load_string($matches_date_link[0][$k]);
        $list = $xml->xpath("//@href");
        $preparedUrls = array();
        foreach ($list as $item) {
            $item = parse_url($item);
            $preparedUrls[] = $item['scheme'] . '://' . $item['host'] . $item['path'];
        }
        return $this->editLink = strpos($preparedUrls[0], 'gkb81.ru/sovety/') ? $preparedUrls[0] : '';
    }

    protected function parsePhoto(){
        preg_match_all('/src="([^"]*)"/i' ,$this->data, $matches_date_photo);
        for ($i = 0; $i < count($matches_date_photo[0]); $i++){
            $dataL = strpos($matches_date_photo[0][$i] , 'https://gkb81.ru/wp-content/uploads') ? $matches_date_photo[0][$i] : '';
            $dataL = substr($dataL, 5);
            $dataL = substr($dataL, 0, -1);
            array_push($this->linksPhoto, $dataL) ;
        }
        for($j = 4; $j < count($this->linksPhoto) - 7; $j++){
            $this->downloadPhoto($this->linksPhoto[$j]);
        }
        $this->linksPhoto = [];
    }

    protected function downloadPhoto($link)
    {
        $upload_path = "img/download/";
        $user_filename = $link;
        $userfile_basename = pathinfo($user_filename, PATHINFO_FILENAME);
        $userfile_extension = pathinfo($user_filename, PATHINFO_EXTENSION);
        $server_filename = $userfile_basename . "." . $userfile_extension;
        $server_filepath = $upload_path . $server_filename;

        $i = 0;
        while (file_exists($server_filepath)) {
            $ms = explode(' ', microtime());
            $i++;
            $server_filepath = $upload_path . $ms[1] . "($i)" . "." . $userfile_extension;
        }
        if (copy($link, $server_filepath)) {
            $response['status'] = 'ok';
        }
    }

    protected function putData($editData, $editLink, $dataBody, $editDate){
        $this->insertData($editData, $editLink, $dataBody, $editDate);
    }
}