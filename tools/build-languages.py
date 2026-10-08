"""Compile the maintained host-domain EN/Du/Sie source; requires GNU gettext."""
from pathlib import Path
import json,subprocess
r=Path(__file__).resolve().parents[1];messages=json.loads((r/'docs/translations.json').read_text())
def q(s):return json.dumps(s,ensure_ascii=False)
for lang in ['de_DE','de_DE_formal']:
 header='msgid ""\nmsgstr ""\n'+''.join(q(x)+'\n' for x in ['Project-Id-Version: Shortcode Item Updated 2.3.0\n','Language: '+lang+'\n','Content-Type: text/plain; charset=UTF-8\n','Content-Transfer-Encoding: 8bit\n','Plural-Forms: nplurals=2; plural=(n != 1);\n'])+'\n'
 for m in messages:
  if m['context']:header+='msgctxt '+q(m['context'])+'\n'
  header+='msgid '+q(m['id'])+'\nmsgstr '+q(m[lang])+'\n\n'
 p=r/'languages'/('shortcode-item-updated-'+lang+'.po');p.write_text(header)
 subprocess.run(['msgfmt','--check-format',str(p),'-o',str(p.with_suffix('.mo'))],check=True)
subprocess.run(['msgattrib','--empty',str(r/'languages/shortcode-item-updated-de_DE.po'),'-o',str(r/'languages/shortcode-item-updated.pot')],check=True)

pot=r/'languages/shortcode-item-updated.pot'
pot.write_text(pot.read_text().replace('Language: de_DE\\n','Language: \\n'))
