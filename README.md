# Format Microsoft Protected Links module

Module for [ProcessWire CMS](https://www.processwire.com/)  
Copyright (c) 2026 Commonwealth of Australia

This module will check the selected fields for [Microsoft Protected Links](https://learn.microsoft.com/en-us/defender-office-365/safe-links-about) on saving the page and convert them to their original URL, preventing unintentional leaking of internal email addresses and other data.

## Requirements

- PHP 7.3+
- Processwire 3.X

## Installing

This module is installed just like any other ProcessWire module: copy or clone the directory containing this module to your /site/modules/ directory, log in, go to Admin > Modules, click "Check for new modules", and install "Replace Outlook Protected Links".

## How to use

After installing this module, go to the modules configuration and select which fields you would like to hook on save to be checked for Microsoft protected Links.

When a page is saved with a selected field, it will now check for Protected links within the `href` attribute of anchor (`<a>`) elements and replace them with the original URL.