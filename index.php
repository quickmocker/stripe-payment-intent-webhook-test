<!DOCTYPE html>
<html>

<head>
  <title>QuickMocker: Stripe Payment Intent Sample</title>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="assets/script.js" type="text/javascript"></script>

  <link href="assets/style.css" rel="stylesheet" />

  <script type="text/javascript">
    <?php
    // Get Stripe Public Key to JS.
    $config = require_once 'api/config/config.php';
    if (empty($config)) {
      throw new Exception('Stripe configuration not found.');
    }
    ?>
    var stripepublishableKey = "<?= $config['stripe']['publishableKey'] ?>";
  </script>
</head>

<body onload="quickmocker.init()">
  <div class="center">
    <img src="assets/stripe-quickmocker.png" alt="QuickMocker and Stripe" class="logo" />
    <h1>Stripe Payment Intent Sample</h1>
    <p>Complete Checkout</p>
    <form class="card-container" autocomplete="off" onsubmit="return quickmocker.submit();">
      <input type="text" name="name" id="name" placeholder="Name" autocomplete="none" />
      <input type="text" name="email" id="email" placeholder="Email" autocomplete="none" />
      <div id="card-element"></div>

      <div id="message"></div>

      <button type="submit" id="submit">Submit</button>
    </form>
  </div>
  <hr />
  <div class="container instructions">

    <h2>Description</h2>
    <p class="mb-5">
      This simple app will allow you to to see how to integrate Stripe Payment Intents on your site and how to debug
      Stripe webhook notifications using QuickMocker (Online API Mocking Tool). Similar approach could be used for any other 3rd party integration
      during development and testing process on your local machine.
    </p>

    <h2>Requirements</h2>
    <p>Please note, this sample app is using following stack below, but your own application can use any other stack as long as it is served over a web server.</p>
    <ul>
      <li>LAMP (WAMP/MAMP) stack with Apache, MySQL and PHP</li>
      <li><em>mod_headers</em> and <em>mod_rewrite</em> for Apache (required for .htaccess file to allow CORS headers and OPTIONS method 200 response)</li>
      <li>Composer (PHP CLI Package Manager)</li>
      <li><em>php-curl</em> and <em>php-mbstring</em> for PHP (required by Stripe SDK)</li>
    </ul>

    <h2>Installation Steps</h2>
    <ul>
      <li>Clone repository <em>git clone https://github.com/quickmocker/stripe-payment-intent-webhook-test.git</em></li>
      <li>Change directory to project folder: <em>cd stripe-payment-intent-webhook-test</em></li>
      <li>Install dependencies: <em>composer install</em></li>
      <li>Copy <em>api/config/config-sample.php</em> to <em>api/config/config.php</em></li>
      <li>Create new MySQL database, run <em>api/schema.sql</em> file to create database schema and define DB credentials in the <em>api/config/config.php</em></li>
      <li><a target="blank" href="https://dashboard.stripe.com/register">Create Stripe account</a>, get publishable and secret API keys inside the Developers section and add them to <em>api/config/config.php</em> file</li>
      <li><a href="https://quickmocker.com/register">Create QuickMocker account</a> and create a new project with any domain</li>
      <li>Open your QuickMocker's project and add new endpoint with POST HTTP method and URL path <em>api/stripe-webhook.php</em></li>
      <li>Copy the endpoint's URL and paste it inside Stripe when creating Webhhook Endpoint (while creating webhook endpoint, select all Payment Intent events)</li>
      <li>Copy "Signing Secret" and paste inside the <em>api/config/config.php</em> file. This is required to confirm that the remote request from Stripe is not spoofed.</li>
      <li>Go back to QuickMocker and switch to Requests Log tab</li>
      <li>
        Click Set Local Forwarder and add your localhost URL (e.g. http://localhost). Please note, that if your localhost URL does not use HTTPS (SSL) protocol,
        you need to allow Insecure Content for QuickMocker site inside your browser.
        See this <a target="_blank" href="https://experienceleague.adobe.com/docs/target/using/experiences/vec/troubleshoot-composer/mixed-content.html?lang=en#task_5448763B8DC941FD80F84041AEF0A14D">article</a>
        with guidance on how to do this.
      </li>
    </ul>

    <h2>How it works?</h2>

    <p>
      After the app is installed locally and you can access it from your browser, enter the name, email and dummy credit card inside the form
      (get the list of all test credit cards numbers <a href="https://stripe.com/docs/testing">here</a> or simply use this one <em>4000 0027 6000 3184</em>).
    </p>
    <p>
      When you fill in the form, hit the submit button. The app will send the AJAX request to its API endpoint (<em>api/stripe-intent.php</em>) in order
      to create/update payment intent with provided user and name value (as for the payment amount, it will be generated randomly on the API side).
      If payment intent was created successfully using Stripe SDK, the client secret code will be returned back to the app.
      Then the client secret code is used in order to confirm card payment using Stripe JS SDK.
    </p>
    <p class="mb-5">
      The payment intent status value (you may find it inside the MySQL DB) will be updated through the <em>api/stripe-webhook.php</em> endpoint
      with the help of Stripe webhook event (notification).
      We will use QuickMocker's Local Forwarder feature in order to expose the <em>api/stripe-webhook.php</em> endpoint from your local
      environment to the world. During the webhook notification the request could be debugged using any debugging tool (e.g. XDebug) or else
      you can peform debugging simply using response output which will be available inside QuickMocker's log record extra tab.
    </p>

    <h2>P.S.</h2>

    <p>We are currently preparing a short video that follows all the instructions to demonstrate QuickMocker's Local Forwarder usage. It will be added here soon.</p>
  </div>
</body>

</html>