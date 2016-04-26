<?php
namespace sergey144010\Socket\Stream\Http;

use sergey144010\Socket\Stream\Response;
use sergey144010\Socket\Stream\Http\HttpOption;

class HttpClient
{
    public $request;
    public $response;

    /**
     * @var HttpOption $http
     */
    public $http;
    public $httpOption;
    public $target;
    public $urlParse;
    public $mode;

    public $header;
    public $cookie;
    public $content;

    /*
     * @set (array) $this->request
     * @return $this
     */
    public function request()
    {
        $this->request = ['http'=>$this->httpOption];
        return $this;
    }

    /*
     * @set $this->response
     * @return $this
     */
    public function response()
    {
        $response = new Response();
        $response->fopen($this->target, $this->mode, $this->request);
        $this->response = $response->getResponse();
        return $this;
    }

    /*
    * ѕровер€ем есть ответ после $this->send или нет
    *
    * @return true || false
    */
    public function responseExist()
    {
        if($this->response){
            return true;
        }else{
            return false;
        }
    }

    public function send()
    {
        if($this->http->method->isDataExist()){
            $this->http->content($this->http->method->getData());
        };
        $this->httpOption = $this->http->getOption();

        $this->request();
        $this->response();
        return $this;
    }

    public function open($target, $mode="rb")
    {
        $this->urlParse = parse_url($target);
        $this->http = new HttpOption();
        $this->target = $target;
        $this->mode = $mode;
        return $this;
    }

    public function getHeader()
    {
        if($this->response){
            $response = stream_get_meta_data($this->response);
            #var_dump($response);
            foreach ($response['wrapper_data'] as $header) {
                $headerRaw = explode(":", $header, 2);
                if (isset($headerRaw[1])) {
                    $headerName = trim($headerRaw[0]);
                    $headerValue = trim($headerRaw[1]);
                    /*
                     * «десь не учтено, что могут быть одинаковые названи€
                     * Set-Cookie например.
                     * »справл€ем только дл€ Set-Cookie
                     */
                    if($headerName == 'Set-Cookie'){
                        if(isset($headerArray['Set-Cookie'])){
                            $headerName = 'Set-Cookie-Ssl';
                        };
                    };
                    $headerArray[$headerName] = $headerValue;

                }else{
                    $headerArray['Status'] = $headerRaw[0];
                };
            };
            if(isset($headerArray)){
                $this->header = $headerArray;
            };
        };
        return $this;
    }

    public function getCookie()
    {
        if($this->header){
            $this->cookie = $this->header['Set-Cookie'];
        };
        return $this;
    }

    public function getContent()
    {
        if($this->response) {
            $this->content = stream_get_contents($this->response);
        };
        return $this;
    }
}
