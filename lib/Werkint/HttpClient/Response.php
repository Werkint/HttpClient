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
        $this->link = $link;

        $this->dataParsed = $this->data = $data;
        if ($this->format == 'json') {
            if ($this->data) {
                $data = json_decode($this->data);
                $this->dataParsed = $data && json_last_error() == JSON_ERROR_NONE ? $data : null;
            }
        }
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
        return $this->dataParsed;
    }

    public function getRawData()
    {
        return $this->data;
    }

    public function getFormat()
    {
        return $this->format;
    }

}