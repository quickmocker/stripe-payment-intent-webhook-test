(function (globalScope) {
  function quickmocker() {}

  quickmocker.prototype.init = function () {
    this.response = {};
    // Update Stripe Public Key with your own.
    this.stripe = Stripe(stripepublishableKey);
    this.createStripeElements();
  };

  quickmocker.prototype.submit = function () {
    this.showLoading();

    var xhr = new XMLHttpRequest();
    var _this = this;
    xhr.responseType = "json";
    xhr.open("POST", "/api/stripe-intent.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
      if (this.readyState !== XMLHttpRequest.DONE) {
        // Skip code if request is not completed.
        return;
      }

      if (this.status === 200) {
        Object.assign(_this.response, this.response);
        // Send request to stripe on success.
        return _this.stripe
          .confirmCardPayment(this.response.client_secret, {
            payment_method: {
              card: _this.cardElement,
              billing_details: {
                name: document.getElementById("name").value,
                email: document.getElementById("email").value,
              },
            },
          })
          .then((result) => {
            _this.hideLoading();
            if (result.error) {
              _this.showMessage(
                result.error.message ? result.error.message : "The payment was not completed. Please try again.",
                true
              );
            } else {
              _this.showMessage("Payment completed in Stripe.");
              this.response = {};
            }
          });
      }
      if (this.status !== 200) {
        // Show message on error.
        _this.hideLoading();
        if (this.response && this.response.message) {
          _this.showMessage(this.response.message, true);
        } else {
          _this.showMessage("Unknown error occured. Please check API side.", true);
        }
      }
    };
    var data = {
      name: document.getElementById("name").value,
      email: document.getElementById("email").value,
      id: this.response.id,
    };
    xhr.send(JSON.stringify(data));
    return false;
  };

  quickmocker.prototype.showLoading = function (message) {
    document.getElementById("submit").classList.add("loading");
    document.getElementById("submit").disabled = true;
    this.showMessage(message ? message : "Loading...");
  };

  quickmocker.prototype.hideLoading = function () {
    document.getElementById("submit").classList.remove("loading");
    document.getElementById("submit").disabled = false;
    this.hideMessage();
  };

  quickmocker.prototype.hideMessage = function () {
    document.getElementById("message").classList.remove("show");
  };

  quickmocker.prototype.showMessage = function (message, isError) {
    var errorElement = document.getElementById("message");
    errorElement.classList.remove("error");
    errorElement.classList.add("show");
    errorElement.innerText = message;
    if (isError) {
      errorElement.classList.add("error");
    }
  };

  quickmocker.prototype.createStripeElements = function () {
    var elements = this.stripe.elements();
    this.cardElement = elements.create("card", {
      hidePostalCode: true,
      iconStyle: "solid",
      style: {
        base: {
          iconColor: "#6AA000",
          color: "#fff",
          fontWeight: 700,
          fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
          fontSize: "16px",
          fontSmoothing: "antialiased",

          ":-webkit-autofill": {
            color: "#5F5F5F",
          },
          "::placeholder": {
            color: "#fff",
          },
        },
        invalid: {
          iconColor: "#df5522",
          color: "#df5522",
        },
      },
    });
    this.cardElement.mount("#card-element");
  };

  globalScope.quickmocker = new quickmocker();
})(window);
