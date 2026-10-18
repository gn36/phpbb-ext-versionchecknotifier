# phpBB 3.3 Extension - Version Check Notifier
This extension will check for new versions of all extensions (including this one) and phpBB periodically and notifies the administrators about any available updates found. This uses the regular phpBB version check mechanism, so no configuration is needed.

Current features:

* Check the version of all extensions and notify administrators of any updates
* Check the version of phpBB and notify administrators of any updates

Requires phpBB 3.3.x and PHP 7.2 or newer (including PHP 8). For phpBB 3.1 use version 1.0.x.

## Installation

Clone into ext/gn36/versionchecknotifier:

    git clone https://github.com/gn36/phpbb-ext-versionchecknotifier ext/gn36/versionchecknotifier

Go to "ACP" > "Customise" > "Extensions" and enable the "gn36 - Version Check Notifier" extension.

## Development

If you find a bug, please report it on https://github.com/gn36/phpbb-ext-versionchecknotifier

## Automated Testing

Tests run via GitHub Actions using the [phpbb-extensions test framework](https://github.com/phpbb-extensions/test-framework) (see `.github/workflows/tests.yml`).

## License

[GPLv2](license.txt)
