<?php

if (!function_exists('mirthe_photogrid_getPhotoInfo')) {
    function mirthe_photogrid_getPhotoInfo(string $photoId, string $api_key): ?object {
        if ($photoId === '' || $api_key === '') {
            return null;
        }

        $url = 'https://api.flickr.com/services/rest/?' .
            'api_key=' . urlencode($api_key) .
            '&method=flickr.photos.getInfo' .
            '&photo_id=' . urlencode($photoId) .
            '&format=json' .
            '&nojsoncallback=1';

        $response = mirthe_photogrid_fetch($url);
        if ($response === null || !isset($response->photo)) {
            return null;
        }

        return $response->photo;
    }
}
