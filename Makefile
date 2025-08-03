
CWD := $(shell pwd) 

all:
	echo hi.

help:
	grep  ":" Makefile

run:  
	docker run --rm -v $$(pwd):/data phpdoc/phpdoc

install:
	docker pull phpdoc/phpdoc
