const toggleClientStatus = async (status, clientid) => {
  try {
    showLoader();
    const data = new FormData();
    data.append("status", status);
    data.append("clientid", clientid);
    const result = await postRequest(`client/status`, data);

    if (result.status) {
      toastr.success(result.message);
      window.location.reload();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(error);
  } finally {
    hideLoader();
  }
};

const toggleClient = (status, clientid) => {
  toastr.confirm("Are you sure you want to perform this operation ?", {
    yes: () => toggleClientStatus(status, clientid),
  });
};
