<?php

if (!function_exists('mirthe_photogrid')) {
    function mirthe_photogrid($tag): string {
        $setid = (string) $tag->set;
        $tagselection = (string) $tag->tags;
        $photoid = (string) $tag->photo;
        $api_key = (string) option('flickr.apiKey');
        $user_id = (string) option('flickr.userID');
        $user_name = (string) option('flickr.username');

        if ($api_key === '') {
            return '';
        }

        if ($photoid === '' && $user_id === '') {
            return '';
        }

        $callsize = $tag->size ?: 'medium';
        $size = $callsize === 'small' ? 't' : 'w';
        $position = strtolower(trim((string) $tag->pos));
        $position = in_array($position, ['left', 'right', 'full'], true) ? $position : 'full';
        $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage = 50;

        if ($photoid !== '') {
            $photo_info = mirthe_photogrid_getPhotoInfo($photoid, $api_key);
            if ($photo_info === null) {
                return '';
            }

            return mirthe_photogrid_renderSinglePhoto($photo_info, $user_name, $callsize, $size, $position);
        }

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

        return mirthe_photogrid_renderPhotoGrid($photo_array, $user_name, $callsize, $size, $flickr_link, $page_count, $page);
    }
}
