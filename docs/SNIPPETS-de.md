# Eigenständiges Snippet installieren

[English](SNIPPETS.md) · [Anleitung](USER-GUIDE-de.md)

Das Snippet bietet alle Shortcode-Funktionen der Version 2.3.0, benötigt WordPress 6.7 und PHP 7.4 und enthält denselben Funktionskern wie das Plugin. Es ist eigenständig. Plugin-Pfade, mitgelieferte Library, Updater, Plugin-Zeilenlinks oder Übersetzungskataloge werden nicht benötigt. Deutsche Standardbeschriftungen funktionieren ohne Plugin-Sprachkatalog; Monatsnamen und Zeitspannen verwenden die WordPress-Sprachressourcen.

Verwende **eine** Installationsart. Ein älteres Snippet beziehungsweise das installierte Plugin vor dem Aktivieren deaktivieren. Bei versehentlicher Überlappung hat das installierte Plugin beim Shortcode Vorrang; eine doppelte Installation ist trotzdem nicht vorgesehen. Eine bereits aus einem anderen Snippet geladene alte Klasse kann innerhalb desselben Aufrufs nicht ersetzt werden.

## Code Snippets

1. Unter **Snippets → Importieren** die Datei `shortcode-item-updated-2.3.0.code-snippets.json` aus dem separaten Snippet-Paket wählen.
2. Importiertes PHP-Snippet prüfen. **Überall ausführen** wählen.
3. Speichern und aktivieren. Das JSON lässt das Snippet zur Prüfung zunächst inaktiv.
4. `[siu-item-updated post_id="123" show_label="yes"]` in eine Seite mit Shortcode-Ausführung einfügen; 123 durch eine öffentliche Beitrags-ID ersetzen.

Manuelle Alternative: PHP-Snippet erstellen und den gesamten Inhalt von `shortcode-item-updated-2.3.0-paste.txt` einfügen. Diese Datei enthält keinen öffnenden PHP-Tag.

## Advanced Scripts (Premium)

In Advanced Scripts 2.6.2 funktioniert derselbe Code-Snippets-Import:

1. Unter **Werkzeuge → Advanced Scripts → Import** die Datei `shortcode-item-updated-2.3.0.code-snippets.json` wählen.
2. Import zunächst inaktiv lassen. Der Import richtet **PHP**, **Everywhere** und **plugins_loaded** mit Priorität 1 ein.
3. Script prüfen, speichern und aktivieren.

Manuell: Neues PHP-Script erstellen und den vollständigen Inhalt von `shortcode-item-updated-2.3.0.snippet.php` einfügen, **einschließlich genau eines öffnenden PHP-Tags**. Ein im Editor bereits vorhandenes `<?php` ersetzen, nicht verdoppeln. Alternativ den Body aus `-paste.txt` unter den vorhandenen Tag setzen. **Everywhere**, **plugins_loaded**, Priorität 1 oder 10 und keine einschränkenden Bedingungen verwenden. Nicht die Ausführung als manager-eigenen Shortcode wählen: Unser Script registriert seinen eigenen `[siu-item-updated]`.

Der Shortcode wird auf `init` oder bei späterem Laden sofort registriert. Das Script muss vor der Inhaltsausgabe ausgeführt werden. Das bereitgestellte Premium-Paket wurde als Testumgebung verwendet; kein Premium-Code ist Teil dieser Ausgabe.

## FluentSnippets

1. Neues Snippet vom Typ **PHP** erstellen.
2. Gesamten Inhalt von `shortcode-item-updated-2.3.0-paste.txt` ohne öffnenden PHP-Tag einfügen.
3. **Überall ausführen / Run everywhere** wählen und Bedingungen ausgeschaltet lassen.
4. Speichern und aktivieren/veröffentlichen.

Ein Funktionen-/PHP-Snippet verwenden, kein Mixed-Content- oder On-Demand-Snippet. FluentSnippets ergänzt beim Speichern selbst den öffnenden PHP-Tag. Die separat enthaltene native FluentSnippets-JSON-Datei lässt sich über Import einlesen; sie wird zur Prüfung inaktiv/als Entwurf importiert.

## Welche Datei wofür?

- `.snippet.php`: vollständiger PHP-Quelltext mit öffnendem Tag; für dateibasierte Einbindung.
- `-paste.txt`: derselbe Code ohne diesen Tag; für Code Snippets/FluentSnippets oder unter dem vorhandenen PHP-Tag in Advanced Scripts.
- `.code-snippets.json`: nativer Code-Snippets-Import, ein inaktives globales PHP-Snippet.
- `.fluentsnippets.json`: nativer FluentSnippets-Import, ein PHP-Snippet mit Ausführung überall.

Nicht die Hauptdatei des installierbaren Plugins einfügen: Sie lädt Dateien, die das eigenständige Snippet nicht hat. Verwende stattdessen die bereitgestellte Snippet-Fassung.

## Updates und Entfernen

Updates erfolgen manuell. Den bisherigen Snippet-Code ersetzen, statt eine zweite aktive Kopie hinzuzufügen. Bei eigenen Anpassungen zuerst exportieren. Das Snippet im Manager deaktivieren/entfernen; der Shortcode erzeugt keine dauerhaften Daten oder geplanten Aufgaben. Seiteninhalte bleiben erhalten und enthalten weiterhin den Shortcode-Text, bis Du ihn entfernst oder ersetzt.

Der Manager verwaltet seine eigenen Speicher-, Export- und Löschdaten. Diese gehören nicht zum Lebenszyklus dieses Snippets.
