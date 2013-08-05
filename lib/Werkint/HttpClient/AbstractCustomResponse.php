<?php
namespace Werkint\HttpClient;

abstract class AbstractCustomResponse extends AbstractResponse
{
    const PREFIX_CUSTOM = 'CUSTOM_';

    public function __construct(
        array $data
    ) {
        parent::__construct($data);

        $this->populateCustomData();
    }

    protected $customMap = [];

    protected function populateCustomData()
    {
        foreach ($this->customMap as $property => $key) {
            $this->$property = $this->fetch(static::PREFIX_CUSTOM . $key, true);
        }
    }

}