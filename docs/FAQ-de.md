# Fragen nach Themen

[English](FAQ.md) · [Anleitung](USER-GUIDE-de.md)

## Einstieg

### Welche Inhalte kann ich verwenden?

Veröffentlichte, öffentlich sichtbare Beiträge, Seiten und Custom-Post-Type-Einträge ohne Passwort. Entwürfe, private Inhalte, Revisionen, gelöschte Beiträge und nicht öffentliche Beitragstypen werden für alle übersprungen, auch für Administratoren.

### Welches Datum wird angezeigt?

Der in WordPress gespeicherte Änderungszeitpunkt, formatiert in der Zeitzone der Website. Das Plugin verfolgt keine Änderungen an einer hochgeladenen PDF-Datei, prüft keine externen Dateien und unterscheidet keine redaktionellen Änderungen von gewöhnlichem Speichern.

### Wie verweise ich auf einen anderen Eintrag?

Mit `post_id="123"` oder mit `post_ids="123,456,789"` für den jüngsten zulässigen Stand. Ersetze die Beispiel-IDs durch IDs Deiner Website. Außerhalb eines Beitrags-Loops immer eine Quelle angeben.

### Warum wird nichts angezeigt?

Prüfe ID, öffentliche Sichtbarkeit, Änderungszeitpunkt sowie `only_if_updated` und den Mindestabstand. Fehlerhafte ID-Listen und ungültige Schwellenwerte bleiben ohne Ausgabe. Sind Datum und Uhrzeit beide ausgeschaltet, erscheint ebenfalls nichts.

### Funktioniert das mit meinem Builder?

Verwende das Shortcode-Element des Builders oder ein Feld, das WordPress-Shortcodes tatsächlich ausführt. Ein Feld, das den Shortcode als gewöhnlichen Text behandelt, rendert ihn nicht. Im Block-Editor genügt der vorhandene Shortcode-Block; dieses Plugin ergänzt keinen Gutenberg-Block.

### Kann ich stattdessen das Snippet verwenden?

Ja. Es bietet dieselben Shortcode-Funktionen und unterstützt PHP 7.4. Verwende genau eine Installationsart. Das eigenständige Snippet enthält keine dateibasierte Library, keinen Plugin-Updater und keine Übersetzungskataloge; Updates erfolgen manuell.

### Warum wirken relative Angaben oder Daten anderer Einträge veraltet?

Seitencaches speichern die fertig gerenderte Ausgabe. Relative Angaben altern, und die Änderung eines referenzierten Eintrags leert nicht zwingend den Cache der anzeigenden Seite. Leere deren Cache oder nutze die Abhängigkeits-/Ausnahmefunktionen Deines Caches. Absolute Ausgabe bleibt der Standard.

## Im Alltag

### Prüft das Plugin eine PDF-Datei oder einen externen Download?

Nein. Es verwendet den Änderungszeitpunkt des gewählten WordPress-Eintrags. Eine ersetzte Datei erscheint nur dann als neuer Stand, wenn sich auch dieser Zeitstempel ändert.

### Beeinflusst es SEO oder Veröffentlichungsdaten?

Nein. Semantische Ausgabe ergänzt time mit datetime. Es gibt keine automatische Einblendung, Schema-/JSON-LD-Erzeugung oder Änderung gespeicherter Daten.

## Darstellung und Integration

### Woher kommen Zeitzone und Formate?

Aus Einstellungen → Allgemein der aktuellen Website. Datum/Zeit lassen sich je Shortcode formatieren; die Website-Zeitzone bleibt maßgeblich.

### Kann ich mehrere Quellen mit einem Mindestabstand kombinieren?

Ja. Die Schwelle wird je Quelle geprüft, bevor der jüngste zulässige Stand ausgewählt wird. Ein neu veröffentlichter unveränderter Eintrag unterdrückt keinen älteren passenden Stand.

### Ist das historische us-Format Monat/Tag/Jahr?

Nein: Es bedeutet Y-m-d und bleibt kompatibel erhalten. iso bezeichnet dasselbe Format; m/d/Y liefert ein numerisches US-Datum.

### Kann ich HTML in Beschriftungen oder Trennzeichen einfügen?

Nein. Das sind Textwerte. HTML-Ausgabe maskiert sie; Textausgabe entfernt Tags. Für eigene Strukturen den vertrauenswürdigen Ausgabefilter verwenden und Ergänzungen maskieren.

## Verwaltung

### Gibt es eine Einstellungsseite oder einen neuen Editor-Block?

Nein. Shortcode-Attribute steuern jede Ausgabe. Die mitgelieferte Library besitzt eigene gemeinsame Verwaltungsfunktionen; sie konfigurieren nicht diesen Shortcode.

### Was passiert bei Multisite und Deinstallation?

IDs und Darstellungseinstellungen gehören zur aktuellen Website. Netzwerkaktivierung funktioniert ohne Datenkopien auf jeder Website. Der Shortcode speichert nichts; Komponenten-Cleanup steht in DATA-de.md.

### Aktualisiert sich das eigenständige Snippet automatisch?

Nein. Ersetze den Code manuell. GitHub-Updates und Library gehören zum installierbaren Plugin. Die Speicherdaten des Managers gehören diesem Manager.
