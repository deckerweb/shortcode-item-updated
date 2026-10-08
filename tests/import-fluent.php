<?php
$export=json_decode(file_get_contents((getenv('SIU_TEST_SNIPPET_DIR') ?: dirname(__DIR__).'/snippets').'/shortcode-item-updated-2.3.0.fluentsnippets.json'),true);
if($export['file_type']!=='fluent_code_snippets'||count($export['snippets'])!==1){throw new Exception('Export format');}
$item=$export['snippets'][0];$code=base64_decode($item['code'],true);$meta=$item['info'];
if(md5($code)!==$item['code_hash']){throw new Exception('Code hash');}
$validated=FluentSnippets\App\Http\Controllers\SnippetsController::validateMeta($meta);
if(is_wp_error($validated)){throw new Exception($validated->get_error_message());}
$validated=FluentSnippets\App\Helpers\Helper::validateCode('PHP',$code);
if(is_wp_error($validated)){throw new Exception($validated->get_error_message());}
$model=new FluentSnippets\App\Model\Snippet();$file=$model->createSnippet($code,$meta);
if(is_wp_error($file)){throw new Exception($file->get_error_message());}
$meta['status']='published';$model->updateSnippet($file,$code,$meta);FluentSnippets\App\Helpers\Helper::cacheSnippetIndex();
echo "PASS FluentSnippets native metadata / hash / PHP validation / file storage / publication\n";
