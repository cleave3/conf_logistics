document.addEventListener("DOMContentLoaded", async () => {
  const state = document.getElementById("state");
  const city = document.getElementById("city");

  city.innerHTML = await loadcities(state.value);

  document.getElementById("clientregisterform").addEventListener("submit", async e => {
    try {
      e.preventDefault();
      e.currentTarget.classList.add("was-validated");
      if (!e.target.checkValidity()) return;
      const password = document.getElementById("password").value;
      const cpassword = document.getElementById("cpassword").value;

      if (password != cpassword) throw new Error("password does not match");
      if (password.length < 6) throw new Error("password must be atleast 6 characters long");

      const registerbtn = document.getElementById("registerbtn");

      registerbtn.innerHTML = "Submitting...";
      registerbtn.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest(`client/register`, data);

      if (result.status) {
        toastr.success(result.message);
        setTimeout(() => {
          window.location = "login";
        }, 3000);
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(result.message);
    } finally {
      registerbtn.innerHTML = "Create Account";
      registerbtn.disabled = false;
    }
  });

  state.addEventListener("change", async e => {
    city.innerHTML = await loadcities(e.target.value);
  });
});
