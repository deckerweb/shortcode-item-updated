"""Generate standalone PHP and manager imports from the maintained shared shortcode engine."""
from pathlib import Path
import json,base64,hashlib,sys
r=Path(__file__).resolve().parents[1];dest=Path(sys.argv[1]) if len(sys.argv)>1 else r/'snippets';dest.mkdir(parents=True,exist_ok=True)
core=(r/'includes/class-shortcode.php').read_text()[6:]
header='''/**
 * Shortcode Item Updated 2.3.0 — standalone PHP snippet.
 * Requires WordPress 6.7 / PHP 7.4. Run everywhere before content rendering.
 * Code Snippets / FluentSnippets: paste the body without an opening PHP tag.
 * Advanced Scripts: use the complete PHP file with exactly one opening tag.
 * Copyright © 2015–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 * Source: https://github.com/deckerweb/shortcode-item-updated
 * The file-based deckerweb Library and plugin updater belong to the installable plugin.
 */
'''
body=header+core+'''
if ( ! function_exists( 'ddw_siu_snippet_initialize' ) ) {
 /**
  * Register after the site locale initializes; the installed plugin takes precedence.
  * @return void No return value. No files or shared components are loaded.
  */
 function ddw_siu_snippet_initialize() {
  if ( ! shortcode_exists( 'siu-item-updated' ) ) { new DDW_Shortcode_Item_Updated(); }
 }
 if ( did_action( 'init' ) ) { ddw_siu_snippet_initialize(); }
 else { add_action( 'init', 'ddw_siu_snippet_initialize', 10 ); }
}
'''
file='<?php\n'+body
(dest/'shortcode-item-updated-2.3.0.snippet.php').write_text(file)
(dest/'shortcode-item-updated-2.3.0-paste.txt').write_text(body)
cs={'generator':'Code Snippets compatible export — Shortcode Item Updated 2.3.0','date_created':'2026-10-08 00:00','snippets':[{'name':'Shortcode Item Updated 2.3.0','desc':'Flexible update dates for posts, pages and custom post types. Run everywhere. Plugin and snippet are alternative installations.','code':body,'tags':['shortcode','deckerweb'],'scope':'global','active':False,'priority':10}]}
(dest/'shortcode-item-updated-2.3.0.code-snippets.json').write_text(json.dumps(cs,ensure_ascii=False,indent=2)+'\n')
meta={'name':'Shortcode Item Updated 2.3.0','description':'Display update dates for selected WordPress content.','type':'PHP','status':'draft','run_at':'all','priority':10,'tags':'deckerweb,shortcode','group':'Content','condition':{'status':'no','run_if':'assertive','items':[]},'load_as_file':''}
fluent={'file_type':'fluent_code_snippets','version':'10.56','snippets':[{'code':base64.b64encode(file.encode()).decode(),'code_hash':hashlib.md5(file.encode()).hexdigest(),'info':meta}],'snippets_count':1}
(dest/'shortcode-item-updated-2.3.0.fluentsnippets.json').write_text(json.dumps(fluent,ensure_ascii=False,indent=2)+'\n')
