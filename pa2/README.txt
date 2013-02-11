<<<<<<< HEAD
EECS 486 PROJECT PART 2
=======================

*AUTHOR: 
--------
	GROUP36 - Baishun Wu, Ruoran Wang, Dailin Liu

*EXTRA CREDITS COVERED:
-----------------------
	1. After user signup, an email will be sent to the user comfirming the membership and the user will be redirected to the logged-in homepage. The email is cc to group members. This action will be activated as soon as the user clicks the signup button in 'signup.php'.

	2. If the user forgets the password, an email will be sent to the user with a new password. The updated user account is decided by the email address the user entered. This action is embedded in the navigation bar.

	3. An administrator is added to the website. User 'spacejunkie' is the default root-user. Root-user can give and remove root-user privilege in 'manage.php'.


*DETAILED DESCRIPTION AND INSTRUCTION FOR EACH PAGE:
----------------------------------------------------
	1. navbar
		-Without loggin process, the navbar will show:
			login textbox
			link of signup - direct to signup.php
			link of Home page - direct to index.php
			link of All Albums - direct to viewalbumlist.php
			link of 'Forget Password?' - direct to ask_email.php
		-If the user starts login process, the login area will
			keep the entered username if the password is incorrect
			Clear the both text boxes if the username is incorrect
		-If the user successfully logged in, the navbar will show:
			link of Home - direct to viewalbumlist.php
			link of MyAlbums - direct to myalbumlist.php
			link of Manage Site - direct to manage.php if the user is root-user
			link of 'username' - direct to edituser.php
			link of Logout - log out the user and return to index.php
	2. index.php
		-Welcome page of the website.
		-The list of users are shown on this page.
	3. signup.php
		-User can input signup information. If the information is illegal, alert box will shown. 
		-After signing up, the user will be directed to the homepage.
	4. ask_email.php
		-Let the user input the email address for the new password.
		-An email containing the new password will be sent.
		-The password of the user who owns the provided email will be updated.  
	5. edituser.php
		-User can change firstname, lastname, email, password, and delete user account.
		-If the user changes the firstname, lastname and email, password should be provided.
	6. viewalbumlist.php
		-Without loggin, the page will show all public albums. The albums can be viewed by visitors. "Vistor" will be shown as the name of the user.
		-After loggin, the page will show all albums, including private ones. If not permitted by the owner of the private albums, the content of private albums cannot be viewed. Firstname and last name of the user will be shown.
	7. myalbumlist.php
		-Show albums owned by the user.
		-Direct user to editalbumlist.php for album editing.
	8. editalbumlist.php
		-At the top of the page, the user can add new albums.
		-User can edit or delete existing albums. Before the album is deleted, a confirm box will prompt out. 
		-User can share private albums to users by entering the username. If the username doesn't exist, there will be an error alert box.
	9. viewalbum.php
		-Show thumbnails of photos in the album, including captions, etc..
		-Clicking the thumbnail will lead to the 'view photo' mode. Under this mode, one can add comments, email and edit the photo.
		-The photo can be only edited by the owner of the album if the owner is directed from myalbum.php.
	10. manage.php
		-This page is only accessible by root user.
		-Root user can edit and delete all albums in this page.
		-Root user can assign new root users or dismiss other root users.
	11. timeout.php
		-If the session is idle for 5min, log out the user and jump to this page. 
=======

MySql infor should be defined in lib.php, see lib.php.example

>>>>>>> c0244e41dff5aa7a329cc0830faaa9b88aaff493
