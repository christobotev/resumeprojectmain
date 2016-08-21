Docs-app
========

A Symfony project created on June 14, 2016, 9:11 am.
In order to get everything working, you need to:
clone this repo and ResumeProjectAPI
run composer install to both
use the virtual hosts found in '_docs' folder to actually create the vhosts
while in ResumeProjectMain folder, run 'php app/console doctrine:migrations:migrate' to set a basic database with group permissions and some dummy data for testing
log in with your google account to be able to use all features. (just click on log in ..OR LOGIN WITH Google)
if any questions arise you can comment here, write to my email or call

Things that I know are missing:

1. Doctors can also create appointments with other doctors(which they can't see, because if you are a doctor you only see appointents
made with you as a doctor - apps for you to approve or deny)

2. Doctors can rate themselfs.
3. No caching is available at this point.I will add memcached soon.
4. Registering users is available but it doesn't have any design, and users don't get any permissions(if you log in with google account you don't need to register manually).
5. Doctrine migrations don't cover all the data in the DB (there's an db dump in the _docs folder)
