<?php
/**
 *  * Created by hannah on 2/24/2021.
 */

namespace App\config;


class Helper
{
    public static function sanitize($str)
    {
        return htmlspecialchars(strip_tags($str));
    }
}