<?php

namespace Mib\Component\PDF;

abstract class Composite extends Component {

    private $children = array();

    /**
     * @param Component $component
     * @return bool
     */
    public function add(Component $component)
    {
        if (in_array($component, $this->children, true))
            return false;

        $this->children[] = $component;

        return true;
    }

    /**
     * @param Component $component
     * @return bool
     */
    public function remove(Component $component)
    {
        if (false === ($pos = array_search($component, $this->children, true)))
            return false;

        unset($this->children[$pos]);

        return true;
    }

    public function getChildren()
    {
        return $this->children;
    }

}