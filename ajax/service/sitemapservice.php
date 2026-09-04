<?php

class SitemapService
{
    private string $basePath;
    private string $baseUrl;
    private array $files;

    public function __construct()
    {
        $configFile = __DIR__ . '/../../include/sitemap.php';

        if (file_exists($configFile)) {
            $config = require $configFile;
        } else {
            $config = [
                'base_path' => '/home/londonserver/uk.goride.run/public',
                'files'     => [
                    'blog'   => 'sitemap-blog.xml',
                    'static' => 'sitemap-static.xml',
                    'index'  => 'sitemap.xml',
                ],
                'base_url'  => 'https://www.goride.run/uk',
            ];
        }

        $this->basePath = rtrim($config['base_path'], '/');
        $this->baseUrl  = rtrim($config['base_url'], '/');
        $this->files    = $config['files'];
    }

    /**
     * Get allowed sitemap path by type.
     */
    private function getPath(string $type = 'blog'): string
    {
        $file = $this->files[$type] ?? 'sitemap-blog.xml';
        return $this->basePath . '/' . $file;
    }

    /**
     * Build target blog URL.
     */
    public function getBlogUrl(string $slug, ?string $catUrl = null): string
    {
        $slug = ltrim($slug, '/');

        if (!empty($catUrl)) {
            $catUrl = trim($catUrl, '/');

            if (preg_match('/^https?:\/\//i', $catUrl)) {
                return rtrim($catUrl, '/') . '/' . $slug;
            }

            $catUrl = preg_replace('/^uk\//i', '', $catUrl);
            return $this->baseUrl . '/' . $catUrl . '/' . $slug;
        }

        return $this->baseUrl . '/blog/' . $slug;
    }

    /**
     * Add or update blog URL in sitemap-blog.xml.
     */
    public function addBlog(string $slug, ?string $catUrl = null, ?string $lastmod = null): bool
    {
        try {
            $path = $this->getPath('blog');
            $url  = $this->getBlogUrl($slug, $catUrl);
            $lastmod = $lastmod ?: date('Y-m-d');

            if (!file_exists($path)) {
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }

                $xml = new SimpleXMLElement(
                    '<?xml version="1.0" encoding="UTF-8"?>' .
                    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>'
                );
            } else {
                $xml = @simplexml_load_file($path);

                if ($xml === false) {
                    $xml = new SimpleXMLElement(
                        '<?xml version="1.0" encoding="UTF-8"?>' .
                        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>'
                    );
                }
            }

            // Check duplicate or existing URL
            $found = false;
            foreach ($xml->url as $existingUrlNode) {
                if ((string) $existingUrlNode->loc === $url) {
                    $existingUrlNode->lastmod = $lastmod;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $urlNode = $xml->addChild('url');
                $urlNode->addChild('loc', htmlspecialchars($url, ENT_XML1, 'UTF-8'));
                $urlNode->addChild('lastmod', $lastmod);
            }

            $this->saveFormattedXML($xml, $path);
            $this->updateSitemapIndex();

            return true;
        } catch (Exception $e) {
            error_log('Sitemap error (addBlog): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove blog URL from sitemap-blog.xml.
     */
    public function removeBlog(string $slug, ?string $catUrl = null): bool
    {
        try {
            $path = $this->getPath('blog');

            if (!file_exists($path)) {
                return true;
            }

            $url = $this->getBlogUrl($slug, $catUrl);

            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            if (!@$dom->load($path)) {
                return false;
            }

            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            $nodes = $xpath->query("//s:url[s:loc='$url']");
            $removed = false;

            if ($nodes && $nodes->length > 0) {
                foreach ($nodes as $node) {
                    $node->parentNode->removeChild($node);
                    $removed = true;
                }
            } else {
                $nodesNoNs = $xpath->query("//url[loc='$url']");
                if ($nodesNoNs && $nodesNoNs->length > 0) {
                    foreach ($nodesNoNs as $node) {
                        $node->parentNode->removeChild($node);
                        $removed = true;
                    }
                }
            }

            if ($removed) {
                $dom->save($path);
                $this->updateSitemapIndex();
            }

            return true;
        } catch (Exception $e) {
            error_log('Sitemap error (removeBlog): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Automatically update main sitemap.xml index.
     */
    public function updateSitemapIndex(): void
    {
        try {
            $indexPath = $this->getPath('index');
            $blogFile  = $this->files['blog'] ?? 'sitemap-blog.xml';
            $blogSitemapUrl = $this->baseUrl . '/' . $blogFile;
            $lastmod = date('Y-m-d');

            if (!file_exists($indexPath)) {
                $xml = new SimpleXMLElement(
                    '<?xml version="1.0" encoding="UTF-8"?>' .
                    '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>'
                );
            } else {
                $xml = @simplexml_load_file($indexPath);

                if ($xml === false) {
                    $xml = new SimpleXMLElement(
                        '<?xml version="1.0" encoding="UTF-8"?>' .
                        '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>'
                    );
                }
            }

            $found = false;
            foreach ($xml->sitemap as $sitemapNode) {
                if ((string) $sitemapNode->loc === $blogSitemapUrl) {
                    $sitemapNode->lastmod = $lastmod;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $node = $xml->addChild('sitemap');
                $node->addChild('loc', htmlspecialchars($blogSitemapUrl, ENT_XML1, 'UTF-8'));
                $node->addChild('lastmod', $lastmod);
            }

            $this->saveFormattedXML($xml, $indexPath);
        } catch (Exception $e) {
            error_log('Sitemap error (updateSitemapIndex): ' . $e->getMessage());
        }
    }

    /**
     * Save XML element cleanly with DOM formatting.
     */
    private function saveFormattedXML(SimpleXMLElement $xml, string $path): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($path);
    }
}
