document.addEventListener("DOMContentLoaded", async () => {
  const state = document.getElementById("state");
  const city = document.getElementById("city");

  city.innerHTML = await loadcities(state.value);

  document.getElementById("registeruserform").addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const registerbtn = document.getElementById("registerbtn");

      registerbtn.innerHTML = "Submitting...";
      registerbtn.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest(`auth/register`, data);

      if (result.status) {
        toastr.success(result.message);
        e.target.reset();
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
