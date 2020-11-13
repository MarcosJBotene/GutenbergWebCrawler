<?php

class GutenbergCrawler
{

    private $url;
    private $proxy;
    private $dom;
    private $html;

    public function __construct()
    {
        $this->url = "http://gutenberg.org";
        $this->proxy = "10.1.21.254:3128";
        $this->dom = new DOMDocument();
    }

    public function getParagraphs()
    {
        $this->loadHTML();
        $divTags = $this->captureDivTags();
        $internalDiv = $this->captureInternalDivsPageContent($divTags);
        $pTags = $this->capturePTags($internalDiv);
        $arrayParagraphs = $this->getArrayParagraphs($pTags);
        return $arrayParagraphs;
    }

    // Cria a configuração com o Proxy.
    private function getContextConnection()
    {
        $arrayConfig = array(
            'http' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true
            ),
            'https' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true
            )
        );

        $context = stream_context_create($arrayConfig);
        return $context;
    }

    // Carrega o HTML
    private function loadHTML()
    {
        $context = $this->getContextConnection();
        $this->html = file_get_contents($this->url, false, $context);

        libxml_use_internal_errors(true);

        $this->dom->loadHTML($this->html);
        libxml_clear_errors();
    }

    private function captureDivTags()
    {
        $divTags = $this->dom->getElementsByTagName('div');
        return $divTags;
    }

    private function captureInternalDivsPageContent($allDivs)
    {
        $internalDivs = null;

        foreach ($allDivs as $div) {
            $class = $div->getAttribute('class');

            if ($class == 'page_content') {
                $internalDivs = $div->getElementsByTagName('div');
                break;
            }
        }

        return $internalDivs;
    }

    private function capturePTags($internalDivs)
    {
        $pTags = null;

        foreach ($internalDivs as $internalDiv) {
            $internalClass = $internalDiv->getAttribute('class');
            if ($internalClass == 'box_announce') {
                $pTags = $internalDiv->getElementsByTagName('p');
            }
        }

        return $pTags;
    }

    private function getArrayParagraphs($pTags)
    {
        $arrayParagraphs = [];

        foreach ($pTags as $p) {
            $arrayParagraphs[] = $p->nodeValue;
        }

        return $arrayParagraphs;
    }
}
