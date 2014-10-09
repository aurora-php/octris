#**
# Makefile for calling installer script. Modify the DSTDIR variable below
# to install in an other directory.
#
# @octdoc       h:octris/Makefile
# @copyright    copyright (c) 2014 by Harald Lapp
# @author       Harald Lapp <harald@octris.org>
#**

CURDIR := $(shell pwd)

DSTDIR := "/usr/local/bin"

help:
	@echo "make targets:"
	@echo "    install    creates single-executable '$(DSTDIR)/octris'"
	
install:
	@php -dphar.readonly=0 $(CURDIR)/phar/install.php $(DSTDIR)
