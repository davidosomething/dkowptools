# DKO WP Tools

> WordPress development tools

## Development

The actual plugin is in `plugin/`
The screenshots for the WP plugins repo are in `assets/`
Run `grunt` to lint and watch files.

## Publishing 

`grunt release` will compile and copy over the files to the proper SVN folder
structure (with trunk and assets) into the `release` folder. Copy the contents
of the release folder to the SVN repo and commit it to WP's SVN repo to publish.
