<?php

namespace sergey144010\Socket\Stream
{

    class Response
    {
        protected $response;
        #protected $request;


        /*
         *
         * http://php.net/manual/ru/function.fopen.php
         * http://php.net/manual/ru/function.stream-context-create.php
         *
         * This type $this->request example:
         *
           $this->request = array(
                                   'http'=>array(
                                            'method'=>"GET",
                                            'header'=>
                                                    "Accept-language: en\r\n" .
                                                    "Cookie: foo=bar\r\n"
                                            )
                            );
         *
         * @set $this->response = resource (stream_contents) || false;
         *
         * @return $this;
         */
        public function fopen($target, $mode, $request, $use_include_path=false)
        {
            $context = stream_context_create($request);
            #$fp = fopen($target, $mode, $use_include_path, $context);

            # Здесь не перехватывается исключение, возвращается только False
            #try{
                $this->response = fopen($target, $mode, $use_include_path, $context);
            #}catch (\Exception $e){
            #    echo $e->getTraceAsString();
            #};

            #fclose($fp);
            return $this;
        }

        /*
        public function setRequest($request)
        {
            $this->request = $request;
            return $this;
        }
        */

        public function getResponse()
        {
            return $this->response;
        }
    }
}