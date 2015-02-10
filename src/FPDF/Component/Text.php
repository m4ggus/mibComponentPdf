<?php

namespace Mib\Component\PDF\FPDF\Component;


use Mib\Component\PDF\Component;

class Text extends Component {

    private $fontFamily = 'Times';

    private $fontSize = '10';

    // 'regular' => '', 'bold' => 'B', 'italic' => 'I', 'underline' => 'U'
    private $fontStyle = '';

    private $value;

    private $align;




    public function __construct(Component $parent, array $options = array())
    {
        parent::__construct($parent, $options);

        if (!isset($options['value'])) {
            throw new \RuntimeException('missing value information');
        }

        $this->value = $options['value'];
        $this->align = 'J';

        if (isset($options['align'])) {
            if (!is_string($options['align'])) {
                throw new \InvalidArgumentException(sprintf('string value expected for option "align"'));
            }
            $alignSettings = array('left' => 'L', 'center' => 'C', 'right' => 'R', 'justify' => 'J');
            $align = strtolower($options['align']);
            if (!isset($alignSettings[$align])) {
                throw new \InvalidArgumentException(sprintf('invalid value for option "align". allowed values: %s', implode(', ', $alignSettings)));
            }
            $this->align = $alignSettings[$align];
        }

        $fontFamilyOption = 'font-family';
        if (isset($options[$fontFamilyOption])) {
            if (!is_string($options[$fontFamilyOption]))
                throw new \InvalidArgumentException(sprintf('string value expected for option "%s"', $fontFamilyOption));
            $this->fontFamily = $options[$fontFamilyOption];

        }

        $fontStyleOption = 'font-style';
        if (isset($options[$fontStyleOption])) {
            if (!is_string($options[$fontStyleOption]))
                throw new \InvalidArgumentException(sprintf('string value expected for option "%s"', $fontStyleOption));
            $this->fontStyle = $options[$fontStyleOption];

        }
    }

    public function prepare($context)
    {
        $context->SetFont($this->fontFamily, $this->fontStyle, $this->fontSize);
        $w = $this->getWidth();
        $h = $context->GetMultiCellHeight($w, $this->fontSize / 2, $this->value, 0, $this->align, false);
        $this->setHeight($h);
        return $h;
    }

    public function render($context)
    {
        if ($this->hasBorder()) {
            $context->Rect($this->getX(), $this->getY(), $this->getWidth(), $this->getHeight());
        }

        $context->SetFont($this->fontFamily, $this->fontStyle, $this->fontSize);

        $context->SetXY($this->getX(), $this->getY());
        // width, height, txt, border, align, fill
        $context->MultiCell($this->getWidth(),  $this->fontSize / 2, $this->value, 0, $this->align, false);
    }
}