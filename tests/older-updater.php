<?php
namespace Deckerweb\GitHubReleaseUpdater\V2 { class Updater { const IMPLEMENTATION_VERSION='2.0.0'; } }
namespace {
 require $argv[1].'/wp-load.php';
 if(!shortcode_exists('siu-item-updated')){throw new Exception('Shortcode unavailable');}
 DDW_SIU_Components::updater();
 if(has_filter('upgrader_source_selection',array('DDW_SIU_Components','validate_package'))!==false){throw new Exception('Old updater registered');}
 if(has_action('admin_notices',array('DDW_SIU_Components','notice'))===false){throw new Exception('Missing compatibility notice');}
 echo "PASS older updater guarded / shortcode retained / host notice registered\n";
}
