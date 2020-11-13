<?php

require './util/GutenbergCrawler.php';

$gut = new GutenbergCrawler();
$paragraphs = $gut->getParagraphs();

print_r($paragraphs);
