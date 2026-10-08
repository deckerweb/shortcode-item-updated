<?php
function nc($name,$condition){if(!$condition){throw new Exception($name);}echo "PASS $name\n";}
$main=get_current_blog_id();$sites=get_sites(array('number'=>2,'fields'=>'ids'));$other=(int)end($sites);
$source=wp_insert_post(array('post_title'=>'SIU main fixture','post_status'=>'publish','post_date'=>'2020-06-01 10:00:00'));
$before=do_shortcode('[siu-item-updated post_id="'.$source.'" output="text"]');
switch_to_blog($other);update_option('date_format','Y');update_option('timezone_string','Asia/Tokyo');
$fixture=wp_insert_post(array('post_title'=>'SIU subsite fixture','post_status'=>'publish','post_date'=>'2021-12-01 10:00:00'));$actual=do_shortcode('[siu-item-updated post_id="'.$fixture.'" output="text"]');nc('current subsite date format',$actual===wp_date('Y',get_post_datetime($fixture,'modified')->getTimestamp()));
nc('subsite timezone',strpos(do_shortcode('[siu-item-updated post_id="'.$fixture.'" semantic="yes"]'),'+09:00')!==false);
wp_delete_post($fixture,true);restore_current_blog();nc('original site restored',get_current_blog_id()===$main);nc('main site output unchanged',do_shortcode('[siu-item-updated post_id="'.$source.'" output="text"]')===$before);wp_delete_post($source,true);
$cache='ddw_ghru_'.substr(md5(DDW_SIU_Components::REPOSITORY),0,24);set_site_transient($cache,array('local-test'=>true));
nc('shared Library election once',get_class($GLOBALS['deckerweb_library_runtime_v1'])==='Deckerweb\\PluginLibrary\\V0_8_1\\Library');
echo "ALL NETWORK ISOLATION CHECKS PASSED\n";
