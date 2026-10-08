# Funktionsprüfungen

[English](README.md)

Nur in einer isolierten wegwerfbaren WordPress-Installation ausführen. Die Prüfungen erstellen/löschen Testbeiträge, ändern Formate/Sprache, setzen Updateangebote und erstellen Manager-Datensätze. Niemals auf einer Produktivwebsite ausführen. WP-CLI eval-file mit der gewählten Testinstallation verwenden.

shortcode.php prüft den gemeinsamen Funktionskern anhand echter Beitragsdaten. components.php prüft Komponentenwahl, Sprachressourcen und Paketidentität/Version/Anforderungen. network-isolation.php braucht eine isolierte Multisite-Installation. Manager-Importprüfungen erfordern separat installierte rechtmäßig bezogene Manager; kein Manager-Code wird mitgeliefert. build-snippets.py erzeugt die importierten Dateien. SIU_TEST_SNIPPET_DIR kann auf ein anderes generiertes Snippet-Verzeichnis zeigen.

Die mit 2.3.0 übergebenen Testergebnisse nennen tatsächliche Umgebungen und verbleibende Grenzen.
