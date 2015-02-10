<?php

namespace Mib\Component\PDF\FPDF;


use fpdf\FPDF;
use Mib\Component\PDF\BuilderInterface;
use Mib\Component\PDF\Composite;
use Mib\Component\PDF\DocumentInterface;

class Builder implements BuilderInterface {

    static private $outputTypes = array(
        DocumentInterface::OUTPUT_INLINE    => 'I',
        DocumentInterface::OUTPUT_DOWNLOAD  => 'D',
        DocumentInterface::OUTPUT_FILE      => 'F',
    );

    static private $orientationTypes = array(
        DocumentInterface::ORIENTATION_LANDSCAPE => 'L',
        DocumentInterface::ORIENTATION_PORTRAIT => 'P',
    );

    static private $unitTypes = array(
        DocumentInterface::UNIT_MILLIMETER => 'mm',
    );

    static private $sizeTypes = array(
        DocumentInterface::SIZE_A4 => 'A4',
    );


    /** @var FPDF */
    private $document;

    private $components = array();

    private $component;

    /**
     * @param $orientation
     * @param $unit
     * @param $size
     * @return $this
     */
    public function create(
        $orientation = DocumentInterface::ORIENTATION_PORTRAIT,
        $unit = DocumentInterface::UNIT_MILLIMETER,
        $size = DocumentInterface::SIZE_A4 )
    {
        if (!isset(self::$orientationTypes[$orientation]))
            throw new \InvalidArgumentException('invalid orientation type');

        if (!isset(self::$unitTypes[$unit]))
            throw new \InvalidArgumentException('invalid unit type');

        if (!isset(self::$sizeTypes[$size]))
            throw new \InvalidArgumentException('invalid size type');

        $this->document = new Document(
            self::$orientationTypes[$orientation],
            self::$unitTypes[$unit],
            self::$sizeTypes[$size]
        );

        $this->component = $this->document;

        return $this;
    }

    /**
     * @param int $orientation
     * @param int $size
     * @return $this
     */
    public function addPage($orientation = DocumentInterface::ORIENTATION_PORTRAIT, $size = DocumentInterface::SIZE_A4)
    {
        $this->document->AddPage($orientation, $size);
        return $this;
    }

    public function add($type, array $options = array())
    {
        $class = __NAMESPACE__.'\\Component\\'.ucfirst($type);

        if (!class_exists($class, true))
            throw new \RuntimeException('component of type '.$type.' does not exist');

        $instance = new $class($this->component, $options);

        $this->component->add($instance);

        if ($instance instanceof Composite)
            $this->component = $instance;

        return $this;
    }

    public function end()
    {
        $instance = $this->component;

        if ($instance instanceof Composite && $instance->getParent() !== null)
            $this->component = $instance->getParent();

        return $this;
    }

    /**
     * @return Document
     */
    public function get()
    {
        $this->document->prepare($this->document->getResource());
        $this->document->render($this->document->getResource());

        return $this->document;
    }
}