# Universal site grabber
It is universal site parser with module architecture.

## Modules description

### Data Loaders
This module should load data from url and return this.

Today realized data loader which use CURL, you can create another using IDataLoader interface.

### Page Handlers
This module should process page, for example, extract data from page or save this.

You can declare many page handlers to process one page, its useful when you need to parse tricky pages which have many fields depending on product type.

For example, pages have some same fields and many different fields that depends on something.
In this situation you can create one handler to process same fields and few handlers to process depends fields.
It is help to spread out the page processing to many small and light handler that not depend on each other.

You can create page handler using IPageHandler interface.

### Visit Strategies
This module should visit website pages and return data using provided Data Loader.

Already realized recursively visit strategy.

You can create you strategy using IVisitStrategy interface.


## Grabber methods description

````
    /**
     * Start from index url and handle all pages
     */
    public function start()
````
    /**
     * Start from index url and handle all pages are not in $parsedURLs
     *
     * @param string[] $parsedURLs
     */
    public function startFromOldPosition(array $parsedURLs)
````
    /**
     * @return IPageHandler[]
     */
    public function getPageHandlers(): array
````
    /**
     * Add IPageHandler to page handlers array
     *
     * @param IPageHandler $pageHandler
     */
    public function addPageHandler(IPageHandler $pageHandler)
````
    /**
     * Sets the page processing interrupt when the any handler has been successfully executed.
     *
     * @param bool $value
     */
    public function setProcessUntilFirstProcessedHandler(bool $value)
````
    /**
     * Return array of handled urls
     *
     * @return string[]
     */
    public function getHandledURLs(): array

## Usage Example
See [UsageExample.php](UsageExample.php)

## Requirements
````
PHP 7.1+
````
