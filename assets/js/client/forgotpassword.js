document.getElementById("forgotpasswordform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const forgotpasswordbtn = document.getElementById("forgotpasswordbtn");

    forgotpasswordbtn.innerHTML = "Submitting...";
    forgotpasswordbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest("client/forgotpassword", data);

    if (result.status) {
      notify("success", result.message);

      setTimeout(() => {
        window.location = "resetpassword";
      }, 1000);
    } else {
      notify("danger", result.message);
    }
  } catch ({ message: error }) {
    notify("danger", error);
  } finally {
    forgotpasswordbtn.innerHTML = "Submit";
    forgotpasswordbtn.disabled = false;
  }
});
