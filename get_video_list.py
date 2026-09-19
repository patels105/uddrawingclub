#!/bin/env python
import requests
import os
import sys
import re
import sqlite3


if not len(sys.argv) > 1:
    print("Usage: get_video_list.py [database]")
    exit()

database_name = sys.argv[1]

if not os.path.isfile(database_name):
    print("Error: [database] must be a file")
    exit()


db = sqlite3.connect(database_name)

playlist = db.execute("SELECT videoid, datetime(timestamp, 'unixepoch') from Playlist").fetchall()


for song in playlist:
    videoid, submittime = song

    if not submittime:
        submittime = "[No submit time]   "

    url = "https://www.youtube.com/watch?v=%s" % (videoid)

    title = None
    try:
        response = requests.get(url)
        title = "\"%s\"" % (re.search("<title>(.*)</title>", response.text).group(1))
    except:
        title = "[No title found]"

    print(url, submittime, title)
