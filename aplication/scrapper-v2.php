<?php
class Scrapper{
 
    private $proxies = array(
       "189.89.227.117:3128",
        "45.32.52.78:80",
        "105.211.254.16:80",
        "200.46.94.194:3128",
        "119.15.155.210:8080",
        "112.78.184.222:8080",
        "122.248.9.101:8080",
        "46.48.133.252:3128",
        "62.201.238.253:8080",
        "105.112.6.34:8080",
        "91.221.233.82:8080",
        "183.91.33.77:80",
        "118.96.132.131:8080"
        );
    public $results;
    public $status_code;
    public $error;
    public $errorno;
    public function __construct(){
        $this->ch = curl_init();
       
        //curl_setopt($this->ch, CURLOPT_PROXY, $this->setProxy());
        curl_setopt($this->ch, CURLOPT_VERBOSE, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36');
       
        $this->temp = tempnam("/tmp", "chatmotor");
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true );
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->temp );
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->temp );
    }
    public function fetch($url){
        //curl_setopt($this->ch, CURLOPT_PROXY, $this->setProxy());
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_REFERER, $url);
        $this->results = curl_exec($this->ch);
        $this->status_code = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        $this->error = curl_error($this->ch);
        $this->errorno = curl_errno($this->ch);
    }
    private function setProxy(){
        $this->proxy = $this->proxies[rand(0,count($this->proxies)-1)];
        $list = explode(":", $this->proxy);
        if( !@fsockopen($list[0],$list[1],$errno, $errstr,(float)0.5) ){
            $this->setProxy();
        }else{
            return $this->proxy;
        }
    }
    public function getProxy(){
        return $this->proxy;
    }
    public function close(){
        if(is_resource($this->ch))
            curl_close($this->ch);
        if(is_file($this->temp))
            unlink($this->temp);
    }
}