<?php

namespace TotalCRM\MoySklad\Components;

/**
 * Filter query is used with ListQuery::filter()
 * Class FilterQuery
 * @package MoySklad\Components
 */
class FilterQuery
{
    private array $queryBuffer = [];

    /**
     * Field = Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function eq($field, $value): self
    {
        $this->queryBuffer[] = "$field=$value";
        return $this;
    }

    /**
     * Field != Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function neq($field, $value): self
    {
        $this->queryBuffer[] = "$field!=$value";
        return $this;
    }

    /**
     * Field  > Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function gt($field, $value): self
    {
        $this->queryBuffer[] = "$field>$value";
        return $this;
    }

    /**
     * Field < Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function lt($field, $value): self
    {
        $this->queryBuffer[] = "$field<$value";
        return $this;
    }

    /**
     * Field >= Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function gte($field, $value): self
    {
        $this->queryBuffer[] = "$field>=$value";
        return $this;
    }

    /**
     * Field <= Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function lte($field, $value): self
    {
        $this->queryBuffer[] = "$field<=$value";
        return $this;
    }

    /**
     * Field ~ Value
     * @param $field
     * @param $value
     * @return $this
     */
    public function like($field, $value): self
    {
        $this->queryBuffer[] = "$field~$value";
        return $this;
    }

    /**
     * Get internal query buffer
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->queryBuffer;
    }

    /**
     * Convert itself to string
     * @return string
     */
    public function getRaw(): string
    {
        return implode(";", $this->queryBuffer);
    }
}
