const addinventoryform = document.getElementById("addinventoryform");
const updateinventoryform = document.getElementById("updateinventoryform");

if (addinventoryform)
  addinventoryform.addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const submitinventory = document.getElementById("submitinventory");

      submitinventory.innerHTML = "Submitting...";
      submitinventory.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest("inventory/add", data);

      if (result.status) {
        notify("success", result.message);
        e.target.reset();
      } else {
        notify("danger", result.message);
      }
    } catch ({ message: error }) {
      notify("danger", error);
    } finally {
      submitinventory.innerHTML = "Submit";
      submitinventory.disabled = false;
    }
  });

if (updateinventoryform)
  updateinventoryform.addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const updateinventory = document.getElementById("updateinventory");

      updateinventory.innerHTML = "Updating...";
      updateinventory.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest("inventory/edit", data);
      console.log(result);

      if (result.status) {
        notify("success", result.message);
        setTimeout(() => {
          window.location = "/clients/inventory";
        }, 1000);
      } else {
        notify("danger", result.message);
      }
    } catch ({ message: error }) {
      notify("danger", error);
    } finally {
      updateinventory.innerHTML = `Update <i class="fa fa-upload" aria-hidden="true"></i>`;
      updateinventory.disabled = false;
    }
  });
