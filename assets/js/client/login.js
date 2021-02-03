document.getElementById("clientloginform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const loginbtn = document.getElementById("loginbtn");

    loginbtn.innerHTML = "Submitting...";
    loginbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest("client/login", data);

    if (result.status) {
      notify("success", result.message);

      setTimeout(() => {
        window.location = "dashboard";
      }, 1000);
    } else {
      notify("danger", result.message);
    }
  } catch ({ message: error }) {
    notify("danger", error);
  } finally {
    loginbtn.innerHTML = "Login";
    loginbtn.disabled = false;
  }
});
