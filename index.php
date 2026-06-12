<?php

require_once __DIR__ . '/photogrid.php';

// TODO opschonen en aanbieden?
// https://getkirby.com/docs/guide/plugins/best-practices

// TODO size mee kunnen geven voor kleinere beelden, bijv die post met die puzzels

Kirby::plugin('mirthe/photogrid', [
    'options' => [
        'cache' => true
    ],
    'tags' => [
        'photogrid' => [
            'attr' => [
                'set',
                'tags',
                'photo',
                'pos',
                'size'
            ],
            'html' => function ($tag) {
                return mirthe_photogrid($tag);
            }
        ]
    ]
]);
