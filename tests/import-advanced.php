<?php
wp_set_current_user(1);
$data=json_decode(file_get_contents((getenv('SIU_TEST_SNIPPET_DIR') ?: dirname(__DIR__).'/snippets').'/shortcode-item-updated-2.3.0.code-snippets.json'));
if(strpos($data->generator,'Code Snippets')!==0){throw new Exception('Unsupported import generator');}
$manager=cpas_scripts_manager();$method=new ReflectionMethod($manager,'_import_from_code_snippets_json');$method->setAccessible(true);
$items=$method->invoke($manager,$data->snippets,0,0);
if(count($items)!==1){throw new Exception('Native import failed');}
$id=$items[0]['id'];
if(get_term_meta($id,'script_status',true)){throw new Exception('Expected inactive import');}
if(get_term_meta($id,'script_location',true)!=='all'||get_term_meta($id,'script_hook',true)!=='plugins_loaded'){throw new Exception('Wrong execution scope');}
update_term_meta($id,'script_status',1);$file=$manager->save_php_file($id);
if(!$file){throw new Exception('PHP file generation failed');}
echo "PASS Advanced Scripts 2.6.2 native importer / inactive default / Everywhere / Plugins Loaded / file storage\n";
