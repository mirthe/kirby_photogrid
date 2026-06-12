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
