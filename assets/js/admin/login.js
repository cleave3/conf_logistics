document.getElementById("adminloginform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const loginbtn = document.getElementById("loginbtn");

    loginbtn.innerHTML = "LOGING IN...";
    loginbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest("auth/login", data);

    if (result.status) {
      toastr.success(result.message);

      setTimeout(() => {
        window.location = "dashboard";
      }, 1000);
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.success(result.message);
  } finally {
    loginbtn.innerHTML = `LOGIN <img class="ml-1" src="/assets/icons/enter.svg" width="20px" height="20px" />`;
    loginbtn.disabled = false;
  }
});

document.getElementById("eye").addEventListener("click", e => {
  const password = document.getElementById("password");
  password.type = password.type == "password" ? "text" : "password";
  e.target.classList.toggle("fa-eye-slash");
});
