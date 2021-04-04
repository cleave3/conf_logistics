const processwaybillform = document.getElementById("processwaybillform");
const processwaybillbtn = document.getElementById("processwaybillbtn");

const updateWaybillRequest = async () => {
  try {
    processwaybillform.classList.add("was-validated");
    if (!processwaybillform.checkValidity()) return;

    processwaybillbtn.innerHTML = "Saving Changes...";
    processwaybillbtn.disabled = true;

    showLoader();

    const data = new FormData(processwaybillform);
    const result = await postRequest("waybill/processwaybill", data);

    if (result.status) {
      toastr.success(result.message);
      window.location.reload();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.error(error);
  } finally {
    hideLoader();
    processwaybillbtn.innerHTML = "Save Changes";
    processwaybillbtn.disabled = false;
  }
};

const confirmUpdate = () => {
  processwaybillform.classList.add("was-validated");
  if (!processwaybillform.checkValidity()) return;
  toastr.confirm("Are you sure you want to save this changes ?", {
    yes: async () => await updateWaybillRequest(),
  });
};

processwaybillbtn.addEventListener("click", confirmUpdate);
