<?php

if (!function_exists('mirthe_photogrid_fetch')) {
    function mirthe_photogrid_fetch(string $url): ?object {
        $cache = kirby()->cache('mirthe.photogrid');
        $cacheKey = 'flickr-' . sha1($url);
        $cached = $cache->get($cacheKey);

        if ($cached !== null) {
            return is_array($cached) ? json_decode(json_encode($cached)) : $cached;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, kirby()->site()->title());
        $output = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);

        $result = null;

        if ($output !== false && $error === 0) {
            $result = json_decode($output);
            if (is_array($result)) {
                $result = json_decode(json_encode($result));
            }
        }

        if ($result !== null) {
            $cache->set($cacheKey, $result, 2 * 3600);
        }

        return $result;
    }
}

if (!function_exists('mirthe_photogrid')) {
    function mirthe_photogrid($tag): string {
        $setid = (string) $tag->set;
        $tagselection = (string) $tag->tags;
        $api_key = (string) option('flickr.apiKey');
        $user_id = (string) option('flickr.userID');
        $user_name = (string) option('flickr.username');

        if ($api_key === '' || $user_id === '') {
            return '';
        }

        $callsize = $tag->size ?: 'medium';
        $size = $callsize === 'small' ? 't' : 'w';
        $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage = 50;

        $baseUrl = 'https://api.flickr.com/services/rest/?' .
            'api_key=' . urlencode($api_key) .
            '&user_id=' . urlencode($user_id) .
            '&per_page=' . $perPage .
            '&page=' . $page .
            '&format=json' .
            '&nojsoncallback=1' .
            '&media=photos' .
            '&sort=date-taken-desc';

        if ($setid !== '') {
            $url = $baseUrl . '&method=flickr.photosets.getPhotos&photoset_id=' . urlencode($setid);
            $flickr_link = 'https://www.flickr.com/photos/' . $user_name . '/albums/' . $setid;
        } elseif ($tagselection !== '') {
            $url = $baseUrl . '&method=flickr.photos.search&tags=' . urlencode($tagselection) . '&tag_mode=all';
            $flickr_link = 'https://www.flickr.com/search/?user_id=' . $user_id . '&view_all=1&tags=' . urlencode($tagselection);
        } else {
            return '';
        }

        $response = mirthe_photogrid_fetch($url);
        if ($response === null) {
            return '';
        }

        if ($setid !== '') {
            $photo_array = $response->photoset->photo ?? [];
            $page_count = $response->photoset->pages ?? 1;
        } else {
            $photo_array = $response->photos->photo ?? [];
            $page_count = 1;
        }

        $output = '<ul class="photogrid photogrid--' . $callsize . '">' . "\n";

        foreach ($photo_array as $single_photo) {
            $farm_id = $single_photo->farm;
            $server_id = $single_photo->server;
            $photo_id = $single_photo->id;
            $secret_id = $single_photo->secret;
            $title = htmlspecialchars($single_photo->title, ENT_QUOTES, 'UTF-8');
            $photo_url = 'https://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_' . $size . '.jpg';
            $photo_url_big = 'https://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_b.jpg';
            $photo_link = 'https://www.flickr.com/photos/' . $user_name . '/' . $photo_id;
            $photo_caption = $title . ' <a href="' . $photo_link . '" class="floatright">Bekijk op Flickr.com</a>';
            $photo_caption = htmlspecialchars($photo_caption, ENT_QUOTES, 'UTF-8');

            $output .= '<li><a href="' . $photo_url_big . '">' .
                '<img loading="lazy" alt="' . $title . '" data-caption="' . $photo_caption . '" src="' . $photo_url . '">' .
                '<span>' . $title . '</span>' .
                '</a></li>' . "\n";
        }

        $output .= "<li></li>\n</ul>\n";

        $back_page = $page - 1;
        $next_page = $page + 1;

        if ($page_count > 1) {
            $output .= '<ul class="photogrid--browse list-horizontal">';
            $output .= $page > 1 ? '<li><a href="?p=' . $back_page . '">&laquo; Vorige</a></li>' : '<li></li>';
            $output .= '<li><a href="' . $flickr_link . '">Foto\'s bekijken op Flickr</a></li>';
            $output .= $page !== $page_count ? '<li><a href="?p=' . $next_page . '">Volgende &raquo;</a></li>' : '<li></li>';
            $output .= '</ul>';
        } else {
            $output .= '<p><small><a class="lees-meer" href="' . $flickr_link . '">Foto\'s bekijken op Flickr</a></small></p>';
        }

        return $output;
    }
}
