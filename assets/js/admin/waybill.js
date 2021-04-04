const processwaybillform = document.getElementById("processwaybillform");

const saveChanges = async () => {
  try {
    showLoader();
    const data = new FormData(processwaybillform);
    const result = await postRequest("package/updatewaybill", data);

    if (result.status) {
      toastr.success(result.message);
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(result.message);
  } finally {
    hideLoader();
  }
};

const recieveWayBill = async packageid => {
  try {
    showLoader();
    const data = new FormData();
    data.append("packageid", packageid);
    const result = await postRequest("package/recievewaybill", data);

    if (result.status) {
      toastr.success(result.message);
      window.location = "/admin/waybills";
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(result.message);
  } finally {
    hideLoader();
  }
};

const confirmRecieve = id => {
  toastr.confirm("Have you confirmed that this item has been recieved ?", {
    yes: () => recieveWayBill(id),
  });
};

const confirmChanges = () => {
  toastr.confirm("Have you informed the seller of these changes ?", {
    yes: () => saveChanges(),
  });
};
