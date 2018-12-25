<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber\URLExtensions;

use oldmine\RelativeToAbsoluteUrl\RelativeToAbsoluteUrl;

class URLExtensions
{
    public static function relativeToAbsolute(string $baseDomain, string $relativeURL)
    {
        $baseDomain = self::normalizeURL($baseDomain);
        $absoluteURL = RelativeToAbsoluteUrl::urlToAbsolute($baseDomain, $relativeURL);

        return $absoluteURL;
    }

    public static function normalizeURL($url)
    {
        $parsed_url = parse_url($url);
        if (empty($parsed_url['scheme'])) {
            $url = "http://$url";
            self::normalizeURL($url);
        }

        return $url;
    }


}