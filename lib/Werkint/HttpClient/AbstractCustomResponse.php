<?php
namespace Werkint\HttpClient;

abstract class AbstractCustomResponse extends AbstractResponse
{
    public function __construct(
        array $data
    ) {
        parent::__construct($data);

        $this->populateCustomData();
    }

    protected $prefixCustom = 'CUSTOM_';
    protected $customMap = [];

    protected function populateCustomData()
    {
        foreach ($this->customMap as $property => $key) {
            $this->$property = $this->fetch($this->prefixCustom . $key, true);
        }
    }

    // -- Setters ---------------------------------------

    /**
     * @param array $customMap
     * @return $this
     */
    public function setCustomMap($customMap)
    {
        $this->customMap = $customMap;
        return $this;
    }

    /**
     * @param string $prefixCustom
     * @return $this
     */
    public function setPrefixCustom($prefixCustom)
    {
        $this->prefixCustom = $prefixCustom;
        return $this;
    }

}