<?php

namespace Mib\Component\PDF;

abstract class Component {

    /** @var Component */
    private $parent;

    /** @var integer */
    private $x;

    /** @var integer */
    private $y;

    /** @var integer */
    private $width;

    /** @var integer */
    private $height;

    /**
     * @var boolean
     */
    private $border;

    public function __construct(Component $parent, array $options = array())
    {
        $this->parent = $parent;

        $this->x = 0.0;
        $this->y = 0.0;
        $this->width = 0.0;
        $this->height = 0.0;
        $this->border = false;


        if (isset($options['width'])) {
            if (false === ($width = filter_var($options['width'], FILTER_VALIDATE_FLOAT)))
                throw new \InvalidArgumentException(sprintf('numeric value expected for option "width"'));
            $this->width = $width;
        }

        if (isset($options['height'])) {
            if (false === ($height = filter_var($options['height'], FILTER_VALIDATE_FLOAT)))
                throw new \InvalidArgumentException(sprintf('numeric value expected for option "width"'));
            $this->height = $height;
        }

        $borderOption = 'border';
        if (isset($options[$borderOption])) {
            $border = !!$options[$borderOption];
//            if (false === ($border = filter_var($options[$borderOption], FILTER_VALIDATE_BOOLEAN)))
//                throw new \InvalidArgumentException(sprintf('boolean value expected for option "%s"', $borderOption));
            $this->border = $border;
        }
    }

    /**
     * @return Component|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Component $component
     * @return bool
     */
    public function add(Component $component)
    {
        return false;
    }

    /**
     * @param Component $component
     * @return bool
     */
    public function remove(Component $component)
    {
        return false;
    }

    public function getChildren()
    {
        return array();
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return boolean
     */
    public function hasBorder()
    {
        return $this->border;
    }

    /**
     * @param boolean $border
     */
    public function setBorder($border)
    {
        $this->border = $border;
    }

    abstract public function prepare($context);

    abstract public function render($context);
}