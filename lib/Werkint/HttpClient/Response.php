<?php
namespace Werkint\HttpClient;

class Response
{
    protected $data;
    protected $format;
    protected $link;

    public function __construct(
        $data, $format, $link
    ) {
        $this->format = $format;
        $this->data = $data;
        $this->link = $link;
    }

    public function toArray()
    {
        return json_decode($this->data, true);
    }

    public function getMessage()
    {
        $ret = $this->toArray();
        return $ret['message'] ? $ret['message'] : 0;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFormat()
    {
        return $this->format;
    }

}