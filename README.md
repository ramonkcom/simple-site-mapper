# SimpleSiteMapper

SimpleSiteMapper allows you to easily create and edit sitemap.xml files.

## Basic Usage

### Installation

Install the latest version with:

```bash
$ composer require ramonztro/simple-site-mapper
```

### Basic Usage

```php
<?php

use Ramonztro\SimpleSiteMapper\SimpleSiteMapper;

//Creates a SiteMapper
$mapper = new SimpleSiteMapper();

//Loads a sitemap file or creates it if doesn't exist
$filename = 'examples.xml';
$mapper->loadSiteMap($filename);

//Adds an URL to sitemap
$mapper->addUrl('http://example.com/example1');

//Adds another URL to sitemap
$mapper->addUrl('http://example.com/example2', '2016-06-06', 'anual', 1);

//Edit an URL
$mapper->addUrl('http://example.com/example', '2016-01-01', 'monthly', 0.8);

//Save sitemap to file
$mapper->save();

//Loads a sitemap index file or creates it if doesn't exist
$mapper->loadSiteMapIndex('sitemap.xml')

//Adds a sitemap to index
$mapper->addSiteMap('http://example.com/sitemap_01.xml', '2016-06-06');

//Save sitemap index to file
$mapper->save();

//Ping search engines
$mapper->pingGoogle('http://example.com/sitemap.xml');
$mapper->pingBing('http://example.com/sitemap.xml');

```

## About

### Requirements

- PHP 5.3 or higher.

### MIT License

*Permission is hereby granted, free of charge, to any person obtaining a copy * 
of this software and associated documentation files (the "Software"), to    
deal in the Software without restriction, including without limitation the  
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or 
sell copies of the Software, and to permit persons to whom the Software is  
furnished to do so, subject to the following conditions:*                    
                                                                            
*The above copyright notice and this permission notice shall be included in  
all copies or substantial portions of the Software.*                         
                                                                            
*THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER      
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING     
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS * 
IN THE SOFTWARE.*

### Author

Ramon Kayo - <contato@ramonkayo.com> - <http://twitter.com/ramonztro>
