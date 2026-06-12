<?php

if (!function_exists('mirthe_photogrid_renderSinglePhoto')) {
    function mirthe_photogrid_renderSinglePhoto(object $photo_info, string $user_name, string $callsize, string $size, string $position = 'full'): string {
        $farm_id = $photo_info->farm;
        $server_id = $photo_info->server;
        $secret_id = $photo_info->secret;
        $photo_id = $photo_info->id;
        $title = trim($photo_info->title->_content ?? '');
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $description = trim($photo_info->description->_content ?? '');
        $description = $description !== '' ? nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) : '';
        $owner_nsid = trim($photo_info->owner->nsid ?? '');
        $owner_path = $owner_nsid !== '' ? $owner_nsid : $user_name;
        $display_size = $position === 'left' || $position === 'right' ? 'n' : ($size === 'small' ? 'w' : 'b');
        $photo_url = 'https://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_' . $display_size . '.jpg';
        $photo_url_big = 'https://farm' . $farm_id . '.staticflickr.com/' . $server_id . '/' . $photo_id . '_' . $secret_id . '_h.jpg';
        $photo_link = 'https://www.flickr.com/photos/' . $owner_path . '/' . $photo_id;

        if ($position === 'left' || $position === 'right') {
            $img_class = $position === 'left' ? 'floatleft' : 'floatright';
            $output = '<figure class="' . $img_class . '"><a href="' . $photo_link . '">' .
                '<img loading="lazy" alt="' . $title . '" src="' . $photo_url . '">' .
                '</a></figure>';

            return $output;
        }

        $output = '<figure class="imgwide">';
        $output .= '<a href="' . $photo_link . '">' .
            '<img loading="lazy" alt="' . $title . '" src="' . $photo_url . '">' .
            '</a>';

        // if ($title !== '') {
        //     $output .= '<figcaption>' . $title . '</figcaption>';
        // }

        $output .= '</figure>';

        return $output;
    }
}
