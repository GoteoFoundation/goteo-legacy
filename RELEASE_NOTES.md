== Notes about the release 2.0

We have included many code from @dropmeaword (see TRANSLATOR_NOTES). Still there is many work to do to have full internationalization. Feel free to contribute on https://github.com/Goteo/Goteo.

In this release we have refactored the /controller/admin.php to use sub controllers, this make all us easier to add/modify the administration pages. 
We also use sub-controllers on the dashboard and the cron tasks.
Those who merge this release into previous ones will surelly have MANY conflits, let us know, send a friendly mail to dev@goteo.org


We include on this release some features that we developed since initial code release. Those include:
- Tasks exchange between admins see /admin/tasks
- Mailing sending process (multi mailing, blocking, etc) at /admin/newsletter. You run /cron-sender.sh 
- Home widgets /admin/home and /view/home*
- You can create own pages from /admin/pages

Others changes:
- Changed the styles a bit
- Erased some texts and page contents that are exclusively for goteo (terms, privacy, about)
- Recaptcha for contact form and token-check for messages (take care of spambots!)

Last but not least:
- Login with services  (/library/oauth)
- PayPal adaptive payments integration based on sdk-php v1.4 (/library/paypal/* IPN not implemented, https://github.com/paypal/sdk-core-php <- v1.5)
- Bcrypt to encript password (change the pass field on the user table)
- About Internationalization, there are known bugs/problems, see https://github.com/Goteo/Goteo/issues/22




Yours faithfully.
--
Goteo Dev Team
