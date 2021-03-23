document.getElementById("forgotpasswordform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const forgotpasswordbtn = document.getElementById("forgotpasswordbtn");

    forgotpasswordbtn.innerHTML = "Submitting...";
    forgotpasswordbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest("agent/forgotpassword", data);

    if (result.status) {
      toastr.success(result.message);

      setTimeout(() => {
        window.location = "resetpassword";
      }, 1000);
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.success(result.message);
  } finally {
    forgotpasswordbtn.innerHTML = "Submit";
    forgotpasswordbtn.disabled = false;
  }
});
