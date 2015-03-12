VERSION := 1.0.2

release:
	cp -ar src spider_blocker
	zip -r spider_blocker.zip spider_blocker
	rm -rf spider_blocker
	mv spider_blocker.zip build/

deploy:
	-bin/linux/amd64/github-release delete -u niteoweb -r spiderblocker -t v$(VERSION)
	-bin/linux/amd64/github-release delete -u niteoweb -r spiderblocker -t latest
	bin/linux/amd64/github-release release -u niteoweb -r spiderblocker -t v$(VERSION)
	bin/linux/amd64/github-release release -u niteoweb -r spiderblocker -t latest
	bin/linux/amd64/github-release upload -u niteoweb -r spiderblocker -t v$(VERSION) -f build/spider_blocker.zip -n spider_blocker.zip
	bin/linux/amd64/github-release upload -u niteoweb -r spiderblocker -t latest -f build/spider_blocker.zip -n spider_blocker.zip
