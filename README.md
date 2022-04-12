# IVMDCMS - InuitViking MarkDown Content Management System

A "CMS" to handle markdown files. Currently, there is no administration interface, but that may appear at a later time.

It's a stand-in replacement for some other similar CMS' I have tried, as the others weren't entirely satisfactory.

Either they didn't work fully, or they barely worked.
The ones I have tried so far (without mentioning anyone; don't want to give them a bad rep regardless) have had the following issues:
 - Awkward to host
 - Nodejs application that exited after a few days of running.
 - Nodejs application that was inconvenient to upgrade.
 - Nodejs application where login would stop working after a day
   - It would only work again after a full redeploy.
 - Couldn't be run with pm2 if it was a nodejs application
 - PHP application that wouldn't read the markdown files
 - PHP application that was incredibly slow
 - PHP application that didn't make sense whatsoever.

This one will make sense, at least for me, which is the important part.

The plan is to expand the markdown beyond commonmark

## Goals
 - [x] Create a suitable markdown display tool.
 - [x] Add configuration (PHP has built in INI support, so this was used).
 - [x] Add a functioning search bar with Ajax.
 - [x] Add highlight.js for syntax highlighting.
 - [ ] Implement an administration interface.
 - [ ] Clean up CSS.
 - [ ] Clean up JS.
 - [ ] Optimise some functionality (PHP).
 - [ ] CSS aggregation / caching.
 - [ ] Create administration interface.
 - [ ] Use ini file for secret salts
 - [ ] Use separate file for users and passwords.
 - [ ] User permissions?
 - CLI
   - [x] Create a CLI script (It's not fantastic, but it's a start).
   - [x] Make it possible to clear HTML cache entirely.
   - [ ] Make it possible to clear cache for one single page.
   - [ ] Make it possible to clear CSS cache.
   - [ ] Make it possible to generate cache in bulk.
   - [ ] Make it possible to generate CSS cache.
   - [ ] Make it possible to generate secret salt.
   - [ ] Make it possible to generate hashed passwords. 
      - [ ] As taking an argument.
      - [ ] If no argument, ask for a password.
      - [ ] Option to auto-generate password.
   - [ ] Make it possible to create a user and add it to secret file.

## Dependencies
- [league/commonmark](https://github.com/thephpleague/commonmark)
- [scssphp/scssphp](https://scssphp.github.io/scssphp/)
- [editor.md](https://github.com/pandao/editor.md) (not yet, but plan to)

## Notes for dev
### How to release
Remember the version convention: year.month.day[.patch].

Create a tag:
```bash
git tag v[VERSION]
```

Go to [Releases](https://gitlab.com/InuitViking/ivmdcms/-/releases) and edit your release with the archives you've created with the following commands:
```bash
tar -zcvf ivmdcms-22.04.12.tar.gz --exclude="ivmdcms/.docksal" --exclude="ivmdcms/.git" --exclude="ivmdcms/.idea" --exclude="ivmdcms/.gitignore" --exclude="ColourPalette.png" --exclude="vendor" ivmdcms/
zip -r ivmdcms-22.04.12.zip ivmdcms -x "ivmdcms/.docksal/*" -x "ivmdcms/.git/*" -x "ivmdcms/.idea/*" -x ivmdcms/.gitignore -x ivmdcms/ColourPalette.png -x "ivmdcms/vendor/*"
```