<?php
namespace Werkint\HttpClient;

abstract class AbstractMerchant
{
    const QUERY_URL = null;

    protected function getRequestUrl()
    {
        if (!static::QUERY_URL) {
            throw new \Exception('Wrong query url');
        }

        return static::QUERY_URL;
    }

    /**
     * @return PostForm
     */
    public function getForm()
    {
        $form = new PostForm(
            $this->getRequestUrl(),
            $this->getData()
        );
        return $form;
    }

    /**
     * @return array
     */
    abstract public function getData();

    protected $customFields = [];

    /**
     * @param $key
     * @param $value
     * @return self
     */
    public function setCustomField($key, $value)
    {
        $this->customFields[$key] = $value;
    }

}