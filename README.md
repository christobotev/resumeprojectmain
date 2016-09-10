Docs-app
========

A Symfony project created on June 14, 2016, 9:11 am.

**Using PHP 5.6, MySQL 5.5.47, jQuery, jQuery UI, Bootstrap 3, Permissions File cache, Memcached server (if available), oAuth2 auth bundle, Google Calendar service etc.**

In order to get everything working, you need to:

1. Clone this repo and ResumeProjectAPI
2. Run composer install to both projects
3. Use the virtual hosts found in '_docs' folder to actually create the vhosts
4. While in ResumeProjectMain folder, run 'php app/console doctrine:migrations:migrate' to set a basic database with group permissions and some dummy data for testing (*not really up to date go to 4 of "Things that I know are missing:"*)
5. Log in with your google account to be able to use all features. (just click on "OR LOGIN WITH Google")
6. If any questions arise you can comment here, write to my email or call

Things that I know are missing:

1. Doctors can also create appointments with other doctors(which they can't see, because if you are a doctor you only see appointments
made with you as a doctor - apps for you to approve or deny)

2. Doctors can rate themselves.
3. Registering users is available but it doesn't have any design, and users don't get any permissions(if you log in with google account you don't need to register manually).
4. Doctrine migrations don't cover all the data in the DB (there's a DB dump in the _docs folder)