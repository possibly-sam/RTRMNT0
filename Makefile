
CWD := $(shell pwd) 
M ?=week-nn

all:
	echo hi.

help:
	grep  ":" Makefile

run:  
	docker run --rm -v $$(pwd):/data phpdoc/phpdoc

install:
	docker pull phpdoc/phpdoc

push:
	git add .
	git commit -m "$(M)"
	git push
