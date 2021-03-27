const paymentoption = document.getElementById("paymentoption");
const firstname = document.getElementById("first-name");
const lastname = document.getElementById("last-name");
const email = document.getElementById("email");
const proof = document.getElementById("proof");
const paymentbtn = document.getElementById("paymentbtn");
const submitpayment = document.getElementById("submitpayment");
const paymentform = document.getElementById("paymentform");

function checkFormValidity() {
  let errors = 0;

  if (paymentoption.value == "card") {
    firstname.value.trim() === ""
      ? (errors++, firstname.nextElementSibling.classList.remove("d-none"))
      : firstname.nextElementSibling.classList.add("d-none");

    lastname.value.trim() === ""
      ? (errors++, lastname.nextElementSibling.classList.remove("d-none"))
      : lastname.nextElementSibling.classList.add("d-none");

    !/^([a-z\d\.-]+)@([a-z\d-]+)\.([a-z]{2,8})(\.[a-z]{2,8})?$/.test(email.value.trim())
      ? (errors++, email.nextElementSibling.classList.remove("d-none"))
      : email.nextElementSibling.classList.add("d-none");
  } else {
    proof.value.length < 1 ? (errors++, proof.nextElementSibling.classList.remove("d-none")) : proof.nextElementSibling.classList.add("d-none");
  }

  return errors > 0 ? false : true;
}

if (paymentoption)
  paymentoption.addEventListener("change", e => {
    if (e.target.value === "card") {
      document.querySelector(".form-submit-card").classList.remove("d-none");
      document.querySelector(".form-submit-bank").classList.add("d-none");
    } else {
      document.querySelector(".form-submit-card").classList.add("d-none");
      document.querySelector(".form-submit-bank").classList.remove("d-none");
    }
  });

if (paymentbtn)
  paymentbtn.addEventListener("click", e => {
    e.preventDefault();
    payWithPayStack();
  });

if (submitpayment)
  submitpayment.addEventListener("click", e => {
    e.preventDefault();
    payWithProof();
  });

function payWithPayStack() {
  if (!checkFormValidity()) return;
  let handler = PaystackPop.setup({
    key: "pk_test_d78727efe914e840cdda7f6d0ef81a9bcd0d1e59",
    email: document.getElementById("email").value,
    amount: Number(document.getElementById("amount").value) * 100,
    firstname: document.getElementById("first-name").value,
    lastname: document.getElementById("last-name").value,
    ref: "conf-" + Math.floor(Math.random() * 1000000000 + 1) + Math.floor(Math.random() * 1000000000 + 1),
    onClose: function () {
      console.log("closed");
    },
    callback: function (response) {
      async function run() {
        try {
          const param = new FormData(paymentform);
          param.append("ref", response.reference);

          const result = await postRequest("task/deliverypayment", param);
          if (result.status) {
            toastr.success(result.message);
            window.history.back();
          } else {
            toastr.error(result.message);
          }
        } catch ({ message: error }) {
          toastr.error(error);
        }
      }
      run();
    },
  });
  handler.openIframe();
}

async function payWithProof() {
  try {
    if (!checkFormValidity()) return;
    showLoader();
    const param = new FormData(paymentform);
    submitpayment.disabled = true;

    const result = await postRequest("task/deliverypayment", param);
    if (result.status) {
      toastr.success(result.message);
      window.history.back();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(error);
  } finally {
    submitpayment.disabled = false;
    hideLoader();
  }
}
