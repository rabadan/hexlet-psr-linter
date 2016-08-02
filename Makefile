install:
	composer install

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests'

test:
	composer exec phpunit -- -c phpunit.xml
	composer exec 'test-reporter'