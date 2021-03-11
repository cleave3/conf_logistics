document.addEventListener("DOMContentLoaded", async () => {
  const state = document.getElementById("state");
  const city = document.getElementById("city");
  const registeruserform = document.getElementById("registeruserform");
  const edituserform = document.getElementById("edituserform");

  if (registeruserform)
    registeruserform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
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
        toastr.error(error);
      } finally {
        hideLoader();
        registerbtn.innerHTML = "Create Account";
        registerbtn.disabled = false;
      }
    });

  if (edituserform)
    edituserform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const savebtn = document.getElementById("savebtn");

        savebtn.innerHTML = "Saving Changes...";
        savebtn.disabled = true;

        const data = new FormData(e.target);

        const result = await postRequest(`auth/edit`, data);

        if (result.status) {
          toastr.success(result.message);
          e.target.reset();
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(error);
      } finally {
        hideLoader();
        savebtn.innerHTML = `SAVE CHANGES <img class="ml-1" src="/assets/icons/save.svg" width="20px" height="20px" />`;
        savebtn.disabled = false;
      }
    });

  state.addEventListener("change", async e => {
    city.innerHTML = await loadcities(e.target.value);
  });
});
