<?php
wp_set_current_user(1);
$c=new Code_Snippets\REST_API\Import\File_Import_REST_Controller();
$method=new ReflectionMethod($c,'parse_json_file');$method->setAccessible(true);
$file=(getenv('SIU_TEST_SNIPPET_DIR') ?: dirname(__DIR__).'/snippets').'/shortcode-item-updated-2.3.0.code-snippets.json';
$data=$method->invoke($c,$file,basename($file));
if(is_wp_error($data)||count($data)!==1){throw new Exception('JSON parser failed');}
$request=new WP_REST_Request('POST');$request->set_param('snippets',$data);
$result=$c->import_selected_snippets($request)->get_data();
if($result['imported']!==1){throw new Exception('Import failed');}
$id=$result['imported_ids'][0];$snippet=Code_Snippets\get_snippet($id);
if($snippet->active){throw new Exception('Expected inactive import');}
$result=Code_Snippets\activate_snippet($id);
if(is_wp_error($result)){throw new Exception($result->get_error_message());}
echo "PASS Code Snippets JSON parser / native import / inactive default / activation\n";
