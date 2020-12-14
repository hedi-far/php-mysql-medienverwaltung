# php-mysql-medienverwaltung

This web application is a simple library catalogue and administration system built with PHP, MariaDB/MySQL and HTML/CSS.

## Features

It has:

- a login page
- user roles (admin, librarian, patron)
- all logged-in users can browse the database, sort the data in the database by author, title, type of media, or publication year.
- all logged-in users can perform a search for author or publication title.
- users logged in as librarian or admin can edit, delete or add data sets to the database.
- users logged in as admin can add or delete users to the database.

## Screenshots

Login Page

![Login page](/screenshots/screenshot_login.jpg)

Admin dashboard

![Admin dashboard](/screenshots/screenshot_admin_dashboard.jpg)

Librarian dashboard

![Admin dashboard](/screenshots/screenshot_dashboard.jpg)

Browse all media

![Browse](/screenshots/screenshot_alle_medien.jpg)

Search

![Search](/screenshots/screenshot_search.jpg)

Description of medium

![Description of medium](/screenshots/screenshot_beschreibung.gif)

Add medium to database

![Add medium](/screenshots/screenshot_datensatz_anlegen.jpg)

Edit/delete medium

![Edit/delete medium](/screenshots/screenshot_loeschen_bearbeiten.jpg)

## Database

Database schema

![Schema](/screenshots/screenshot_db_schema.jpg)

Database administration

![DB admin](/screenshots/screenshot_php_myadmin.jpg)

## Instructions

1. Download [XAMPP](https://www.apachefriends.org/de/index.html) and install it.
2. Start XAMPP and start Apache and MySQL.
3. Go to http://localhost/phpmyadmin/ and add a new account with all privileges.
4. Rename the file "include_connection_xxx.php" to "include_connection.php" and add your login data.
5. In phpMyAdmin, add a new database called "Medienverwaltung". Add a table "medium" and a table "user".

'user' Table:

![user table](/screenshots/screenshot_table_user.jpg)

'medium' Table:

![user table](/screenshots/screenshot_table_medium.jpg)

6. Add a new user with user role 0 (admin user - this is a different user from the one you created above!).
7. Go to http://localhost/php-mysql-medienverwaltung/login.php and login as admin user. From here, you can create new users as well as new database entries.
