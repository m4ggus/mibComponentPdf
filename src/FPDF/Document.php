<?php

namespace Mib\Component\PDF\FPDF;

use Mib\Component\PDF\Composite;
use Mib\Component\PDF\DocumentInterface;

class Document extends Composite implements DocumentInterface
{
    private $marginLeft   = 10;
    private $marginRight  = 10;
    private $marginTop    = 10;
    private $marginBottom = 10;

    private $resource;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        $this->resource = new FPDF(10, $orientation, $unit, $size);

        $this->resource->SetFont('Times','',10);
        $this->setX($this->resource->GetX());
        $this->setY($this->resource->getY());
        $this->setWidth($this->resource->getPageWidth()-$this->marginLeft-$this->marginRight);
        $this->setHeight($this->resource->getPageHeight()-$this->marginBottom-$this->marginTop);
    }

    public function addPage()
    {
        $this->resource->AddPage('', '');
        $this->setX($this->resource->GetX());
        $this->setY($this->resource->getY());
        $this->setWidth($this->resource->getPageWidth()-$this->marginLeft-$this->marginRight);
        $this->setHeight($this->resource->getPageHeight()-$this->marginBottom-$this->marginTop);
    }

    public function save()
    {
        $this->resource->Output('mi_component_pdf.pdf', 'I');
    }

    public function prepare($context)
    {
        $x = $this->getX();
        $y = $this->getY();

        $w = $this->getWidth();
        $h = $this->getHeight();

        foreach ($this->getChildren() as $child)
        {
            $child->setX($x);
            $child->setY($y);
            $child->setWidth($w);
            $childHeight = $child->prepare($context);
            $child->setHeight($childHeight);
            $y += $childHeight;

            if ($y > $h) {
                $y = $this->getY();
                $child->setY($y);
                $childHeight = $child->prepare($context);
                $child->setHeight($childHeight);
                $y += $childHeight;
            }
        }
    }

    public function render($context)
    {
        $x = $this->getX();
        $y = $this->getY();

        $w = $this->getWidth();
        $h = $this->getHeight();

        $skip = true;

        foreach ($this->getChildren() as $child)
        {
            // new page if element start from top margin
            if ($child->getY() < $this->marginTop + 1) {
                if ($skip) {
                    $skip = !$skip;
                } else {
                    $this->addPage();
                }
            }

            $child->render($this->resource);

//            $y += $columnHeight;
        }
    }

    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    public function getMarginRight()
    {
        return $this->marginRight;
    }

    public function getMarginTop()
    {
        return $this->marginTop;
    }

    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    public function getResource()
    {
        return $this->resource;
    }
}