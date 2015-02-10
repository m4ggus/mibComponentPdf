<?php

namespace Mib\Component\PDF\FPDF\Component;

use Mib\Component\PDF\Composite;

class Row extends Composite {

    public function prepare($context)
    {
        $x = $this->getX();
        $y = $this->getY();
        $w = $this->getWidth();

        $h = 0;

        $columns = $this->getChildren();
        $columnCount = count($columns);
        $columnWidth = $w / $columnCount;
        $columnWeights = array();
        $columnAutoWidthCount = 0;

        for ($i = 0; $i < $columnCount; ++$i) {
            $weight = $columns[$i]->getWidth();
            $columnWeights[] = $weight;
            if (!$weight) {
                ++$columnAutoWidthCount;
            }
        }

        $columnWidthSum = array_sum($columnWeights);
        $columnWidthMax = max($columnWeights);

        if ($columnWidthSum <= $w) {
            if ($columnAutoWidthCount) {

                $remainingSpace = $w - $columnWidthSum;
                $columnAutoWidth = $remainingSpace / $columnAutoWidthCount;

                for ($i = 0; $i < $columnCount; ++$i) {
                    if (!$columnWeights[$i]) {
                        $columnWeights[$i] = $columnAutoWidth;
                    }
                }
            }
        } else {
            throw new \RuntimeException(sprintf('assigned column widths exceeds row width'));
        }


        for ($i = 0; $i < $columnCount; ++$i) {
            $columns[$i]->setX($x);
            $columns[$i]->setY($y);
            $columns[$i]->setWidth($columnWeights[$i]);
            $columnHeight = $columns[$i]->prepare($context);
            if ($columnHeight > $h) {
                $h = $columnHeight;
            }
            $columns[$i]->setHeight($columnHeight);

            $x += $columnWeights[$i];
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