document.addEventListener("DOMContentLoaded", () => {
  const statusform = document.getElementById("statusform");

  if (statusform)
    statusform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const data = new FormData(e.target);

        const result = await postRequest("task/updateorderstatus", data);

        if (result.status) {
          toastr.success(result.message);

          window.location.reload();
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        console.log(error);
      } finally {
        hideLoader();
      }
    });
});
