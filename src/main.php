<?php

if (!function_exists('mirthe_photogrid')) {
    function mirthe_photogrid($tag): string {
        $setid = (string) $tag->set;
        $tagselection = (string) $tag->tags;
        $photoid = (string) $tag->photo;
        $selection = trim((string) $tag->selection);
        $api_key = (string) option('flickr.apiKey');
        $user_id = (string) option('flickr.userID');
        $user_name = (string) option('flickr.username');

        if ($api_key === '') {
            return '';
        }

        if ($photoid === '' && $selection === '' && $user_id === '') {
            return '';
        }

        $callsize = $tag->size ?: 'medium';
        $size = $callsize === 'small' ? 't' : 'w';
        $position = strtolower(trim((string) $tag->pos));
        $position = in_array($position, ['left', 'right', 'full', 'wide', 'fullwidth'], true) ? $position : 'full';
        $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $perPage = 50;

        if ($selection !== '') {
            $photo_ids = array_map('trim', explode('|', $selection));
            if (count($photo_ids) < 2 || count(array_filter($photo_ids, static function (string $id): bool {
                return preg_match('/^\d+$/D', $id) !== 1;
            })) > 0) {
                return '';
            }

            $layout = strtolower(trim((string) $tag->pos));
            $allowed_layouts = ['1/1', '1/2', '2/1', 'natural-2', 'natural-3'];
            if (!in_array($layout, $allowed_layouts, true)) {
                $layout = '1/1';
            }

            $photos = [];
            foreach ($photo_ids as $photo_id) {
                $photo_info = mirthe_photogrid_getPhotoInfo($photo_id, $api_key);
                if ($photo_info === null) {
                    return '';
                }
                $photos[] = $photo_info;
            }

            return mirthe_photogrid_renderSelection($photos, $user_name, $callsize, $layout);
        }

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
            '&content_types=0,1,2,3' .
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
