# Data and lifecycle

[Deutsch](DATA-de.md)

The shortcode reads the current site's existing post records, locale, date/time formats and timezone. It stores no settings, user data, content, revisions, dependency lists or scheduled tasks. No external request is needed to render it.

The GitHub updater stores a site/network transient named ddw_ghru_ followed by a repository-derived digest. It contains release metadata, not credentials or translated UI text. GitHub receives normal HTTP connection metadata for update checks and package downloads. No site-owner details are added to URLs.

Library 0.8.1 shares settings, introduction status, host/installation ownership, optional online catalog caches and temporary installation resources with other installed Library hosts. The online catalog starts disabled. Enabling it contacts the documented raw GitHub catalog; installing plugins contacts their approved package sources. No telemetry is sent. Library controls explain their scopes; per-site and network state remain separate. Its released lifecycle implementation inventories and cleans temporary resources.

Deactivation keeps data. Uninstall removes only this host's updater transient, including the known key in each network of a multi-network installation. Library cleanup is delegated to protocol 3. An installed inactive host still protects shared data. When this is the last physical host, temporary data is removed; Library settings remain unless its explicit delete-settings option is enabled. Installed plugins, posts and foreign data are never deleted.

The standalone snippet contains no shared components or updater cache. Its manager controls snippet records and exports. Removing it does not edit content that contains the shortcode.
