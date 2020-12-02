# DESCRIPTION

This simple app will allow you to to see how to integrate Stripe Payment Intents on your site and how to debug Stripe webhook notifications using [QuickMocker](https://quickmocker.com) (Online API Mocking Tool). Similar approach could be used for any other 3rd party integration during development and testing process on your local machine.

 
# REQUIREMENTS

Please note, this sample app is using following stack below, but your own application can use any other stack as long as it is served over a web server.

* LAMP (WAMP/MAMP) stack with Apache, MySQL and PHP
* `mod_headers` and `mod_rewrite` for Apache (required for .htaccess file to allow CORS headers and OPTIONS method 200 response)
* Composer (PHP CLI Package Manager)
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
* Copy the endpoint's URL and paste it inside Stripe when creating Webhhook Endpoint (while creating webhook endpoint, select all Payment Intent events)
* Go back to QuickMocker and switch to Requests Log tab
* Click Set Local Forwarder and add your localhost URL (e.g. http://localhost). Please note, that if your localhost URL does not use HTTPS (SSL) protocol, you need to allow Insecure Content for QuickMocker site inside your browser. See this [article](https://experienceleague.adobe.com/docs/target/using/experiences/vec/troubleshoot-composer/mixed-content.html?lang=en#task_5448763B8DC941FD80F84041AEF0A14D) with guidance on how to do this. 


# How it works?
    
After the app is installed locally and you can access it from your browser, enter the name, email and dummy credit card inside the form (get the list of all test credit cards numbers [here](https://stripe.com/docs/testing) or simply use this one `4000 0027 6000 3184`).
    
When you fill in the form, hit the submit button. The app will send the AJAX request to its API endpoint (api/stripe-intent.php) in order to create/update payment intent with provided user and name value (as for the payment amount, it will be generated randomly on the API side). If payment intent was created successfully using Stripe SDK, the client secret code will be returned back to the app. Then the client secret code is used in order to confirm card payment using Stripe JS SDK.
    
The payment intent status value (you may find it inside the MySQL DB) will be updated through the api/stripe-hook.php endpoint with the help of Stripe webhook event (notification). We will use QuickMocker's Local Forwarder feature in order to expose the api/stripe-hook.php endpoint from your local environment to the world. During the webhook notification the request could be debugged using any debugging tool (e.g. XDebug) or else you can peform debugging simply using response output which will be available inside QuickMocker's log record extra tab.
    
 
# P.S.

We are currently preparing a short video that follows all the instructions to demonstrate QuickMocker's Local Forwarder usage. It will be added here soon.
