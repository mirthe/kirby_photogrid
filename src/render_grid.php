<?php

if (!function_exists('mirthe_photogrid_renderPhotoGrid')) {
    function mirthe_photogrid_renderPhotoGrid(array $photo_array, string $user_name, string $callsize, string $size, string $flickr_link, int $page_count, int $page): string {
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
