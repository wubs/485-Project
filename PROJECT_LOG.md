EECS485 PROJECT PA1 LOG
=======================

PART 3 Schema creation (30) (DONE)
----------------------------------

1. PART 3A Create tables
    * REQUIREMENT:
        1. TABLES: User, Album, Contain, Photo, AlbumAccess (DONE)
        2. Create 'pa1/tb1_create.sql'(DONE)

2. PART 3B Load data 
    * REQUIReMENT:
        1. Create 'pa1/load_data.sql' (DONE)


PART 4 Building photo album website (50)
----------------------------------------

1. index.php (DONE)
    * REQUIREMENT:
	    1. <title><meta>tags, header and footer for the page (DONE)	
	    2. Text describing the website (DONE)
	    3. A list of users whose albums can be browsed (DONE)
	
2. viewalbumlist.php (DONE)
    * REQUIREMENT:
	    1. Show different web pages depending on the users (DONE)
	    2. Public albums show hyperlinks (DONE)
	    3. Private albums are shown, but have no hyperlinks (DONE)	
	
3. editalbumlist.php
    * REQUIREMENT:
	    1. Pressents the user with a list of his/her albums(different pages should be presented depending on users)
	    2. use "op" indicates the operation of buttons (DONE)
	    3. update the tables(Photo, Contain, Album, AlbumAccess) correspondingly

4. editalbum.php
    * REQUIREMENT:
	    1. user can change access
	    2. add pictures to album (duplicacy is only tested by filename)
            * in database, attach to the album
	        * not appear in database, add to Photo table and attach to the album
        3. Delete pictures from the album (one at a time)
	    4. Automatically assign a seq# to the picture
	    5. Figure out the fomat of the image from given URL

5. viewalbum.php
    * REQUIREMENT:
        1. Thumbnail view of the pictures in the album ordered by the seq# (DONE)

6. viewpicture.php			
    * REQUIREMENT:
        1. shows the full sized picture (DONE)
        2. have navigational elements to go to next/previous picture

7. Website Spruce up (DONE)
    * REQUIREMENT:
        1. easy to understand, simple to use...

PART 5 EXTRA CREDITS
--------------------
* REQUIREMENT:
    1. Comments for each photo(post new + read previous ones)
	2. Email a photo to an email address
	3. The actual image bytes are stored in and loaded from the database itself and not the URLs
	 
