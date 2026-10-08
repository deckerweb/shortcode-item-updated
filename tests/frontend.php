<?php
require $argv[1].'/wp-load.php';
if(!shortcode_exists('siu-item-updated')){throw new Exception('No frontend shortcode');}
if(class_exists('Deckerweb\\GitHubReleaseUpdater\\V2\\Updater',false)){throw new Exception('Frontend loaded updater engine');}
if(!empty($GLOBALS['deckerweb_library_runtime_v1'])){throw new Exception('Frontend elected Library runtime');}
echo "PASS frontend shortcode / no updater engine / no Library runtime\n";
