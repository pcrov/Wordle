Try to follow PSR-2, and pay attention to whatever PHPMD complains about.

## Tools

API documentation will be written out to doc/

phpcs and phpmd should stay quiet as long as you don't anger them

### Windows
- vendor\bin\phpunit.bat --configuration phpunit.xml.dist --coverage-text
- vendor\bin\phpcs.bat --standard=phpcs.xml
- vendor\bin\phpmd.bat src text phpmd.xml
- vendor\bin\phpdoc.bat

### Sensible Operating Systems
- vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-text
- vendor/bin/phpcs --standard=phpcs.xml
- vendor/bin/phpmd src text phpmd.xml
- vendor/bin/phpdoc