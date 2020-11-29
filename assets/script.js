function quickmockerInit() {
  var stripe = Stripe(
    "pk_test_51HrW5DBqxL3ZMOsUS4ygFTVQd6IqEcPYsKzs90iPtRN0ZrRkaWcCXUsheImj6B2wYjn1zUlWqHKFmHCRGSJrPUe700jMDdHThP"
  );
  var elements = stripe.elements();
  var cardElement = elements.create("card", {
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
  cardElement.mount("#card-element");
}
