"""Derive all sizes from approved native vector masters, without recomposition."""
from pathlib import Path
import re
root=Path(__file__).resolve().parents[1]
for lang in ['de','en']:
    master=(root/'tools'/f'banner-source-{lang}.svg').read_text()
    for name,w,h,vb in [('banner-github',1280,640,'0 0 1280 640'),('banner',772,250,'0 112.7461139896 1280 414.5077720208'),('banner-high',1544,500,'0 112.7461139896 1280 414.5077720208')]:
        svg=re.sub(r'width="2560" height="1280" viewBox="0 0 1280 640"',f'width="{w}" height="{h}" viewBox="{vb}"',master,count=1)
        (root/'assets'/f'{name}-{lang}.svg').write_text(svg)
(root/'assets'/'icon.svg').write_text((root/'tools'/'icon-source.svg').read_text())
