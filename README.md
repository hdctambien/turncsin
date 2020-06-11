# Turn CS In
> A barebones web application to allow remote submission of Computer Science programming assignments

## Getting started

Edit the `config/config.ini` file and change the password and admin_password values. Also, change the timezone setting ( [Supported Timezones](https://www.php.net/manual/en/timezones.php) )

Copy all the files from this project into a folder on your PHP enabled web server and it should just work!

This application only requires Apache Web Server and PHP. No databases!

## Logging in

This application does not have "user accounts". A user is either a *student* or an *admin*.

## Students
Students can upload assignments and are required to provide their name (or some identifying label) when they turn in their assignment. Their assignment will be stored in a folder with that name.

Files uploaded through this application cannot be accessed via the web.

## Teachers (admin)

An admin user can access the admin control panel by appending `/admin` to the URL.

From the Admin Control Panel an admin can:

* create new assignments
* edit existing assignments
* delete assignments (only if no student has submitted a file to it yet)
* view an assignments status (including the names of students that have turned in files)
* download all student files submitted to an assignment in a single .zip file

## Assignment Settings

The only required field for an assignment is it's `slug`. That is the name of the folder that it will be stored in as well as how it will be identified in the url. This should be kept short and sweet.

All other settings for an assignment are optional:

* name - The name of the assignment will appear in a dropdown menu for the students and in the admin control panel
* Required File Name - This will limit student uploads for this assignment to the specified file name. (Example: foo.java)
* Open Date: The date to begin allowing uploads for this assignment (format: YYYY-mm-dd hh:ii)
* Close Date: The date to stop allowing uploads for this assignment (format: YYYY-mm-dd hh:ii)

You can leave any of these fields empty if you don't want to enable their functionality.

Note: An assignment with an empty title will display the slug name.

Note: If you are using the open & close date fields, make sure to set the timezone in the `config.ini` file

## Libraries and Frameworks

This project uses the [Skeleton](http://getskeleton.com/) framework for its CSS

## Features

This project makes it easy to:
* Students can upload .java files remotely or paste their code into a text box
* Teachers can download a zip file for each assignment that contains a folder for each student
* Assignments can require a specific java file to be uploaded
* Assignments can be automatically shown/hidden based on the date/time

## Licensing

This project is licensed under MIT license. A short and simple permissive license with conditions only requiring preservation of copyright and license notices. Licensed works, modifications, and larger works may be distributed under different terms and without source code.
