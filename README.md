Docs-app
========
**Overview:**

This project is created to show - coding style and ideas of how I handle different situations (using design patterns,using REST API client and a server, separating own libraries(decoupling) to be used as an outside library (composer package manager) - might as well separate the rest client bundle so that it could be used by other projects as a library)

This Application could be described simply as patient/doctor appointment system
- Doctor roles are assigned by the administator manually.
- Patients could register through the registration form or with their Google Accounts.

**Views:**  
There are four basic grids:  
1. - list of all doctors  
1.1. - From here the user has an option to go to the doctor profile (see pt.5)  
2. - List of all available doctors (doctors having less than 3 appointments that day)  
2.1. - From here the user has an option to:  
2.1.1. - Go to the doctor profile (see pt.5).  
2.1.2. - Make an appointment.  
        When appointment is created if the user has logged in through his GOOGLE an appointment
        to his google calendar will be created.

2.1.3. - Rate the doctor (maybe this should be available only after a successfull appointment*).  
3. - list of all appointments that belong to the logged user.  
4. - list of all reminder that belong to the logged user.  
5. - doctor profile, here the users can:  
5.1. - create appointment  
5.2. - create a reminder for this doctor  
5.3. - see all the info that we have for this doctor + ratings from other users.  

* - Thoughts out loud

**Using PHP 5.6, MySQL 5.5.47, jQuery, jQuery UI, Bootstrap 3, Permissions File cache, Memcached server (if available), oAuth2 auth bundle, Google Calendar service etc.**

In order to get everything working, you need to:  
1. Clone this repo and ResumeProjectAPI  
2. Run composer install to both projects (ResumeProjectMain && ResumeProjectAPI)  
3. Use the virtual hosts found in '_docs' folder to create the vhosts  
4. While in ResumeProjectMain folder, run 'php app/console doctrine:migrations:migrate' to set a basic database with group permissions and some dummy data for testing (*not really up to date go to 4 of "Things that I know are missing:"*)  
5. Log in with your google account to be able to use all features. (just click on "OR LOGIN WITH Google")  
6. If any questions arise you can comment here, write to my email or call  

Things that I know are missing:  
1. Registering users is available but only as patients (doctor role should be given by administrator).  
2. Doctrine migrations don't cover all the data in the DB (there's a DB dump in the _docs folder)  
