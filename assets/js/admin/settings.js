const setEditDetails = async (type, id) => {
  try {
    showLoader();
    const url = type === "pricing" ? `price?id=${id}` : `location?id=${id}`;

    if (type === "pricing") {
      $(`#price_state option[value='']`).prop("selected", true);
      document.getElementById("price_amount").value = "";
      document.getElementById("price_city").value = "";
      document.getElementById("price_id").value = "";
    } else {
      $(`#location_state option[value='']`).prop("selected", true);
      $(`#location_status option[value='']`).prop("selected", true);
      document.getElementById("location_amount").value = "";
      document.getElementById("location_location").value = "";
      document.getElementById("location_id").value = "";
    }

    const result = await getRequest(`config/${url}`);
    console.log(result);

    if (result.status) {
      if (type === "pricing") {
        $(`#price_state option[value='${result.data.state_id}']`).prop("selected", true);
        document.getElementById("price_amount").value = result.data.extra_charge;
        document.getElementById("price_city").value = result.data.city;
        document.getElementById("price_id").value = result.data.id;
      } else {
        $(`#location_state option[value='${result.data.state_id}']`).prop("selected", true);
        $(`#location_status option[value='${result.data.status}']`).prop("selected", true);
        document.getElementById("location_amount").value = result.data.amount;
        document.getElementById("location_location").value = result.data.location;
        document.getElementById("location_id").value = result.data.id;
      }
    }
  } catch ({ message: error }) {
    toastr.error(error);
  } finally {
    hideLoader();
  }
};

document.addEventListener("DOMContentLoaded", async () => {
  const companylogo = document.getElementById("companylogo");
  const image = document.getElementById("image");
  const changebtn = document.getElementById("changebtn");
  const basicsettingsform = document.getElementById("basicsettingsform");
  const pricingform = document.getElementById("pricingform");
  const locationform = document.getElementById("locationform");
  const editpricingform = document.getElementById("editpricingform");
  const editlocationform = document.getElementById("editlocationform");
  const configurationforms = document.querySelectorAll("#configuration form");

  changebtn.addEventListener("click", e => {
    e.preventDefault();
    image.click();
  });

  image.addEventListener("change", async e => {
    if (e.target.files.length < 1) {
      companylogo.src = "/files/photo/camera.svg";
      return;
    }

    const file = e.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", e => (companylogo.src = reader.result));
    reader.readAsDataURL(file);
  });

  if (basicsettingsform)
    basicsettingsform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const savebasics = document.getElementById("savebasics");
        savebasics.innerHTML = "UPLOADING...";
        savebasics.disabled = true;

        const data = new FormData(e.target);
        const result = await postRequest(`config/updatesettings`, data);

        if (result.status) {
          toastr.success(result.message);
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(result.message);
      } finally {
        savebasics.innerHTML = `Save <i class="fa fa-upload" aria-hidden="true"></i>`;
        savebasics.disabled = false;
        hideLoader();
      }
    });

  const refreshTables = async type => {
    let newrow = "";
    const tablebody = type === "pricing" ? "pricingtablebody" : "locationtablebody";
    const tableid = type === "pricing" ? "#pricingtable" : "#locationtable";
    const url = type === "pricing" ? "deliverypricies" : "waybilllocations";
    const result = await getRequest(`config/${url}`);

    result.data.map((data, i) => {
      if (type === "pricing") {
        // <td data-label="">${i + 1}</td>
        // <td data-label="STATE : ">${data.state}</td>
        newrow += `<tr>
              <td data-label="CITY : ">${data.state} - ${data.city}</td>
              <td data-label="AMOUNT : ">&#8358;${numberFormat(data.extra_charge)}</td>
              <td data-label=" " class="d-md-flex justify-content-center">
                  <a class="btn btn-sm mx-1 btn-primary" href="#" data-toggle="modal" data-target="#editpricingmodal" title="Edit Pricing" onclick="setEditDetails('pricing' , '${
                    data.id
                  }')">
                      <img src="/assets/icons/edit.svg" width="20px" height="20px" />
                  </a>
              </td>
          </tr>`;
      } else {
        // <td data-label="">${i + 1}</td>
        // <td data-label="STATE : ">${data.state}</td>
        newrow += `<tr>
          <td data-label="LOCATION : ">${data.state} - ${data.location}</td>
          <td data-label="AMOUNT : ">&#8358;${numberFormat(data.amount)}</td>
          <td data-label="STATUS : " >
          <span class="badge badge-${determineClass(data.status)} p-2">${data.status}</span>
          </td>
          <td data-label=" " class="d-md-flex justify-content-center">
              <a class="btn btn-sm mx-1 btn-primary" data-toggle="modal" data-target="#editlocationmodal" title="Edit Pricing" onclick="setEditDetails('location' , '${
                data.id
              }')" title="Edit Location">
                  <img src="/assets/icons/edit.svg" width="20px" height="20px" />
              </a>
          </td>
      </tr>`;
      }
    });
    const table = $(tableid).DataTable();
    const tableinfo = table.page.info();
    table.destroy();

    document.getElementById(tablebody).innerHTML = newrow;

    $(tableid).DataTable({
      fixedHeader: true,
      displayStart: tableinfo.page * tableinfo.length,
      pageLength: tableinfo.length,
    });
  };

  if (pricingform)
    pricingform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const addpricingbtn = document.getElementById("addpricingbtn");
        addpricingbtn.innerHTML = "Submitting...";
        addpricingbtn.disabled = true;

        const data = new FormData(e.target);
        const result = await postRequest(`config/adddeliverypricing`, data);

        if (result.status) {
          toastr.success(result.message);
          e.target.reset();
          refreshTables("pricing");
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(error);
        console.trace(error);
      } finally {
        hideLoader();
        addpricingbtn.innerHTML = `SUBMIT <i class="fa fa-paper-plane"></i>`;
        addpricingbtn.disabled = false;
      }
    });

  if (locationform)
    locationform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const addlocationbtn = document.getElementById("addlocationbtn");
        addlocationbtn.innerHTML = "Submitting...";
        addlocationbtn.disabled = true;

        const data = new FormData(e.target);
        const result = await postRequest(`config/addwaybilllocation`, data);

        if (result.status) {
          toastr.success(result.message);
          e.target.reset();
          refreshTables("location");
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(error);
        console.trace(error);
      } finally {
        addlocationbtn.innerHTML = `SUBMIT <i class="fa fa-paper-plane"></i>`;
        addlocationbtn.disabled = false;
        hideLoader();
      }
    });

  if (editpricingform)
    editpricingform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const editpricingbtn = document.getElementById("editpricingbtn");
        editpricingbtn.innerHTML = "Submitting...";
        editpricingbtn.disabled = true;

        const data = new FormData(e.target);
        const result = await postRequest(`config/updatepricing`, data);

        if (result.status) {
          toastr.success(result.message);
          refreshTables("pricing");
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(error);
        console.trace(error);
      } finally {
        editpricingbtn.innerHTML = `SUBMIT <i class="fa fa-paper-plane"></i>`;
        editpricingbtn.disabled = false;
        hideLoader();
      }
    });

  if (editlocationform)
    editlocationform.addEventListener("submit", async e => {
      e.preventDefault();
      try {
        showLoader();
        const editlocationbtn = document.getElementById("editlocationbtn");
        editlocationbtn.innerHTML = "Submitting...";
        editlocationbtn.disabled = true;

        const data = new FormData(e.target);
        const result = await postRequest(`config/updatelocation`, data);

        if (result.status) {
          toastr.success(result.message);
          refreshTables("location");
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(error);
        console.trace(error);
      } finally {
        editlocationbtn.innerHTML = `SUBMIT <i class="fa fa-paper-plane"></i>`;
        editlocationbtn.disabled = false;
        hideLoader();
      }
    });

  if (configurationforms.length > 0) {
    configurationforms.forEach(form => {
      form.addEventListener("submit", async e => {
        e.preventDefault();
        try {
          showLoader();
          const data = new FormData(e.target);
          const result = await postRequest(`config/updateconfig`, data);

          if (result.status) {
            toastr.success(result.message);
          } else {
            toastr.error(result.message);
          }
        } catch ({ message: error }) {
          toastr.error(error);
          console.trace(error);
        } finally {
          hideLoader();
        }
      });
    });
  }
});
