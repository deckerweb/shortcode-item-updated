# Ausführliche Anleitung

[English](USER-GUIDE.md) · [Readme](../README-de.md) · [Parametertabelle](../README-de.md#shortcode-parameter) · [Snippet-Installation](SNIPPETS-de.md)

## Erst die Quelle wählen, dann die Darstellung

Der Shortcode erledigt zwei getrennte Aufgaben: einen WordPress-Eintrag auswählen und dessen gespeicherten Änderungszeitpunkt formatieren. Platziere ihn in einem Bereich, der Shortcodes ausführt. Er ändert keine Veröffentlichungsdaten, speichert keine neuen Metadaten, prüft keine Dateien und blendet nichts automatisch ein.

Die ID findest Du im Bearbeitungsfenster eines Eintrags: In der Adresse steht beispielsweise `post=123`. Verwende diese Nummer als `post_id`. Alle Zahlen in den Beispielen sind Platzhalter. IDs gehören bei Multisite zur aktuellen Website; der Shortcode sucht nicht auf anderen Websites.

## Eine Inhaltsseite verweist auf einen Download

Die Inhaltsseite könnte ID 42 haben, der separate Download-Eintrag ID 123. Der Shortcode ohne ID würde auf der Inhaltsseite deren eigenen Stand anzeigen. Verweise deshalb ausdrücklich auf den Download:

```text
[siu-item-updated post_id="123" show_label="yes" label_before="Download aktualisiert:" date_format="de"]
```

Wurde der Download-Eintrag am 3. Juni 2020 geändert, erscheint **Download aktualisiert: 03.06.2020**. Änderungen an der Inhaltsseite beeinflussen die Quelle nicht. Eine ersetzte Datei verändert dieses Datum nur, wenn dabei auch der Änderungszeitpunkt des zugehörigen WordPress-Eintrags aktualisiert wird.

## Ein gemeinsamer Stand für mehrere Handbücher

Die IDs 123, 456 und 789 gehören zu drei Handbüchern. Auf der Übersicht soll eine Zeile zeigen, wann sich zuletzt eines davon geändert hat:

```text
[siu-item-updated post_ids="123,456,789" show_label="yes" label_before="Handbücher aktualisiert:" date_format="de" semantic="yes"]
```

Liegen die Änderungen am 3., 4. und 2. Juni, verwendet das Ergebnis den 4. Juni. Es erscheint keine Liste, kein Titel der ausgewählten Quelle und kein Link. Die Reihenfolge ist egal; doppelte IDs werden entfernt. Eine eigene Taxonomie-/Abfragesprache ist nicht enthalten.

Eine nicht leere `post_ids`-Liste hat Vorrang vor `post_id`. Erlaubt sind höchstens 100 kommagetrennte Einträge. Jeder muss eine positive ganze Dezimalzahl sein. `123, falsch` oder eine Liste mit abschließendem leeren Eintrag ist ungültig und bleibt ohne Ausgabe. IDs ohne verfügbaren Beitrag werden übersprungen. Sind alle Quellen einer gültigen Liste nicht verfügbar, bleibt sie ebenfalls ohne Ausgabe. So wird ein Tippfehler nicht stillschweigend als andere Quelle interpretiert.

## Speichern rund um die Veröffentlichung ausblenden

```text
[siu-item-updated post_id="123" only_if_updated="yes" min_update_gap="86400" show_label="yes"]
```

Die Änderung muss nach der Veröffentlichung liegen und mindestens 86.400 Sekunden später erfolgt sein. Genau ein Tag Abstand genügt. Bei Schwelle null zählt jeder spätere Zeitpunkt; identische Zeitpunkte zählen nie. Mit `only_if_updated="no"` unterdrückt die Schwelle keine gültige Ausgabe. Schwellenwerte müssen nicht negative ganze Sekunden sein.

Bei mehreren Quellen wird die Bedingung zuerst je Quelle angewendet:

```text
[siu-item-updated post_ids="123,456,789" only_if_updated="yes" min_update_gap="86400" show_label="yes" label_before="Handbücher aktualisiert:"]
```

Ein neu veröffentlichtes Handbuch ohne passende spätere Änderung wird übersprungen. Angezeigt wird der jüngste verbleibende zulässige Stand. Passt keiner, bleibt die komplette Ausgabe leer, einschließlich Beschriftungen und Hüllelement. Auch gewöhnliches späteres Speichern kann die Bedingung erfüllen: Die redaktionelle Bedeutung erkennt das Plugin nicht.

## Rezepte für Datum und Uhrzeit

Deutsches Datum mit Uhrzeit:

```text
[siu-item-updated post_id="123" date_format="de" show_time="yes" time_format="H:i" show_sep="yes" sep=", um" label_after="Uhr" show_label="yes"]
```

Beispiel: **Zuletzt aktualisiert: 03.06.2020, um 12:34 Uhr**.

Ausgeschriebener Monat:

```text
[siu-item-updated post_id="123" date_format="j. F Y" show_label="yes"]
```

Beispiel bei deutscher WordPress-Lokalisierung: **Zuletzt aktualisiert: 3. Juni 2020**.

Nur die Uhrzeit:

```text
[siu-item-updated post_id="123" show_date="no" show_time="yes" time_format="H:i" label_after="Uhr"]
```

Beispiel: **12:34 Uhr**. Das Trennzeichen erscheint nur bei gleichzeitig sichtbarem Datum und sichtbarer Uhrzeit. `label_after` erscheint ausschließlich nach einer absoluten Uhrzeit und ist kein allgemeiner Nachsatz. Sind beide Sichtbarkeitsparameter `no`, erscheint nichts.

Datums- und Zeitstandard stammen aus **Einstellungen → Allgemein**. Monatsnamen und Zeitspannen folgen der WordPress-Lokalisierung. Beschriftungen beachten mit WPML/Polylang die Seitensprache; sonst die Sprache der Website. Die Sprache Deines Administratorprofils bestimmt nicht die öffentliche Beschriftung. Für bewusst andere Formulierungen eigene Texte angeben.

Das historische Kürzel `us` bedeutet `Y-m-d`, also beispielsweise **2020-06-03**. Für denselben Wert gibt es die verständlichere Bezeichnung `iso`. Ein echtes US-Format Monat/Tag/Jahr lässt sich mit `date_format="m/d/Y"` festlegen.

## Relative Angaben und Caches

```text
[siu-item-updated post_id="123" display="relative" show_label="yes"]
```

Beispiel: **Zuletzt aktualisiert: vor 3 Tagen**. WordPress wählt näherungsweise Einheiten; das ist kein Countdown und keine sekundengenaue Zeitmessung. Ein zukünftiger Zeitpunkt erscheint als **in 3 Tagen**. Mindestens `show_date` oder `show_time` muss eingeschaltet bleiben. `date_format`, `time_format`, `sep` und `label_after` beeinflussen die relative Formulierung nicht.

Für den exakten Zeitpunkt zusätzlich semantisches Markup verwenden:

```text
[siu-item-updated post_id="123" display="relative" semantic="yes" show_label="yes"]
```

Die Zeitspanne wird beim Rendern in WordPress berechnet. Ein Seitencache kann alte Formulierungen festhalten. Außerdem erkennt er nicht zwingend, dass eine Änderung an einem referenzierten Beitrag den Cache der verweisenden Seite ungültig machen sollte. Leere deren Cache, verwende eine passende Laufzeit oder nutze Abhängigkeits-/Ausnahmefunktionen Deines Caches. Das Plugin installiert keine Cache-spezifische Löschintegration. Für stark gecachte Seiten sind absolute Angaben einfacher.

## HTML-Ausgabe und Gestaltung

```text
[siu-item-updated post_id="123" date_format="iso" semantic="yes" class="download-date muted" wrapper="div"]
```

Beispielausgabe auf einer Website mit Zeitzone Europe/Berlin:

```html
<div class="item-last-updated download-date muted"><time datetime="2020-06-03T12:34:00+02:00">2020-06-03</time></div>
```

`semantic` ist optional und standardmäßig `no`. Es ergänzt kein Stylesheet, JSON-LD, Meta-Tag oder Ranking-Versprechen. Der ISO-Wert enthält den Offset der Website-Zeitzone am jeweiligen Datum, einschließlich Sommerzeit.

Erlaubte Hüllelemente: `span`, `div`, `p`, `small`, `strong`, `em`, `time`, `li`, `dt`, `dd`, `figcaption`, `section`, `article`, `aside`, `footer`, `header` sowie `h1`–`h6`. Nicht unterstützte Werte fallen auf `span` zurück. Wähle ein im umgebenden Markup gültiges Element; `li` braucht beispielsweise eine passende Liste. Bei `time` erhält die Hülle selbst `datetime`, ohne verschachteltes `time`. Beschriftungen, Formate und Trennzeichen sind Text, keine Möglichkeit zum Einfügen von HTML.

Eigenes CSS im Theme oder Builder könnte so aussehen:

```css
.download-date { font-size: .875rem; }
.download-date.muted { color: #515b66; }
```

Die bestehende Klasse `.item-last-updated` bleibt im HTML-Modus erhalten. Klassen werden einzeln bereinigt; Duplikate werden entfernt. Das Plugin ergänzt keine Frontend-Gestaltung.

## Reiner Text und PHP-Templates

```text
[siu-item-updated post_id="123" output="text" date_format="iso"]
```

Beispiel: **2020-06-03**, ohne HTML-Hülle. Im Textmodus wirken `wrapper`, `class` und `semantic` nicht. Beschriftungen funktionieren weiterhin; Markup wird aus der Textausgabe entfernt. Es bleibt eine Datumsformatierung, keine API für rohe Unix-Zeitstempel.

Normale sichere HTML-Ausgabe in einem PHP-Template:

```php
echo do_shortcode( '[siu-item-updated post_id="123" show_label="yes" semantic="yes"]' );
```

`do_shortcode()` gibt einen String zurück; für die Anzeige wird `echo` benötigt. Reinen Text passend zum Zielkontext maskieren:

```php
$updated = do_shortcode( '[siu-item-updated post_id="123" output="text" date_format="iso"]' );
echo esc_html( $updated ); // Sichtbarer Text.
// Für einen Wert in einem HTML-Attribut esc_attr( $updated ) verwenden.
```

## Builder, Loops und gemeinsame Templates

Verwende das Shortcode-Element Deines Builders statt einer Anzeige für unverarbeiteten Text oder Code. Im Query Loop stammt die Standard-ID aus `get_the_ID()`. Das funktioniert auch in einem Builder-Loop, der den aktuellen WordPress-Beitrag korrekt setzt. Archivüberschriften, Footer und andere Templates können keinen oder einen unerwarteten aktuellen Beitrag haben; gib dort `post_id` an. Das Plugin errät keine Archiv-, Template- oder fremde Website-Quelle.

Im WordPress-Editor genügt der vorhandene Shortcode-Block. Dieses Plugin registriert keinen Gutenberg-Block. Ein Builder-Feld für Text kann `output="text"` benötigen; es muss den Shortcode trotzdem ausführen. Eine eigene native Dynamic-Data-Integration wird nicht installiert.

## Bestehende Filter

Standardwerte für die Website ändern; ausdrücklich im Shortcode gesetzte Werte haben weiterhin Vorrang:

```php
add_filter( 'siu_filter_shortcode_defaults', function ( $defaults ) {
    $defaults['semantic'] = 'yes';
    $defaults['date_format'] = 'iso';
    return $defaults;
} );
```

Die fertige Ausgabe verändern:

```php
add_filter( 'siu_filter_shortcode_item_updated', function ( $output, $atts ) {
    if ( 'text' === strtolower( $atts['output'] ) ) {
        return $output;
    }
    return '<div class="update-note">' . $output . '</div>';
}, 10, 2 );
```

Vertrauenswürdige Filter müssen eigene Ergänzungen passend maskieren. Der WordPress-Filter `shortcode_atts_siu-item-updated` bleibt verfügbar. Dort ergänzte eigene Attribute erreichen weiterhin den Ausgabefilter. Es gibt keinen neuen eigenen Hook. Bei nicht verfügbarer Quelle wird vor dem Ausgabefilter ein leerer String zurückgegeben; der Filter umgeht keine Sichtbarkeitsregeln.

## Umstellung von 2.2.0

- PHP-Mindestversion des Plugins jetzt 8.0; eigenständiges Snippet weiterhin 7.4. WordPress-Mindestversion bleibt 6.7.
- Shortcode-Name, ursprüngliche Attribute, CSS-Klasse und bestehende Filter bleiben erhalten.
- `de`/`us` verwenden korrekt den Quellzeitpunkt statt des heutigen Datums.
- Mit `show_label="yes"` erscheint die Standardbeschriftung korrekt.
- Ungültige Quellen liefern kein irreführendes aktuelles Datum oder Epochendatum.
- Daten privater, passwortgeschützter, nicht öffentlicher Inhalte und Entwürfe werden auch angemeldeten Administratoren nicht angezeigt. Gecachte öffentliche Ausgabe bleibt damit unabhängig von Nutzerrechten.
- Mehrere Klassen funktionieren; nicht unterstützte Hüllelemente fallen auf `span` zurück.
- Trennzeichen und Uhrzeitzusätze benötigen die entsprechenden sichtbaren Komponenten.
- Keine Einstellungsmigration, neue Datenbankstruktur oder Inhaltsumschreibung.

## Multisite und Lebenszyklus

Aktivierung je Website und Netzwerkaktivierung werden unterstützt. IDs, Sprache, Formate und Zeitzone bleiben je Website getrennt. `post_ids` verbindet keine Websites. Der Shortcode besitzt keine dauerhaften Einstellungen oder geplanten Aufgaben.

Das installierbare Plugin bringt gemeinsam genutzte Library-Daten und einen GitHub-Updatecache mit. Deaktivierung erhält sie. Deinstallation leert den Cache dieses Hosts und delegiert Library-Cleanup: Ein anderer physisch installierter Host, auch inaktiv, schützt gemeinsame Daten. Beim letzten Host werden temporäre Daten bereinigt; Library-Einstellungen bleiben erhalten, sofern die ausdrückliche Löschoption nicht aktiviert ist. Installierte Plugins und Inhalte werden dabei nie entfernt. Siehe [Dateninventar](DATA-de.md).
