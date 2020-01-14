# phpBB 3.2/3.3 Extension - Version Check Notifier
This extension will check for new versions of all extensions (including this one) and phpBB periodically and notifies the administrators about any available updates found. This uses the regular phpBB version check mechanism, so no configuration is needed.

Current features:

* Check the version of all extensions and notify administrators of any updates
* Check the version of phpBB and notify administrators of any updates


## Installation

Clone into ext/gn36/versionchecknotifier:

    git clone https://github.com/gn36/phpbb-ext-versionchecknotifier ext/gn36/versionchecknotifier

Go to "ACP" > "Customise" > "Extensions" and enable the "gn36 - Version Check Notifier" extension.

## Development

If you find a bug, please report it on https://github.com/gn36/phpbb-ext-versionchecknotifier

## Automated Testing

We will use automated unit tests including functional tests to prevent regressions. This will be updated with our travis build once it is working:

master: [![Build Status](https://travis-ci.org/gn36/phpbb-ext-versionchecknotifier.png?branch=master)](http://travis-ci.org/gn36/phpbb-ext-versionchecknotifier)

## License

[GPLv2](license.txt)
