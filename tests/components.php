<?php
function ck($name,$condition){if(!$condition){fwrite(STDERR,"FAIL $name\n");exit(1);}echo "PASS $name\n";}
ck('updater 2.1.0', \Deckerweb\GitHubReleaseUpdater\V2\Updater::IMPLEMENTATION_VERSION === '2.1.0');
ck('host package validator registered', has_filter('upgrader_source_selection',array('DDW_SIU_Components','validate_package')) === 30);
ck('Library 0.8.1 elected', !empty($GLOBALS['deckerweb_library_runtime_v1']) && str_contains(get_class($GLOBALS['deckerweb_library_runtime_v1']),'V0_8_1'));
$manifest=json_decode(file_get_contents(dirname(DDW_SIU_FILE).'/includes/deckerweb-plugin-library/compatibility.json'),true);
foreach($manifest['hashes'] as $file=>$hash){ck('Library hash '.$file,hash_file('sha256',dirname(DDW_SIU_FILE).'/includes/deckerweb-plugin-library/'.$file)===$hash);}
switch_to_locale('de_DE');ck('informal host translation',__('Documentation','shortcode-item-updated')==='Dokumentation');$translate=require dirname(DDW_SIU_FILE).'/includes/updater-translations.php';ck('informal updater translation',str_contains($translate('The private update could not be authorized. Check the repository credentials and refresh updates.'),'Prüfe'));restore_previous_locale();
switch_to_locale('de_DE_formal');ck('formal updater translation',str_contains($translate('The private update could not be authorized. Check the repository credentials and refresh updates.'),'Prüfen Sie'));restore_previous_locale();
require_once ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php';require_once ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php';
require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
$GLOBALS['wp_filesystem']=new WP_Filesystem_Direct(null);
$dir=WP_CONTENT_DIR.'/upgrade/siu-validation/';wp_mkdir_p($dir);
$header="<?php\n/*\nPlugin Name: Shortcode Item Updated\nVersion: 2.4.0\nRequires at least: 6.7\nRequires PHP: 8.0\nText Domain: shortcode-item-updated\nUpdate URI: https://github.com/deckerweb/shortcode-item-updated\n*/\n";
file_put_contents($dir.'shortcode-item-updated.php',$header);
$base=plugin_basename(DDW_SIU_FILE);set_site_transient('update_plugins',(object)array('response'=>array($base=>(object)array('new_version'=>'2.4.0'))));
$extra=array('plugin'=>$base,'type'=>'plugin','action'=>'update');$grader=new Plugin_Upgrader();
ck('valid offered newer package',DDW_SIU_Components::validate_package($dir,'',$grader,$extra)===$dir);
foreach(array('name'=>array('Shortcode Item Updated','Wrong'),'domain'=>array('Text Domain: shortcode-item-updated','Text Domain: wrong'),'URI'=>array('https://github.com/deckerweb/shortcode-item-updated','https://example.com/else'),'version'=>array('2.4.0','2.5.0'),'old'=>array('2.4.0','2.2.0'),'PHP'=>array('Requires PHP: 8.0','Requires PHP: 99.0'),'WP'=>array('Requires at least: 6.7','Requires at least: 99.0')) as $name=>$swap){file_put_contents($dir.'shortcode-item-updated.php',str_replace($swap[0],$swap[1],$header));ck('reject wrong '.$name,is_wp_error(DDW_SIU_Components::validate_package($dir,'',$grader,$extra)));}
file_put_contents($dir.'shortcode-item-updated.php',$header);
$grader->bulk=true;ck('Core bulk context validated',DDW_SIU_Components::validate_package($dir,'',$grader,array('plugin'=>$base))===$dir);
file_put_contents($dir.'shortcode-item-updated.php',str_replace('2.4.0','2.2.0',$header));ck('bulk bad package refused',is_wp_error(DDW_SIU_Components::validate_package($dir,'',$grader,array('plugin'=>$base))));
ck('unrelated plugin preserved',DDW_SIU_Components::validate_package($dir,'',$grader,array('plugin'=>'other/other.php','type'=>'plugin','action'=>'update'))===$dir);
ck('conflicting context untouched',DDW_SIU_Components::validate_package($dir,'',$grader,array('plugin'=>$base,'type'=>'theme','action'=>'update'))===$dir);
unlink($dir.'shortcode-item-updated.php');rmdir($dir);
echo "ALL COMPONENT CHECKS PASSED\n";
