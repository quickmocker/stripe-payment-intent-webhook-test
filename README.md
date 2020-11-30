 

 
# DESCRIPTION

This simple app will allow you to to see how to integrate Stripe Payment Intents on your site and how to debug Stripe webhook notifications using QuickMocker (Online API Mocking Tool). Similar approach could be used for any other 3rd party integration during development and testing process on your local machine.

 
# REQUIREMENTS

Please note, this sample app is using following stack below, but your own application can use any other stack as long as it is served over a web server.

* LAMP (WAMP or MAMP) stack with Apache, MySQL and PHP
* `mod_headers` and `mod_rewrite` for Apache (required for .htaccess file to allow CORS headers and OPTIONS method 200 response)
* `php-curl` and `php-mbstring` for PHP (required by Stripe SDK)

 
# INSTALLATION STEPS

* Clone repository `git clone https://github.com/quickmocker/stripe-payment-intent-webhook-test.git` or use `composer require quickmocker/stripe-payment-intent-webhook-test`
* Change directory to project folder: `cd stripe-payment-intent-webhook-test`
* Install dependencies: `composer install`
* Copy `api/config-sample.php` to `api/config.php`
* Create new MySQL database, run `api/schema.sql` file to create database schema and define DB credentials in the `api/config.php`
* [Create Stripe account](https://dashboard.stripe.com/register) , get publishable and secret API keys inside the Developers section and add them to `api/config.php` file
* [Create QuickMocker account](https://quickmocker.com/register) and create a new project with any domain
* Open your QuickMocker's project and add new endpoint with POST HTTP method and URL path _api/stripe-hook.php_
* Copy the endpoint's URL and paste it inside Stripe when creating Webhhook Endpoint
* Go back to QuickMocker and switch to Requests Log tab
* Click Set Local Forwarder and add your localhost URL (e.g. http://localhost). Please note, that if your localhost URL does not use HTTPS (SSL) protocol, you need to allow Insecure Content for QuickMocker site inside your browser. See this [article](https://experienceleague.adobe.com/docs/target/using/experiences/vec/troubleshoot-composer/mixed-content.html?lang=en#task_5448763B8DC941FD80F84041AEF0A14D) with guidance on how to do this. 

 
# P.S.

We are currently preparing a short video that follows all the instructions to demonstrate QuickMocker's Local Forwarder usage. It will be added here soon.
