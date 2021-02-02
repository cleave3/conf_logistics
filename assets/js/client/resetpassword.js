const url = "http://localhost:8080/api/client/resetpassword";

document.getElementById("resetpasswordform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const resetpasswordbtn = document.getElementById("resetpasswordbtn");

    const password = document.getElementById("password").value;
    const cpassword = document.getElementById("cpassword").value;

    if (password != cpassword) throw new Error("password does not match");
    if (password.length < 6) throw new Error("password must be atleast 6 characters long");

    resetpasswordbtn.innerHTML = "Submitting...";
    resetpasswordbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest(url, data);

    if (result.status) {
      notify("success", result.message);

      setTimeout(() => {
        window.location = `login`;
      }, 1000);
    } else {
      notify("danger", result.message);
    }
  } catch ({ message: error }) {
    notify("danger", error);
  } finally {
    resetpasswordbtn.innerHTML = "Submit";
    resetpasswordbtn.disabled = false;
  }
});
