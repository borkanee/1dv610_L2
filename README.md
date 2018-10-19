# INSTALL
- Clone the repo, add a config folder, and add a file `Config.php` inside the config folder.
- The Config-file should look like this:

```php
<?php

$_ENV['DB_HOST'] = 'host';
$_ENV['DB_USER'] = 'user';
$_ENV['DB_PASSWORD'] = 'database_password';
$_ENV['DB_NAME'] = 'database_name';

```

Of course you have to enter your own information above (host, user etc...)

You also have to add 3 tables to your database:
* users (username, password)
* cookies (username, cookiepassword, expirydate)
* snippets (username, snippetname, snippetcode)

# USE CASES
## UC5 Save Snippet
### Preconditions
A user is authenticated. Ex. UC1, UC3
### Main scenario
 1. Starts when a user is logged in
 2. User clicks on 'My Snippets'
 3. System presents the snippets view
 4. User provides a snippet name and some code
 5. System saves the user and presents the snippet below input-area 

### Alternate Scenarios
 * 4a. Saving snippet failed
   1. System presents an error message
   2. Step 3 in main scenario

## UC6 View previous snippets
### Preconditions
A user is authenticated. Ex. UC1, UC3
### Main scenario
 1. Starts when a user is logged in
 2. User clicks on 'My Snippets'
 3. System presents the snippets view
 4. All previously saved snippets are visible 

### Alternate Scenarios
 * 4a. Fetching snippets failed
   1. System fails to fetch snippets for some reason
   2. Step 3 in main scenario

# TESTCASES
## TC 5.1: Snippets link present when user has logged in

**INPUT**
* Test case 1.7: Successful login with correct Username and Password

**OUTPUT**
* Snippets link present when user has logged in

## TC 5.2: Snippets view presented when clicking 'My Snippets'

**INPUT**
* Test case 5.1

**OUTPUT**
* Snippets view is presented.

## TC 5.3: Failed save without any code

**INPUT**
* Test case 5.2
* Enter a snippet name but leave the code area empty
* Press 'Save'

**OUTPUT**
* Snippets view is presented.
* Feedback: "Snippet code is missing." is shown

## TC 5.4: Failed save with empty fields

**INPUT**
* Test case 5.2
* Leave snippet name and snipped code empty
* Press 'Save'

**OUTPUT**
* Snippets view is presented.
* Feedback: "Name and code is missing." is shown

## TC 5.5: Failed save with too short snippet name

**INPUT**
* Test case 5.2
* Enter a snippet name shorter than 3 characters, write some code in the text area
* Press 'Save'

**OUTPUT**
* Snippets view is presented.
* Feedback: "Snippet name has too few characters, at least 3 characters." is shown

## TC 5.6: Successful snippet save

**INPUT**
* Test case 5.2
* Enter a snippet name longer than 3 characters, write some code in the text area
* Press 'Save'

**OUTPUT**
* Snippets view is presented.
* Snippet is saved in the database 
* Snippet is shown under the input-area

## TC 6.1: Successful fetch of saved snippets

**INPUT**
* Test case 1.7: Successful login with correct Username and Password
* Click 'My Snippets'

**OUTPUT**
* Snippets view is presented.
* All previous snippets are shown under the input-area
