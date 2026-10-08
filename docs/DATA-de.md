# Daten und Lebenszyklus

[English](DATA.md)

Der Shortcode liest vorhandene Beitragsdaten, Sprache, Datums-/Zeitformate und Zeitzone der aktuellen Website. Er speichert keine Einstellungen, Nutzerdaten, Inhalte, Revisionen, Abhängigkeitslisten oder geplanten Aufgaben. Zum Rendern wird keine externe Verbindung benötigt.

Der GitHub-Updater speichert einen Website-/Netzwerk-Transient mit dem Namen ddw_ghru_ und einem vom Repository abgeleiteten Hash. Enthalten sind Release-Metadaten, keine Zugangsdaten oder übersetzten Oberflächentexte. Bei Updateprüfungen und Paketdownloads erhält GitHub übliche HTTP-Verbindungsmetadaten. Website-Inhaberdaten werden nicht an URLs angehängt.

Library 0.8.1 teilt Einstellungen, Einführungsstatus, Host-/Installationszuordnung, optionale Online-Katalogcaches und temporäre Installationsressourcen mit anderen installierten Library-Hosts. Der Online-Katalog ist anfangs ausgeschaltet. Aktivieren kontaktiert den dokumentierten Raw-GitHub-Katalog; Plugin-Installation deren freigegebene Paketquellen. Keine Telemetrie. Library-Einstellungen erklären ihren Geltungsbereich; Website- und Netzwerkdaten bleiben getrennt. Die veröffentlichte Lebenszyklusimplementierung inventarisiert und bereinigt temporäre Ressourcen.

Deaktivierung erhält Daten. Deinstallation entfernt ausschließlich den Update-Transient dieses Hosts, einschließlich des bekannten Schlüssels je Netzwerk einer Installation mit mehreren Netzwerken. Library-Cleanup wird an Protokoll 3 delegiert. Ein installierter inaktiver Host schützt gemeinsame Daten weiterhin. Beim letzten physischen Host werden temporäre Daten entfernt; Library-Einstellungen bleiben ohne aktivierte ausdrückliche Löschoption erhalten. Installierte Plugins, Beiträge und fremde Daten werden nie gelöscht.

Das eigenständige Snippet enthält keine gemeinsamen Komponenten oder Updatecaches. Sein Manager verwaltet Snippet-Datensätze und Exporte. Entfernen verändert keine Inhalte mit Shortcodes.
