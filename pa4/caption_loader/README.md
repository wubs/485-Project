CaptionLoader - subproject of PA4
=================================

db.cfg
------
Change db name, uesr, pass here.


Source code 
-----------

`src/CaptionLoader.java`


Ant - build.xml
---------------

Check the file for detail.

After `db.cfg` is configured. 

`ant run` will load the images into database.

1. We used old db schema. column code and type are for base64 images, which is not used in pa4.

2. We didn't load the date, because all dates are the same.


Images
------

This program will not move the files under `flickr-images/` to `/static/images/`

You need to manually move those:
`cp pa4/caption_loader/flickr-images/* pa4/html/static/images/`
