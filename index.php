<?php

include 'vendor/autoload.php';

file_put_contents(__DIR__.'/cat.jpg', file_get_contents('http://lorempixel.com/200/200/cats/meow/'));

$builder = new \Mib\Component\PDF\FPDF\Builder();

$builder
    ->create()
    ->addPage()
    ->add('row', array('border'=>false))
    ->add('column')
    ->add('image', array('path' => __DIR__.'/cat.jpg'))
    ->end('column')
    ->add('column')
    ->add('text', array('value' => 'Katze vom ' . date('d.m.Y')."\n\n", 'font-style' => 'BI', 'align' => 'right'))
    ->end('column')
    ->end('row')
    ->add('row', array('border'=>false))
    ->add('column', array('width' => 25))
    ->add('text', array('value' => 'Spalte #1', 'font-style' => 'B'))
    ->end('column')
    ->add('column')
    ->add('text', array('value' => 'Spalte #2', 'font-style' => 'B'))
    ->end('column')
    ->add('column')
    ->add('text', array('value' => 'Spalte #3', 'font-style' => 'B'))
    ->end('column')
    ->add('column', array('width' => 20))
    ->add('text', array('value' => 'Spalte #4', 'font-style' => 'B'))
    ->end('column')
    ->add('column', array('width' => 20))
    ->add('text', array('value' => 'Spalte #5', 'font-style' => 'B'))
    ->end('column')
    ->end('row');

    foreach (range(1, 5) as $level) {
        $builder->add('row', array('border'=>false))
            ->add('column', array('width' => 25))
            ->add('text', array('value' => $level.'-1'))

            ->end('column')
            ->add('column')
            ->add('text', array('value' => $level.'-2'))
            ->add('image', array('path' => __DIR__.'/cat.jpg'))
            ->end('column')
            ->add('column')
            ->add('text', array('value' => $level.'-3'))
            ->end('column')
            ->add('column', array('width' => 20))
            ->add('text', array('value' => $level.'-4'))
            ->end('column')
            ->add('column', array('width' => 20))
            ->add('text', array('value' => $level.'-5'))
            ->end('column')
            ->end('row');
    }

$builder->get()->save();