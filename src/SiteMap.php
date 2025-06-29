<?php
//111111111111111
namespace SiteMap;

class SiteMap
{
    private array $pages;
    private string $fileType;

    public function __construct(array $data, string $fileType)
    {
        $this->pages = $data;
        $this->fileType = $fileType;
    }

    public function generate(): string
    {
        if (!$this->validate()) {
            return 'Неверный формат входных данных';
        }

        switch (strtoupper($this->fileType)) {
            case 'XML':
                return $this->xml();
            case 'CSV':
                return $this->csv();
            case 'JSON':
                return $this->json();
            default:
                throw new InvalidFileTypeException("Unsupported file type");
        }
    }

    private function validate(): bool
    {

        return true;
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

        return '<pre>' . htmlspecialchars($print) . '</pre>';
    }

    private function csv(): string
    {
        $print = 'loc;lastmod;priority;changefreq<br>';
        foreach ($this->pages as $page) {
            $print .= $page['loc'] . ';' . $page['lastmod'] . ';' . $page['priority'] . ';' . $page['changefreq'] . '<br>';
        }

        return $print;
    }

    private function json(): string
    {
        $print = '[';
        foreach ($this->pages as $key => $page) {
            $print .= '{<br>'
            . ' loc: ' . $page['loc'] . ',<br>'
            . ' lastmod: ' . $page['lastmod'] . ',<br>'
            . ' priority: ' . $page['priority'] . ',<br>'
            . ' changefreq: ' . $page['changefreq'] . '<br>}' . (count($this->pages) !== $key + 1 ? '<br>' : '');
        }

        return '<pre>' . $print . ']' . '</pre>';
    }
}