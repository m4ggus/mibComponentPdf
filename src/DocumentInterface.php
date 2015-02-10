<?php

namespace Mib\Component\PDF;

interface DocumentInterface
{
    const OUTPUT_INLINE   = 0;
    const OUTPUT_DOWNLOAD = 1;
    const OUTPUT_FILE     = 2;

    const ORIENTATION_PORTRAIT = 0;
    const ORIENTATION_LANDSCAPE = 1;

    const UNIT_MILLIMETER = 0;

    const SIZE_A4 = 0;

    public function getWidth();

    public function getHeight();

    public function getMarginLeft();

    public function getMarginRight();

    public function getMarginTop();

    public function getMarginBottom();

    public function addPage();
}