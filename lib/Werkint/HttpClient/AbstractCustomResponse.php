<?php
namespace Werkint\HttpClient;

abstract class AbstractCustomResponse extends AbstractResponse
{
    protected $prefixCustom = null;
    protected $customMap = [];

    public function populateCustomData()
    {
        foreach ($this->customMap as $property => $key) {
            $val = $this->fetch($this->prefixCustom . $key, true);
            $this->dataParsed['_custom_' . $property] = $val;
            $this->$property = $val;
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