<?php

// TODO opschonen en aanbieden?
// https://getkirby.com/docs/guide/plugins/best-practices

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
                $user_name = option('flickr.username');

                // https://www.flickr.com/services/api/misc.urls.html
                $callsize = "medium";
                if ( $tag->size != "" ) {$callsize = $tag->size;}
                if ( $callsize == 'small' ) {$size = 't';} else {$size = 'w';}
                
                $page = isset($_GET['p']) ? $_GET['p'] : 1;
                $perPage = 30;

                $url = 'http://api.flickr.com/services/rest/?';
                $url.= 'api_key='.$api_key;
                $url.= '&user_id='.$user_id;
                $url.= '&per_page='.$perPage;
                $url.= '&page='.$page;
                $url.= '&format=json';
                $url.= '&nojsoncallback=1';
                $url.= '&media=photos';
                $url.= '&sort=date-taken-desc';
                //$url.= '&sort=interestingness-desc';
                    
                if ( $setid != "" )
                {
                    // als tag een nummer is, de set ophalen..
                    $url.= '&method=flickr.photosets.getPhotos';
                    $url.= '&photoset_id='.$setid;
                    
                    $flickr_link = "https://www.flickr.com/photos/".$user_name."/albums/" . $setid;

                    $response = json_decode(file_get_contents($url));
                    $photo_array = $response->photoset->photo;
                    $page_count = $response->photoset->pages;
                }
                elseif ( $tagselection != "" ) {
                    $url.= '&method=flickr.photos.search';
                    $url.= '&tags='.$tagselection;
                    $url.= '&tag_mode=all';

                    $flickr_link = "https://www.flickr.com/search/?user_id=".$user_id."&view_all=1&tags=" . $tagselection;

                    $response = json_decode(file_get_contents($url));
                    $photo_array = $response->photos->photo;
                    $page_count = 1;
                }

                else {
                    // error teruggeven?
                }
                
                $mijnoutput = '<ul class="photogrid photogrid--'.$callsize.'">'. "\n";
                foreach($photo_array as $single_photo){

                    $farm_id = $single_photo->farm;
                    $server_id = $single_photo->server;
                    $photo_id = $single_photo->id;
                    $secret_id = $single_photo->secret;
                    $title = $single_photo->title;
                    
                    $photo_url = 'https://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
                    $photo_url_big = 'https://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_b.jpg';
                    
                    // TODO owner ophalen met flickr.photos.getInfo als ik plugin wil aanbieden
                    $photo_link = 'https://www.flickr.com/photos/'.$user_name.'/'.$photo_id;
                    $photo_caption = $title . ' <a href='.$photo_link.' class=floatright>Bekijk op Flickr.com</a>';

                    $mijnoutput .= '<li><a href="'.$photo_url_big.'">
                        <img loading="lazy" alt="'.$title.'" data-caption="'.$photo_caption.'" src="'.$photo_url.'">
                        <span>'.$title.'</span>
                    </a></li>'. "\n";  
                }
                $mijnoutput .= "<li></li>\n</ul>\n";

                // pagination
                $back_page = $page - 1;
                $next_page = $page + 1;
                
                if($page_count > 1) {
                    $mijnoutput .= '<ul class="photogrid--browse list-horizontal">';
                    if($page > 1) {$mijnoutput .= "<li><a href='?p=$back_page'>&laquo; Vorige</a></li>";} else { $mijnoutput .= "<li></li>";} 
                    $mijnoutput .= '<li><a href="'.$flickr_link.'">'."Foto's bekijken op Flickr".'</a></li>';
                    if($page != $page_count) {$mijnoutput .= "<li><a href='?p=$next_page'>Volgende &raquo;</a></li>";} else { $mijnoutput .= "<li></li>";} 
                    $mijnoutput .= "</ul>";
                } else {
                    $mijnoutput .= '<p><small><a class="lees-meer" href="'.$flickr_link.'">'."Foto's bekijken op Flickr".'</a></small></p>';
                }
                
                return $mijnoutput;
            }
        ]
    ]
]);

?>