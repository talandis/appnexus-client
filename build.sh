#!/usr/bin/env bash

composer install -vvv --profile

echo "### Appnexus client: Code style checker ###"

./bin/phpcs -p --standard=PSR2 --warning-severity=6 --colors src/
if [ $? != 0 ]
	then
	    echo "There are some code style issue."
	    echo "###Tidyng up the code ###"
	    ./bin/phpcbf -p --standard=PSR2 --warning-severity=6 src/
	    echo "DONE, retry the code should be fine."
		exit 1
	fi

echo "### Appnexus client: DONE! ###"

./bin/phpstan analyse -c phpstan.neon src/ --autoload-file=vendor/autoload.php
./bin/phpstan analyse -c phpstan.relaxed.neon tests/ --autoload-file=vendor/autoload.php

bin/phpunit --testsuite=unit
bin/paraunit run
