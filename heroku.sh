#!/bin/sh

git add -A
git commit -m "a"
git push heroku master
heroku open