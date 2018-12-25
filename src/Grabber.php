<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber;

use oldmine\SiteGrabber\PageHandlers\IPageHandler;
use oldmine\SiteGrabber\VisitStrategies\IVisitStrategy;

class Grabber
{
    /**
     * Pages are which have been handled
     *
     * @var string[]
     */
    private $handledURLs = array();

    /**
     * Array of page handlers
     *
     * @var IPageHandler[]
     */
    private $pageHandlers = array();

    /**
     * @var IVisitStrategy
     */
    private $visitStrategy;

    /**
     * @var bool
     */
    private $processUntilFirstProcessedHandler = false;

    public function __construct(IVisitStrategy $visitStrategy)
    {
        $this->visitStrategy = $visitStrategy;
    }

    /**
     * Start from index url and handle all pages
     */
    public function start()
    {
        $this->getAllLinks();
    }

    /**
     * Start from index url and handle all pages are not in $parsedURLs
     *
     * @param string[] $parsedURLs
     */
    public function startFromOldPosition(array $parsedURLs)
    {
        $this->handledURLs = $parsedURLs;
        $this->getAllLinks();
    }

    /**
     * @return IPageHandler[]
     */
    public function getPageHandlers(): array
    {
        return $this->pageHandlers;
    }

    /**
     * Add IPageHandler to page handlers array
     *
     * @param IPageHandler $pageHandler
     */
    public function addPageHandler(IPageHandler $pageHandler)
    {
        $this->pageHandlers[] = $pageHandler;
    }

    /**
     * Sets the page processing interrupt when the any handler has been successfully executed.
     *
     * @param bool $value
     */
    public function setProcessUntilFirstProcessedHandler(bool $value)
    {
        $this->processUntilFirstProcessedHandler = $value;
    }

    /**
     * Return array of handled urls
     *
     * @return string[]
     */
    public function getHandledURLs(): array
    {
        return $this->handledURLs;
    }

    private function getAllLinks()
    {
        $generator = $this->visitStrategy->getGenerator();

        /**
         * @var $page Page
         */
        foreach ($generator as $page) {
            $this->handlePageHTML($page->url, $page->htmlData);
        }
    }

    private function handlePageHTML(string $url, string $data)
    {
        //If page with this url is already handled
        if (!in_array($url, $this->handledURLs)) {
            $this->handledURLs[] = $url;
            foreach ($this->pageHandlers as $handler) {
                $haveBeenProcessed = $handler->handle($url, $data);

                //If enabled processing interrupt when the any handler has been successfully executed
                if ($this->processUntilFirstProcessedHandler && $haveBeenProcessed)
                    break;
            }
        }
    }
}