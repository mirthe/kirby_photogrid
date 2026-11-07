<?php

if (!function_exists('mirthe_photogrid_renderSelection')) {
    function mirthe_photogrid_renderSelection(array $photos, string $user_name, string $callsize, string $layout): string {
        $layout_class = str_replace('/', '-', $layout);
        $display_size = $callsize === 'small' ? 'w' : 'b';
        $output = '<div class="photogrid photogrid--selection photogrid--selection-' . $layout_class . '">' . "\n";

        foreach ($photos as $photo) {
            if (!isset($photo->farm, $photo->server, $photo->id, $photo->secret)) {
                continue;
            }

            $farm_id = $photo->farm;
            $server_id = $photo->server;
            $photo_id = $photo->id;
            $secret_id = $photo->secret;
            $title = htmlspecialchars(trim($photo->title->_content ?? ''), ENT_QUOTES, 'UTF-8');
            $owner_nsid = trim($photo->owner->nsid ?? '');
            $owner_path = $owner_nsid !== '' ? $owner_nsid : $user_name;
            $photo_url = 'https://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_' . $display_size . '.jpg';
            $photo_link = 'https://www.flickr.com/photos/' . rawurlencode($owner_path) . '/' . rawurlencode((string) $photo_id);

            $output .= '<div class="photogrid__selection-item"><a href="' . htmlspecialchars($photo_link, ENT_QUOTES, 'UTF-8') . '">' .
                '<img loading="lazy" title="' . $title . '" src="' . $photo_url . '">' .
                '</a></div>' . "\n";
        }

        return $output . '</div>' . "\n";
    }
}
