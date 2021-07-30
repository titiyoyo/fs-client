<?php

namespace Titiyoyo\FsClient\Fs;

class AddedField
{
    private $fieldName;
    private $fieldValue;
    private $callback;

    public function __construct($fieldName, $fieldValue, \Closure $callback = null)
    {
        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
        $this->callback = $callback;
    }

    public function execute()
    {
        return $this->callback();
    }

    /**
     * @return \Closure
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @param \Closure $callback
     * @return AddedField
     */
    public function setCallback(\Closure $callback): AddedField
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param mixed $fieldName
     * @return AddedField
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }

    /**
     * @param mixed $fieldValue
     * @return AddedField
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
        return $this;
    }
}
