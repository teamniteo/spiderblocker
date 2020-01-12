VERSION := 1.0.22
PLUGINSLUG := spiderblocker
SRCPATH := $(shell pwd)/src

bin/linux/amd64/github-release:
	wget https://github.com/aktau/github-release/releases/download/v0.7.2/linux-amd64-github-release.tar.bz2
	tar -xvf linux-amd64-github-release.tar.bz2
	chmod +x bin/linux/amd64/github-release
	rm linux-amd64-github-release.tar.bz2

ensure: vendor
vendor:
	composer install --dev
	composer dump-autoload -a

clover.xml: vendor test

unit: test

test: vendor
	bin/phpunit --coverage-html=./reports

build: ensure
	sed -i "s/@##VERSION##@/${VERSION}/" src/index.php
	sed -i "s/@##VERSION##@/${VERSION}/" src/i18n/spiderblocker.pot
	mkdir -p build
	cp -ar $(SRCPATH) $(PLUGINSLUG)
	zip -r $(PLUGINSLUG).zip $(PLUGINSLUG)
	rm -rf $(PLUGINSLUG)
	mv $(PLUGINSLUG).zip build/
	sed -i "s/${VERSION}/@##VERSION##@/" src/index.php
	sed -i "s/${VERSION}/@##VERSION##@/" src/i18n/spiderblocker.pot

release:
	git stash
	git fetch -p
	git checkout master
	git pull -r
	git tag v$(VERSION)
	git push origin v$(VERSION)
	git pull -r
	@echo "Go to the https://github.com/niteoweb/spiderblocker/releases/new?tag=v$(VERSION) and publish the release in order to build the package for distribution!"

fmt: ensure
	bin/phpcbf --standard=WordPress src
	bin/phpcbf --standard=WordPress tests --ignore=vendor

lint: ensure
	bin/phpcs --standard=WordPress src
	bin/phpcs --standard=WordPress tests --ignore=vendor

psr:
	composer dump-autoload -a

i18n:
	wp i18n make-pot src src/i18n/spiderblocker.pot

cover: vendor
	bin/coverage-check clover.xml 100

clean:
	rm -rf vendor/ bin
