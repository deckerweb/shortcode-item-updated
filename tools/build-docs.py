"""Generate the maintained EN/DE documentation and local history from one content source."""
from pathlib import Path
import json
r=Path(__file__).resolve().parents[1]
c=json.loads((r/'docs/content.json').read_text())
for name,text in c['documents'].items():
 p=r/name;p.parent.mkdir(parents=True,exist_ok=True);p.write_text(text)
(r/'includes/history.json').write_text(json.dumps(c['history'],ensure_ascii=False,indent=2)+'\n')
