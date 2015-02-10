<?php

namespace Mib\Component\PDF\FPDF\Component;

use Mib\Component\PDF\Component;

class Image extends Component
{
    private $path;

    public function __construct(Component $parent, array $options = array())
    {
        parent::__construct($parent, $options);

        if (!isset($options['path'])) {
            throw new \InvalidArgumentException(sprintf('missing path option for image element'));
        }

        if (!file_exists($options['path'])) {
            throw new \InvalidArgumentException(sprintf('the file specified in path does not exist'));
        }

        $this->path = $options['path'];
    }

    public function prepare($context)
    {
        $w = $this->getWidth();

        list($width, $height) = getimagesize($this->path);
        $width *= 0.264583333;
        $h = $height * 0.264583333 + 2;
        // add scaling?
        if ($w < $width) {
            $h = $w / $width * $height * 0.264583333;
        }
        $this->setHeight($h);
        return $h;
    }

    public function render($context)
    {
        if ($this->hasBorder())
            $context->Rect($this->getX(), $this->getY(), $this->getWidth(), $this->getHeight());

        $context->Image($this->path, $this->getX() + 1, $this->getY() + 1);
    }
}
