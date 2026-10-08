<?php
/** Functional regression checks against a real WordPress database. */
function siu_check( $name, $actual, $expected ) {
	if ( $actual !== $expected ) { fwrite( STDERR, "FAIL $name: " . var_export( $actual, true ) . ' != ' . var_export( $expected, true ) . "\n" ); exit(1); }
	echo "PASS $name\n";
}
function siu_has( $name, $actual, $needle ) { siu_check( $name, strpos( $actual, $needle ) !== false, true ); }
function siu_fixture( $date, $modified, $status = 'publish', $type = 'post', $password = '' ) {
	global $wpdb;
	$id = wp_insert_post( array( 'post_title' => 'SIU regression', 'post_status' => $status, 'post_type' => $type, 'post_password' => $password, 'post_date' => $date ) );
	$wpdb->update( $wpdb->posts, array( 'post_modified' => $modified, 'post_modified_gmt' => get_gmt_from_date( $modified ) ), array( 'ID' => $id ) );
	clean_post_cache( $id );
	return $id;
}
update_option( 'timezone_string', 'Europe/Berlin' ); update_option( 'date_format', 'd.m.Y' ); update_option( 'time_format', 'H:i' );
register_post_type( 'siu_download', array( 'public' => true ) );
register_post_type( 'siu_hidden', array( 'public' => false ) );
$a = siu_fixture( '2020-06-01 10:00:00', '2020-06-03 12:34:00' );
$b = siu_fixture( '2020-06-01 10:00:00', '2020-06-04 13:45:00', 'publish', 'siu_download' );
$unchanged = siu_fixture( '2020-06-01 10:00:00', '2020-06-01 10:00:00' );
$draft = siu_fixture( '2020-06-01 10:00:00', '2020-06-06 12:34:00', 'draft' );
$private = siu_fixture( '2020-06-01 10:00:00', '2020-06-06 12:34:00', 'private' );
$password = siu_fixture( '2020-06-01 10:00:00', '2020-06-06 12:34:00', 'publish', 'post', 'secret' );
$hidden = siu_fixture( '2020-06-01 10:00:00', '2020-06-06 12:34:00', 'publish', 'siu_hidden' );
$bad = siu_fixture( '2020-06-01 10:00:00', '2020-02-31 12:34:00' );
$zero = siu_fixture( '2020-06-01 10:00:00', '0000-00-00 00:00:00' );
$bad_object = get_post($bad); $bad_object->post_modified = '2020-02-31 12:34:00'; wp_cache_set($bad,$bad_object,'posts');
$zero_object = get_post($zero); $zero_object->post_modified = '0000-00-00 00:00:00'; wp_cache_set($zero,$zero_object,'posts');
$engine = new DDW_Shortcode_Item_Updated();
$r = static function( $args = array() ) use ($a,$engine) { return $engine->item_updated( array_merge( array( 'post_id'=>$a, 'output'=>'text' ),$args ) ); };
siu_check('old date', $r(), '03.06.2020');
siu_check('de shortcut uses source', $r(array('date_format'=>'de')), '03.06.2020');
siu_check('us shortcut uses source', $r(array('date_format'=>'us')), '2020-06-03');
siu_check('iso alias', $r(array('date_format'=>'iso')), '2020-06-03');
siu_check('date time label', $r(array('show_time'=>'yes','show_sep'=>'yes','sep'=>', at','show_label'=>'yes','label_before'=>'Updated:','label_after'=>'UTC+2')), 'Updated: 03.06.2020, at 12:34 UTC+2');
siu_check('time only', $r(array('show_date'=>'no','show_time'=>'yes','show_sep'=>'yes')), '12:34');
siu_check('no date time', $r(array('show_date'=>'no','show_time'=>'no','show_label'=>'yes')), '');
siu_check('after label needs time', $r(array('label_after'=>'Uhr')), '03.06.2020');
siu_check('missing', $r(array('post_id'=>99999999)), '');
foreach(array($draft,$private,$password,$hidden,$bad,$zero) as $id) { siu_check('hidden or bad '.$id, $r(array('post_id'=>$id)), ''); }
foreach(array('0','-1','123oops','1e2','',array(1)) as $id) { siu_check('invalid ID '.json_encode($id),$r(array('post_id'=>$id)),''); }
siu_check('latest explicit IDs', $r(array('post_ids'=>"$a,$b,$draft,99999999,$b")), '04.06.2020');
siu_check('list overrides single', $r(array('post_ids'=>(string)$b,'post_id'=>$a)), '04.06.2020');
siu_check('malformed list fails', $r(array('post_ids'=>"$a,bad")), '');
siu_check('oversized list fails', $r(array('post_ids'=>implode(',',array_fill(0,101,$a)))), '');
siu_check('unchanged hidden', $r(array('post_id'=>$unchanged,'only_if_updated'=>'yes')), '');
siu_check('changed shown', $r(array('only_if_updated'=>'yes')), '03.06.2020');
$gap = get_post_datetime($a,'modified')->getTimestamp()-get_post_datetime($a,'date')->getTimestamp();
siu_check('gap exact eligible', $r(array('only_if_updated'=>'yes','min_update_gap'=>$gap)), '03.06.2020');
siu_check('gap below threshold hidden', $r(array('only_if_updated'=>'yes','min_update_gap'=>$gap+1)), '');
siu_check('bad threshold', $r(array('min_update_gap'=>'junk')), '');
siu_check('threshold unused without condition', $r(array('min_update_gap'=>'999999')), '03.06.2020');
siu_check('latest qualifying source', $r(array('post_ids'=>"$unchanged,$a,$b",'only_if_updated'=>'yes')), '04.06.2020');
siu_has('semantic site timezone', $r(array('output'=>'html','semantic'=>'yes')), '<time datetime="2020-06-03T12:34:00+02:00">03.06.2020</time>');
siu_has('multi CSS class', $r(array('output'=>'html','class'=>'foo bar foo')), 'class="item-last-updated foo bar"');
siu_has('unsafe wrapper fallback', $r(array('output'=>'html','wrapper'=>'script')), '<span ');
siu_has('time wrapper datetime', $r(array('output'=>'html','wrapper'=>'time','semantic'=>'yes')), '<time class="item-last-updated" datetime="2020-06-03T12:34:00+02:00">');
siu_check('no nested time', substr_count($r(array('output'=>'html','wrapper'=>'time','semantic'=>'yes')),'<time'), 1);
siu_has('escaped custom label', $r(array('output'=>'html','show_label'=>'yes','label_before'=>'<script>x</script>')), '&lt;script&gt;x&lt;/script&gt;');
siu_check('text strips tags', $r(array('show_label'=>'yes','label_before'=>'<b>Updated:</b>')), 'Updated: 03.06.2020');
siu_has('format escaped late', $r(array('output'=>'html','date_format'=>'\\<\\b\\>Y\\<\\/\\b\\>')), '&lt;b&gt;2020&lt;/b&gt;');
siu_check('scalar fallback no warnings', $r(array('class'=>array('bad'),'wrapper'=>array('bad'))), '03.06.2020');
siu_check('yes German', $engine->yes_no(' JA '), 'yes');
$defaults = static function($atts){$atts['date_format']='Y';return $atts;}; add_filter('siu_filter_shortcode_defaults',$defaults);
siu_check('defaults filter', $r(), '2020');remove_filter('siu_filter_shortcode_defaults',$defaults);
$final = static function($out,$atts){return $out.'!';};add_filter('siu_filter_shortcode_item_updated',$final,10,2);
siu_check('output filter', $r(), '03.06.2020!');remove_filter('siu_filter_shortcode_item_updated',$final,10);
$custom = static function($out,$pairs,$atts){$out['custom']='kept';return $out;};add_filter('shortcode_atts_siu-item-updated',$custom,10,3);
$inspect = static function($out,$atts){return isset($atts['custom'])?$atts['custom']:'lost';};add_filter('siu_filter_shortcode_item_updated',$inspect,10,2);
siu_check('custom attribute filter', $r(), 'kept');remove_filter('siu_filter_shortcode_item_updated',$inspect,10);remove_filter('shortcode_atts_siu-item-updated',$custom,10);
$GLOBALS['post']=get_post($b);siu_has('loop current ID', do_shortcode('[siu-item-updated]'), '04.06.2020');unset($GLOBALS['post']);
siu_has('English default label', $r(array('show_label'=>'yes')), 'Last updated:');
switch_to_locale('de_DE');siu_has('German label',$r(array('show_label'=>'yes')),'Zuletzt aktualisiert:');restore_previous_locale();
$wpml = static function(){return 'en';};update_option('WPLANG','de_DE');add_filter('wpml_current_language',$wpml);
siu_has('WPML page language precedes site locale',$r(array('show_label'=>'yes')),'Last updated:');remove_filter('wpml_current_language',$wpml);update_option('WPLANG','');
$rel = $r(array('display'=>'relative'));siu_has('past relative', $rel, 'ago');
$future = siu_fixture('2020-06-01 10:00:00', wp_date('Y-m-d H:i:s', time()+86400));siu_has('future relative', $r(array('post_id'=>$future,'display'=>'relative')), 'in ');
$recent = siu_fixture('2020-06-01 10:00:00', wp_date('Y-m-d H:i:s', time()-2*DAY_IN_SECONDS));siu_check('relative 2 days', $r(array('post_id'=>$recent,'display'=>'relative')), '2 days ago');
update_option('timezone_string','America/New_York');siu_has('timezone DST winter', $r(array('output'=>'html','semantic'=>'yes')), '-04:00');
foreach(array($a,$b,$unchanged,$draft,$private,$password,$hidden,$bad,$zero,$future,$recent) as $id){wp_delete_post($id,true);}
echo "ALL SHORTCODE CHECKS PASSED\n";
