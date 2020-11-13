<?php

require '../WebCrawler/util/GutenbergCrawler.php';

$gut = new GutenbergCrawler();
$paragraphs = $gut->getParagraphs();

print_r($paragraphs);
