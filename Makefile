VERSION := 1.3.5
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

dist: ensure
	sed -i "s/@##VERSION##@/${VERSION}/" src/index.php
	sed -i "s/@##VERSION##@/${VERSION}/" src/i18n/$(PLUGINSLUG).pot
	mkdir -p dist
	cp -r $(SRCPATH)/. dist/
	sed -i "s/${VERSION}/@##VERSION##@/" src/index.php
	sed -i "s/${VERSION}/@##VERSION##@/" src/i18n/$(PLUGINSLUG).pot

build: ensure
	sed -i "s/@##VERSION##@/${VERSION}/" src/index.php
	sed -i "s/@##VERSION##@/${VERSION}/" src/i18n/$(PLUGINSLUG).pot
	mkdir -p build
	cp -ar $(SRCPATH) $(PLUGINSLUG)
	zip -r $(PLUGINSLUG).zip $(PLUGINSLUG)
	rm -rf $(PLUGINSLUG)
	mv $(PLUGINSLUG).zip build/
	sed -i "s/${VERSION}/@##VERSION##@/" src/index.php
	sed -i "s/${VERSION}/@##VERSION##@/" src/i18n/$(PLUGINSLUG).pot

release:
	git stash
	git fetch -p
	git checkout master
	git pull -r
	git tag v$(VERSION)
	git push origin v$(VERSION)
	git pull -r

fmt: ensure
	bin/phpcbf --standard=WordPress src
	bin/phpcbf --standard=WordPress tests --ignore=vendor

lint: ensure
	bin/phpcs --standard=WordPress src
	bin/phpcs --standard=WordPress tests --ignore=vendor

psr:
	composer dump-autoload -o

i18n:
	wp i18n make-pot src src/i18n/$(PLUGINSLUG).pot

cover: vendor
	bin/coverage-check clover.xml 90

clean:
	rm -rf vendor/ bin
