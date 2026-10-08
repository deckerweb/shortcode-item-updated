<?php
require (getenv('SIU_TEST_SNIPPET_DIR') ?: dirname(__DIR__).'/snippets').'/shortcode-item-updated-2.3.0.snippet.php';
if(!shortcode_exists('siu-item-updated')){throw new Exception('late loading did not register');}
echo "PASS standalone snippet after init / class guard\n";
