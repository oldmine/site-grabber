<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 22.12.2018
 */

namespace oldmine\SiteGrabber\VisitStrategies;


use DOMDocument;
use DOMXPath;
use oldmine\SiteGrabber\DataLoaders\IDataLoader;
use oldmine\SiteGrabber\Page;
use oldmine\SiteGrabber\URLExtensions\URLExtensions;

class RecursivelyVisitStrategy implements IVisitStrategy
{
    /**
     * Pages are which have been checked on this grabber run
     *
     * @var string[]
     */
    private $checkedURLs = array();

    private $indexUrl;
    private $host;
    /**
     * @var IDataLoader
     */
    private $dataLoader;

    public function __construct(string $indexUrl, IDataLoader $dataLoader)
    {
        if (empty($indexUrl))
            throw new \InvalidArgumentException('Variable $indexUrl must be not empty');

        if (empty($dataLoader))
            throw new \InvalidArgumentException('Variable $dataLoader must be not empty');

        $parsedIndexUrl = parse_url($indexUrl);

        if (empty($parsedIndexUrl['host']))
            throw  new \InvalidArgumentException('URL is not valid');

        $this->indexUrl = $indexUrl;
        $this->host = $parsedIndexUrl['host'];
        $this->dataLoader = $dataLoader;
    }

    /**
     * Returns a generator that provides Pages objects
     *
     * @return \Generator
     * @yield Page
     */
    public function getGenerator(): \Generator
    {
        return $this->getNext($this->indexUrl);
    }

    private function getNext(string $pageURL): \Generator
    {
        //If page with this url is not checked
        if (!in_array($pageURL, $this->checkedURLs)) {
            $this->checkedURLs[] = $pageURL;
            $data = $this->dataLoader->loadHTML($pageURL);

            //If $pageURL is empty is broken page or url, trying skip and go next
            if (!empty($pageURL))
                yield new Page($pageURL, $data);

            $doc = new DOMDocument();
            @$doc->loadHTML($data);
            $xpath = new DOMXPath($doc);
            $tags = $xpath->query('.//a');
            /**
             * @var $tag \DOMElement
             */
            foreach ($tags as $tag) {
                $url = $tag->getAttribute('href');
                $parsed_url = parse_url($url);
                if (empty($parsed_url['scheme'])) {
                    //If is relative url
                    $url = URLExtensions::relativeToAbsolute($this->host, $url);
                    yield from $this->getNext($url);
                } else if (!empty($parsed_url['host'])) {
                    //If is absolute url
                    if ($parsed_url['host'] == $this->host) {
                        //If absolute url is under host
                        yield from $this->getNext($url);
                    }
                }
            }
        }
    }
}