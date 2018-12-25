<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber\PageHandlers;


use mysqli;
use oldmine\SiteGrabber\URLExtensions\URLExtensions;

class SaveToDatabasePageHandler implements IPageHandler
{
    private const MYSQL_HOST = 'localhost';
    private const MYSQL_PORT = '3307';
    private const MYSQL_DATABASE = 'site_grabber_2';
    private const MYSQL_LOGIN = 'root';
    private const MYSQL_PASSWORD = '';

    public function getSavedPages(string $siteHost)
    {
        $connection = $this->getConnection();
        $result = $connection->query("SELECT `page_url` FROM `site_page` WHERE `site_url` = '$siteHost'");
        $pagesUrlArray = array();
        while ($row = $result->fetch_row()) {
            $pagesUrlArray[] = $row[0];
        }
        $connection->close();

        return $pagesUrlArray;
    }

    /**
     * @param string $url page url
     * @param string $data page html data
     *
     * @return bool Handler have processed page.
     *              True - if handler have processed page.
     *              False - if handler can't process page.
     */
    public function handle(string $url, string $data): bool
    {
        // return false if handler can't process page
        if (empty($url))
            return false;

        $parsed_url = parse_url(URLExtensions::normalizeURL($url));
        $this->insertNewSite($parsed_url['host']);
        $this->insertNewSitePage($parsed_url['host'], $url, $data);

        return true;
    }

    private function getConnection()
    {
        $connect = new mysqli(self::MYSQL_HOST, self::MYSQL_LOGIN, self::MYSQL_PASSWORD, self::MYSQL_DATABASE, self::MYSQL_PORT);

        if ($connect->connect_errno) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw  new \Exception("Не удалось подключиться к MySQL: (" . $connect->connect_errno . ") " . $connect->connect_error);
        }

        return $connect;
    }

    private function insertNewSite(string $siteHost)
    {
        $connection = $this->getConnection();
        $query = $connection->prepare('INSERT INTO `site` ( `url`) VALUES ( ? )');
        $query->bind_param('s', $siteHost);
        $query->execute();
        $query->close();
        $connection->close();
    }

    private function insertNewSitePage(string $siteHost, string $pageUrl, string $pageData)
    {
        $connection = $this->getConnection();
        $query = $connection->prepare('INSERT INTO `site_page` ( `html_data`, `page_url`, `site_url`) VALUES ( ?, ?, ? )');
        $query->bind_param('sss', $pageData, $pageUrl, $siteHost);
        $query->execute();
        $query->close();
        $connection->close();
    }
}