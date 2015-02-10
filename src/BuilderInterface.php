<?php

namespace Mib\Component\PDF;

interface BuilderInterface {



    /**
     * @param $orientation
     * @param $unit
     * @param $size
     * @return BuilderInterface
     */
    public function create($orientation, $unit, $size);


    public function add($type, array $options = array());

    /**
     * @return DocumentInterface
     */
    public function get();

}