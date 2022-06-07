<?php

namespace Devscreencast\ResponseClass;


class JsonResponse
{
    public $status;
    public $message;
    public $data = [];
    public $statusCode;
    public $result;
    
    public function __construct($status, $message = '', array $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        
        $this->result = array(
          'status' => $this->status
        );
        
        echo $this->response();
    }
    
    /**
     * Format user message with HTTP status and status code
     *
     * @return string, json object
     */
    public function response()
    {
        $statusCode = 200;
        
        //set the HTTP response code
        switch ($this->status)
        {
            case "unauthorized":
                $statusCode = 401;
                break;
            case "exception":
                $statusCode = 500;
                break;
        }
        
        //set the response header
        header("Content-Type", "application/json");
        header(sprintf('HTTP/1.1 %s %s', $statusCode, $this->status), true, $statusCode);
        
        if ( $this->message != '')
        {
            $this->result['message'] = $this->message;
        }
        
        if ( count($this->data) > 0 ) {
            $this->result['data'] = $this->data;
        }
        
        return json_encode($this->result);
    }

    public function send() {
        $this
          ->sendHeaders();
        $this
          ->sendContent();
        if (function_exists('fastcgi_finish_request')) {
          fastcgi_finish_request();
        }
        elseif ('cli' !== PHP_SAPI) {
          static::closeOutputBuffers(0, true);
        }
        return $this;
      }

      public function sendHeaders() {

        // headers have already been sent by the developer
        if (headers_sent()) {
          return $this;
        }
      
        /* RFC2616 - 14.18 says all Responses need to have a Date */
        if (!$this->headers
          ->has('Date')) {
          $this
            ->setDate(\DateTime::createFromFormat('U', time()));
        }
      
        // headers
        foreach ($this->headers
          ->allPreserveCase() as $name => $values) {
          foreach ($values as $value) {
            header($name . ': ' . $value, false, $this->statusCode);
          }
        }
      
        // status
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);
      
        // cookies
        foreach ($this->headers
          ->getCookies() as $cookie) {
          if ($cookie
            ->isRaw()) {
            setrawcookie($cookie
              ->getName(), $cookie
              ->getValue(), $cookie
              ->getExpiresTime(), $cookie
              ->getPath(), $cookie
              ->getDomain(), $cookie
              ->isSecure(), $cookie
              ->isHttpOnly());
          }
          else {
            setcookie($cookie
              ->getName(), $cookie
              ->getValue(), $cookie
              ->getExpiresTime(), $cookie
              ->getPath(), $cookie
              ->getDomain(), $cookie
              ->isSecure(), $cookie
              ->isHttpOnly());
          }
        }
        return $this;
      }
      public function sendContent() {
        echo $this->content;
        return $this;
      }

      public static function closeOutputBuffers($targetLevel, $flush) {
        $status = ob_get_status(true);
        $level = count($status);
      
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE) : -1;
        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || $flags === ($s['flags'] & $flags) : $s['del'])) {
          if ($flush) {
            ob_end_flush();
          }
          else {
            ob_end_clean();
          }
        }
      }
      public function setDate(\DateTime $date) {
        $date
          ->setTimezone(new \DateTimeZone('UTC'));
        $this->headers
          ->set('Date', $date
          ->format('D, d M Y H:i:s') . ' GMT');
        return $this;
      }
}