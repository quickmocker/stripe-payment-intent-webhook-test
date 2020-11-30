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
    $config = require_once 'api/config.php';
    if (empty($config)) {
      throw new Exception('Stripe configuration not found.');
    }
    ?>
    var stripePublicKey = "<?= $config['stripe']['publicKey'] ?>";
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
      <li>LAMP (WAMP or MAMP) stack with Apache, MySQL and PHP</li>
      <li><em>mod_headers</em> and <em>mod_rewrite</em> for Apache (required for .htaccess file to allow CORS headers and OPTIONS method 200 response)</li>
      <li><em>php-curl</em> and <em>php-mbstring</em> for PHP (required by Stripe SDK)</li>
    </ul>

    <h2>Installation Steps</h2>
    <ul>
      <li>Clone repository <em>git clone https://github.com/quickmocker/stripe-payment-intent-webhook-test.git</em> or use <em>composer require quickmocker/stripe-payment-intent-webhook-test</em></li>
      <li>Change directory to project folder: <em>cd stripe-payment-intent-webhook-test</em></li>
      <li>Install dependencies: <em>composer install</em></li>
      <li>Copy <em>api/config-sample.php</em> to <em>api/config.php</em></li>
      <li>Create new MySQL database, run <em>api/schema.sql</em> file to create database schema and define DB credentials in the <em>api/config.php</em></li>
      <li><a target="blank" href="https://dashboard.stripe.com/register">Create Stripe account</a>, get publishable and secret API keys inside the Developers section and add them to <em>api/config.php</em> file</li>
      <li><a href="https://quickmocker.com/register">Create QuickMocker account</a> and create a new project with any domain</li>
      <li>Open your QuickMocker's project and add new endpoint with POST HTTP method and URL path <em>api/stripe-hook.php</em></li>
      <li>Copy the endpoint's URL and paste it inside Stripe when creating Webhhook Endpoint</li>
      <li>Go back to QuickMocker and switch to Requests Log tab</li>
      <li>
        Click Set Local Forwarder and add your localhost URL (e.g. http://localhost). Please note, that if your localhost URL does not use HTTPS (SSL) protocol,
        you need to allow Insecure Content for QuickMocker site inside your browser.
        See this <a target="_blank" href="https://experienceleague.adobe.com/docs/target/using/experiences/vec/troubleshoot-composer/mixed-content.html?lang=en#task_5448763B8DC941FD80F84041AEF0A14D">article</a>
        with guidance on how to do this.
      </li>
    </ul>

    <h2>P.S.</h2>

    <p>We are currently preparing a short video that follows all the instructions to demonstrate QuickMocker's Local Forwarder usage. It will be added here soon.</p>
  </div>
</body>

</html>