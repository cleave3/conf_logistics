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

document.getElementById("eye").addEventListener("click", e => {
  const password = document.getElementById("password");
  password.type = password.type == "password" ? "text" : "password";
  e.target.classList.toggle("fa-eye-slash");
});
