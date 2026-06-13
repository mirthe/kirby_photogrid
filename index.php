<?php

require_once __DIR__ . '/photogrid.php';

// TODO opschonen en aanbieden?
// https://getkirby.com/docs/guide/plugins/best-practices

// TODO size mee kunnen geven voor kleinere beelden, bijv die post met die puzzels

Kirby::plugin('mirthe/photogrid', [
    'options' => [
        'cache' => true
    ],
    'snippets' => [],
    'translations' => [
        'nl' => [
            'mirthe.photogrid.previous' => 'Vorige',
            'mirthe.photogrid.next' => 'Volgende',
            'mirthe.photogrid.view-on-flickr' => 'Foto\'s bekijken op Flickr',
            'mirthe.photogrid.view-on-flickr-com' => 'Bekijk op Flickr.com',
        ],
        'en' => [
            'mirthe.photogrid.previous' => 'Previous',
            'mirthe.photogrid.next' => 'Next',
            'mirthe.photogrid.view-on-flickr' => 'View photos on Flickr',
            'mirthe.photogrid.view-on-flickr-com' => 'View on Flickr.com',
        ],
        'fr' => [
            'mirthe.photogrid.previous' => 'Précédent',
            'mirthe.photogrid.next' => 'Suivant',
            'mirthe.photogrid.view-on-flickr' => 'Voir les photos sur Flickr',
            'mirthe.photogrid.view-on-flickr-com' => 'Voir sur Flickr.com',
        ],
        'de' => [
            'mirthe.photogrid.previous' => 'Zurück',
            'mirthe.photogrid.next' => 'Nächste',
            'mirthe.photogrid.view-on-flickr' => 'Fotos auf Flickr ansehen',
            'mirthe.photogrid.view-on-flickr-com' => 'Auf Flickr.com ansehen',
        ]
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
