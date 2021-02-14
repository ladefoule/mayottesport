<?php

return [
    'feeds' => [
        'main' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\Article@getFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => 'feed',

            'title' => 'Le flux RSS de Mayotte Sport',
            'description' => 'Retrouvez les dernières news et actualités publiées sur notre site mayottesport.com',
            'language' => 'fr-FR',

            /*
             * The view that will render the feed.
             */
            'view' => 'feed::atom',

            /*
             * The type to be used in the <link> tag
             */
            'type' => 'application/atom+xml',
        ],
    ],
];
