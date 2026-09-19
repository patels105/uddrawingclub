#!/bin/env python

import sqlite3
import sys
import os


query_videos_to_remove = """
SELECT * FROM Playlist WHERE videoid NOT IN (
    SELECT videoid FROM (
        SELECT videoid FROM Playlist WHERE Flagged=FALSE ORDER BY timestamp DESC LIMIT :count
    )
    UNION
    SELECT videoid FROM Playlist WHERE Flagged=TRUE
);
"""

query_delete_video = """
DELETE FROM Playlist WHERE videoid=:videoid
"""


if not len(sys.argv) > 2:
    print("Usage: prune_videos.py [database] [count]")
    exit()

database_name = sys.argv[1]

count = sys.argv[2]

if not os.path.isfile(database_name):
    print("Error: [database] must be a file")
    exit()

db = sqlite3.connect(database_name)


videos_to_remove = db.execute(query_videos_to_remove, {'count': count}).fetchall()

total_removed = 0

for video in videos_to_remove:
    (videoid, _, _, _, _, _) = video

    print(f"removing video 'https://youtu.be/{videoid}'")

    db.execute(query_delete_video, {'videoid': videoid})

    total_removed += 1

db.commit()

print(f"removed {total_removed} videos")

db.close()
