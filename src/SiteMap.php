<?php

namespace SiteMap;

use SiteMap\Exceptions\InvalidFileTypeException;
use SiteMap\Exceptions\InvalidArrayException;

class SiteMap
{
    private array $pages;
    private string $fileType;
    private string $filePath;

    public function __construct(array $data, string $fileType, string $filePath)
    {
        $this->pages = $data;
        $this->filePath = $filePath;
        $this->fileType = strtoupper($fileType);
    }

    public function generate(): void
    {
        $this->validate();

        $content = '';
        switch ($this->fileType) {
            case 'XML':
                $content = $this->xml();
                break;
            case 'CSV':
                $content = $this->csv();
                break;
            case 'JSON':
                $content = $this->json();
                break;
            default:
                throw new InvalidFileTypeException;
        }

        mkdir($this->filePath, 0777, true);
        file_put_contents($this->filePath . '/file.xml', $content);
    }

    private function validate(): void
    {
        foreach ($this->pages as $page) {
            if (!is_array($page) || !isset($page['loc'], $page['lastmod'], $page['priority'], $page['changefreq'])) {
                throw new InvalidArrayException;
            }
        }
    }

    private function xml(): string
    {
        $print = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach ($this->pages as $page) {
            $print .= '
        <url>
            <loc>' . $page['loc'] . '</loc>
            <lastmod>' . $page['lastmod'] . '</lastmod>
            <priority>' . $page['priority'] . '</priority>
            <changefreq>' . $page['changefreq'] . '</changefreq>
        </url>';
        }

        $print .= '
</urlset>';

        return html_entity_decode(htmlspecialchars($print));
    }

    private function csv(): string
    {
        $print = 'loc;lastmod;priority;changefreq' . "\n";
        foreach ($this->pages as $page) {
            $print .= $page['loc'] . ';' . $page['lastmod'] . ';' . $page['priority'] . ';' . $page['changefreq'] . '<br>' . "\n";
        }

        return $print;
    }

    private function json(): string
    {
        $print = '[';
        foreach ($this->pages as $key => $page) {
            $print .= '{' . "\n"
                . ' loc: ' . $page['loc'] . "\n"
                . ' lastmod: ' . $page['lastmod'] . "\n"
                . ' priority: ' . $page['priority'] . "\n"
                . ' changefreq: ' . $page['changefreq'] . "\n" . '}' . (count($this->pages) !== $key + 1 ? "\n" : '');
        }

        return $print . ']';
    }
}