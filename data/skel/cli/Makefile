# vim:set noexpandtab:

#
# This file is part of the '{{$vendor}}/{{$package}}' package.
#
# (c) {{$company}}
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#

#**
# Makefile for {{$vendor}}-{{$package}}.
#
# @octdoc       h:{{$package}}/Makefile
# @copyright    copyright (c) {{$year}} by {{$company}}
# @author       {{$author}} <{{$email}}>
#**

CURDIR := $(shell pwd)

DSTDIR := "/usr/local/bin"

help:
	@echo "make targets:"
	@echo "    install    creates single-executable '$(DSTDIR)/{{$package}}'"

install:
	@php -dphar.readonly=0 $(CURDIR)/phar/install.php $(DSTDIR)
