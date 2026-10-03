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

        $cache = kirby()->cache('mirthe.photogrid');
        $cacheKey = 'flickr-photo-info-' . sha1($url);
        $cached = $cache->get($cacheKey);
        $force = isset($_GET['forcecache']);

        if ($cached !== null && !$force) {
            return is_array($cached) ? json_decode(json_encode($cached)) : $cached;
        }

        $response = mirthe_photogrid_fetch($url);
        if ($response === null || !isset($response->photo)) {
            return null;
        }

        $photo = $response->photo;
        $cache->set($cacheKey, $photo, 2 * 3600);

        return $photo;
    }
}
