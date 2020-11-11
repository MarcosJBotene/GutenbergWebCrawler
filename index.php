<?php

require './util/GutenbergCrawler.php';

        'request_fulluri' => true
$gut = new GutenbergCrawler();
$paragraphs = $gut->getParagraphs();

print_r($paragraphs);
