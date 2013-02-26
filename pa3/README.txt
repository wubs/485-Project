Note: MySql infor should be defined in lib.php, see lib.php.example

EECS 486 PROJECT PART 3
=======================

*AUTHOR: 
--------
	GROUP36 - Baishun Wu, Ruoran Wang, Dailin Liu

*EXTRA CREDITS COVERED:
-----------------------
  1. Scrollable photo captions. 
    This part is shown in viewalbum.php. The caption will sroll together with the picuture in image viewer.

  2. Instant filtering.
    This part is also implemented in viewalbum.php. At the top of the page, a textbox is shown for the user to input the keywords. 
    If keyword is found and matched, corresponding images will be shown on the page. Otherwise, an error message will be shown.
    
    Please test the images in album 1 - 'I love sports' for meaningful captions. The meaningful captions are "basketball", "soccer", and "sports". 


*DETAILED DESCRIPTION OF TASKS:
----------------------------------------------------
	1. Drag and Drop Library. 
	  The Drag and Drop function is implemented in ruoran.js.

	2. Access Control by Drag and Drop
    This part is realized in editalbumlist.php.
    The ACDD is able to give access to another users. User's access can be deleted by dragging the username to the Trash location. If the element is not dragged to the correct location, it will restore to the original position.
    
  3. Scrollable Photo Viewer
    This part is shown on viewalbum.php
    The album viewer is displayed on the thumbnail page and can be closed by clicking the 'Close' text. Black area is shown when the user scroll beyond the first or last photo of the album, and the viewer will return to the most recent picture. 
