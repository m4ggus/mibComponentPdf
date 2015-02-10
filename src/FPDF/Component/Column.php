<?php

namespace Mib\Component\PDF\FPDF\Component;



use Mib\Component\PDF\Composite;

class Column extends Composite {

    public function prepare($context)
    {
        $x = $this->getX();
        $y = $this->getY();
        $w = $this->getWidth();
        $h = 0;

        $cells = $this->getChildren();
        $cellCount = count($cells);

        for ($i = 0; $i < $cellCount; ++$i) {

            $cells[$i]->setX($x);
            $cells[$i]->setY($y);

            $cellWidth = $cells[$i]->getWidth();
            if (!$cellWidth) {
                $cells[$i]->setWidth($w);
            }

            $cellHeight = $cells[$i]->prepare($context);
            $y += $cellHeight;
            $h += $cellHeight;
        }

        return $h;
    }

    public function render($context)
    {
        if ($this->hasBorder())
            $context->Rect($this->getX(), $this->getY(), $this->getWidth(), $this->getHeight());

        foreach ($this->getChildren() as $child)
        {
            $child->render($context);
        }
    }
}