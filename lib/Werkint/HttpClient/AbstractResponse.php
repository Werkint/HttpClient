<?php
namespace Werkint\HttpClient;

abstract class AbstractResponse
{
    const PREFIX = null;

    protected $data;
    protected $dataRaw;

    public function getDataRaw()
    {
        return $this->dataRaw;
    }

    public function __construct(
        array $data
    ) {
        $this->data = $this->dataRaw = $data;
        $this->parseData();

        // Удаляем лишние поля
        if (static::PREFIX) {
            $this->cleanupData();
        }
    }

    protected function fetch($key, $noPrefix = false)
    {
        if (static::PREFIX && !$noPrefix) {
            $key = static::PREFIX . $key;
        }
        if (!isset($this->data[$key])) {
            return null;
        }
        $val = $this->data[$key];
        unset($this->data[$key]);
        return $val;
    }

    protected function cleanupData()
    {
        foreach ($this->data as $key => $val) {
            if (stripos($key, static::PREFIX) === 0) {
                unset($this->data[$key]);
            }
        }
    }

    protected $dataMap = [];

    protected function parseData(
        array $map = null
    ) {
        if (!$map) {
            $map = $this->dataMap;
        }
        foreach ($map as $property => $key) {
            $this->$property = $this->fetch($key);
        }
    }

}