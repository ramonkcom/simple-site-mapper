<?php
/*
+---------------------------------------------------------------------------+
| SimpleSiteMapper                                                          |
| Copyright (c) 2013-2016, Ramon Kayo                                       |
+---------------------------------------------------------------------------+
| Author        : Ramon Kayo                                                |
| Email         : contato@ramonkayo.com                                     |
| License       : Distributed under the MIT License                         |
| Full license  : https://github.com/ramonztro/simple-site-mapper           |
+---------------------------------------------------------------------------+
| "Simplicity is the ultimate sophistication." - Leonardo Da Vinci          |
+---------------------------------------------------------------------------+
*/
namespace Ramonztro\SimpleSiteMapper;

use \DOMDocument;
use \DOMXPath;
use \Exception;

class SimpleSiteMapper {
	
	const
		SitemapPingUrlBing = 'http://www.bing.com/webmaster/ping.aspx?siteMap=',
		SitemapPingUrlGoogle = 'http://www.google.com/webmasters/tools/ping?sitemap=';
	
	private 
		$filename = '',
		$dom = null;
	
/*===========================================================================*/
// PUBLIC METHODS
/*===========================================================================*/
	/**
	 * 
	 * @param string $loc
	 * @param string $lastmod
	 */
	public function addSiteMap($loc, $lastmod = null) {
		$sitemapindex = $this->dom->getElementsByTagName('sitemapindex')->item(0);
		$id = hash('sha256', $loc);
		
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$siteMapNode = $xpath->query("/s:sitemapindex/s:sitemap[@id='$id']")->item(0);
		
		if ($siteMapNode) {
			$this->editSiteMap($loc);
		} else {
			$sitemapNode = $this->dom->createElement('sitemap');
			$sitemapNode->setAttribute('id', $id);
			
			$locNode = $this->dom->createElement("loc");
			$sitemapNode->appendChild($locNode);
			$locNode->appendChild($this->dom->createTextNode($loc));
		
			$lastmodNode = $this->dom->createElement("lastmod");
			$sitemapNode->appendChild($lastmodNode);
			if (!$lastmod) $lastmod = gmdate('Y-m-d\TH:i:s\Z', time());
			$lastmodNode->appendChild($this->dom->createTextNode($lastmod));
			
			$sitemapindex->appendChild($sitemapNode);
		}
	}
	
	/**
	 * 
	 * @param string $loc
	 * @param string $lastmod
	 * @param string $changefreq
	 * @param string $priority
	 */
	public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null) {
		$urlset = $this->dom->getElementsByTagName('urlset')->item(0);
		$id = hash('sha256', $loc);
		
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$urlNode = $xpath->query("/s:urlset/s:url[@id='$id']")->item(0);
		
		$date = gmdate('Y-m-d\TH:i:s\Z', time());
		
		if ($urlNode) {
			$this->editUrl($loc, $lastmod, $changefreq, $priority);
		} else {
			$urlNode = $this->dom->createElement('url');
			$urlNode->setAttribute('id', $id);
			
			$locNode = $this->dom->createElement("loc");
			$urlNode->appendChild($locNode);
			$locNode->appendChild($this->dom->createTextNode($loc));
		
			$lastmodNode = $this->dom->createElement("lastmod");
			$urlNode->appendChild($lastmodNode);
			if (!$lastmod) $lastmod = $date;
				$lastmodNode->appendChild($this->dom->createTextNode($lastmod));
			
			if ($changefreq) {
				$changefreqNode = $this->dom->createElement("changefreq");
				$urlNode->appendChild($changefreqNode);
				$changefreqNode->appendChild($this->dom->createTextNode($changefreq));
			}
			
			if ($priority) {
				$priorityNode = $this->dom->createElement("priority");
				$urlNode->appendChild($priorityNode);
				$priorityNode->appendChild($this->dom->createTextNode($priority));
			}
				
			$urlset->appendChild($urlNode);
		}
	}
	
	/**
	 * 
	 * @param string $filename
	 */
	public function loadSiteMap($filename = 'sitemap.xml') {
		$this->filename = $filename;
		$this->dom = new DOMDocument('1.0', 'UTF-8');
		if (!is_file($filename) || !file_exists($filename)) {
			$this->dom->loadXML('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
		} else {
			@$this->dom->load($this->filename);
		}
	}
	
	/**
	 * 
	 * @param string $filename
	 */
	public function loadSiteMapIndex($filename = 'sitemap.xml') {
		$this->filename = $filename;
		$this->dom = new DOMDocument('1.0', 'UTF-8');
		if (!is_file($filename) || !file_exists($filename)) {
			$this->dom->loadXML('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');
		} else {
			@$this->dom->load($this->filename);
		}
	}
	
	/**
	 * 
	 * @param string $loc
	 */
	public function editSiteMap($loc) {
		$id = hash('sha256', $loc);
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		
		$siteMapNode = $xpath->query("/s:sitemapindex/s:sitemap[@id='$id']")->item(0);
		if (!$siteMapNode) return;
		
		$lastmodNode = $xpath->query("/s:sitemapindex/s:sitemap[@id='$id']/s:lastmod")->item(0);
		$date = gmdate('Y-m-d\TH:i:s\Z', time());
		if (!$lastmodNode) {
			$lastmodNode = $this->dom->createElement("lastmod");
			$siteMapNode->appendChild($lastmodNode);
			$lastmodNode->appendChild($this->dom->createTextNode($date));
		} else {
			$lastmodNode->nodeValue = $date;
		}
		
	}
	
	/**
	 * 
	 * @param string $loc
	 * @param string $lastmod
	 * @param string $changefreq
	 * @param string $priority
	 */
	public function editUrl($loc, $lastmod = null, $changefreq = null, $priority = null) {
		$id = hash('sha256', $loc);
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		
		$urlNode = $xpath->query("/s:urlset/s:url[@id='$id']")->item(0);
		if (!$urlNode) return;
		
		$lastmodNode = $xpath->query("/s:urlset/s:url[@id='$id']/s:lastmod")->item(0);
		$date = gmdate('Y-m-d\TH:i:s\Z', time());
		if (!$lastmodNode) {
			$lastmodNode = $this->dom->createElement("lastmod");
			$urlNode->appendChild($lastmodNode);
			$lastmodNode->appendChild($this->dom->createTextNode($date));
		} else {
			$lastmodNode->nodeValue = $date;
		}
		
		if ($changefreq) {
			$changefreqNode = $xpath->query("/s:urlset/s:url[@id='$id']/s:changefreq")->item(0);
			if (!$changefreqNode) {
				$changefreqNode = $this->dom->createElement("changefreq");
				$urlNode->appendChild($changefreqNode);
				$changefreqNode->appendChild($this->dom->createTextNode($changefreq));
			} else {
				$changefreqNode->nodeValue = $changefreq;
			}
		}
		
		if ($priority) {
			$priorityNode = $xpath->query("/s:urlset/s:url[@id='$id']/s:priority")->item(0);
			if (!$priorityNode) {
				$priorityNode = $this->dom->createElement("priority");
				$urlNode->appendChild($priorityNode);
				$priorityNode->appendChild($this->dom->createTextNode($priority));
			} else {
				$priorityNode->nodeValue = $priority;
			}
		}
	}
	
	/**
	 * 
	 * @param string $loc
	 */
	public function deleteSiteMap($loc) {
		$id = hash('sha256', $loc);
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$sitemapNode = $xpath->query("/s:sitemapindex/s:sitemap[@id='$id']")->item(0);
		if ($sitemapNode) $sitemapNode->parentNode->removeChild($sitemapNode);
	}
	
	/**
	 * 
	 * @param string $url
	 */
	public function deleteUrl($loc) {
		$id = hash('sha256', $loc);
		$xpath = new DOMXPath($this->dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$urlNode = $xpath->query("/s:urlset/s:url[@id='$id']")->item(0);
		if ($urlNode) $urlNode->parentNode->removeChild($urlNode);
	}
	
	/**
	 * 
	 * @param string $sitemapUrl
	 * @return boolean
	 */
	public function pingAllSearchEngines($sitemapUrl) {
		$successBing = $this->pingBing($sitemapUrl);
		$successGoogle = $this->pingGoogle($sitemapUrl);
		return ($successBing && $successGoogle);
	}
	
	/**
	 * 
	 * @param string $sitemapUrl
	 * @return boolean
	 */
	public function pingBing($sitemapUrl) {
		return $this->ping(self::SitemapPingUrlBing . urlencode($sitemapUrl));
	}
	
	/**
	 * 
	 * @param string $sitemapUrl
	 * @return boolean
	 */
	public function pingGoogle($sitemapUrl) {
		return $this->ping(self::SitemapPingUrlGoogle . urlencode($sitemapUrl));
	}
	
	/**
	 * 
	 * @throws Exception
	 */
	public function rewriteRobotsTxt() {
		$file = @fopen('robots.txt', 'w');
		if (!$file) throw new Exception("Couldn't open the file.");
	
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->load($filename);
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		
		$siteMapLocNodes = $xpath->query("/s:sitemapindex/s:sitemap/s:loc");
		for ($i=0; $i<$siteMapLocNodes->length; $i++) {
			$success = fwrite($file, 'Sitemap: ' . $siteMapLocNodes->item($i)->nodeValue . PHP_EOL);
			if (!$success) throw new Exception("Couldn't write to the file.");
		}
		
		$success = fclose($file);
		if (!$success) throw new Exception("Couldn't close the file.");
	}
	
	/**
	 * 
	 */
	public function save() {
		$this->dom->save($this->filename);
	}
	
	/*===========================================================================*/
	// PRIVATE METHODS
	/*===========================================================================*/
	private function ping($url) {
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_NOBODY, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_exec($ch);
	    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    return ($status == 200);
	}
	
}