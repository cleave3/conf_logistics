const url = "http://localhost:8080/api/client/register";

document.getElementById("clientregisterform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const password = document.getElementById("password").value;
    const cpassword = document.getElementById("cpassword").value;

    if (password != cpassword) throw new Error("password does not match");
    if (password.length < 6) throw new Error("password must be atleast 6 characters long");

    const registerbtn = document.getElementById("registerbtn");

    registerbtn.innerHTML = "Submitting...";
    registerbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest(url, data);

    if (result.status) {
      notify("success", result.message);

      setTimeout(() => {
        window.location = "login";
      }, 3000);
    } else {
      notify("danger", result.message);
    }
  } catch ({ message: error }) {
    notify("danger", error);
  } finally {
    registerbtn.innerHTML = "Create Account";
    registerbtn.disabled = false;
  }
});
