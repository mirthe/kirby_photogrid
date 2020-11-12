<?php

// TODO opschonen en aanbieden?
// https://getkirby.com/docs/guide/plugins/best-practices

// TODO Aparte git repo of submodule van maken?

// TODO size mee kunnen geven voor kleinere beelden, bijv die post met die puzzels

Kirby::plugin('mirthe/photogrid', [
    'options' => [
        'cache' => true
    ],
    'tags' => [
        'photogrid' => [
            'attr' =>[
                'set',
                'tags',
                'size'
            ],
            'html' => function($tag) {

                $setid = $tag->set;
                $tagselection = $tag->tags;

                $api_key = option('flickr.apiKey');
                $user_id = option('flickr.userID');

                $perPage = 50;

                // https://www.flickr.com/services/api/misc.urls.html

                $callsize = "medium";
                if ( $tag->size != "" ) {$callsize = $tag->size;}
                if ( $callsize == 'small' ) {$size = 't';} else {$size = 'w';}
                
                $url = 'http://api.flickr.com/services/rest/?';
                $url.= 'api_key='.$api_key;
                $url.= '&user_id='.$user_id;
                $url.= '&per_page='.$perPage;
                $url.= '&format=json';
                $url.= '&nojsoncallback=1';
                $url.= '&media=photos';
                $url.= '&sort=interestingness-desc';
                    
                if ( $setid != "" )
                {
                    // als tag een nummer is, de set ophalen..
                    $url.= '&method=flickr.photosets.getPhotos';
                    $url.= '&photoset_id='.$setid;
                    
                    $flickr_link = "https://www.flickr.com/photos/mirthe/albums/" . $setid;

                    $response = json_decode(file_get_contents($url));
                    $photo_array = $response->photoset->photo;
                }
                elseif ( $tagselection != "" ) {
                    $url.= '&method=flickr.photos.search';
                    $url.= '&tags='.$tagselection;
                    $url.= '&tag_mode=all';

                    $flickr_link = "https://www.flickr.com/search/?user_id=17324502%40N00&view_all=1&tags=" . $tagselection;

                    $response = json_decode(file_get_contents($url));
                    $photo_array = $response->photos->photo;
                }

                else {
                    // error teruggeven?
                }
                
                // TODO img naar modal vergroten?
                // TODO bladeren toevoegen
                // TODO een title toevoegen met datum en misschien desc
                
                $mijnoutput = '<ul class="photogrid photogrid--'.$callsize.'">'. "\n";
                foreach($photo_array as $single_photo){

                    $farm_id = $single_photo->farm;
                    $server_id = $single_photo->server;
                    $photo_id = $single_photo->id;
                    $secret_id = $single_photo->secret;
                    $title = $single_photo->title;
                    
                    $photo_url = 'https://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
                    
                    // TODO owner ophalen met flickr.photos.getInfo als ik plugin wil aanbieden
                    $photo_link = 'https://www.flickr.com/photos/mirthe/'.$photo_id;
                    
                    $mijnoutput .= '<li><a href="'.$photo_link.'"><img loading="lazy" alt="'.$title.'" src="'.$photo_url.'"></a></li>'. "\n";  
                }
                $mijnoutput .= "<li></li>\n</ul>\n";

                $mijnoutput .= '<p><small><a class="lees-meer" href="'.$flickr_link.'">'."Foto's bekijken op Flickr".'</a></small></p>';

                return $mijnoutput;
            }
        ]
    ]
]);

?>