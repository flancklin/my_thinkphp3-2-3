#!/usr/bin/env bash
cd /var/www/html/my_thinkphp3-2-3
git pull origin master
rm -rf /var/www/html/my_thinkphp3-2-3/code/Application/Runtime/*
