<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber\DataLoaders;


class CurlDataLoader implements IDataLoader
{
    /**
     * @param string $url which need get data
     *
     * @return String
     */
    public function loadHTML(string $url): String
    {
        $headers = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}