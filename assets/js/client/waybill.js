document.addEventListener("DOMContentLoaded", async () => {
  const addwaybillitem = document.getElementById("addwaybillitem");
  const registerwaybillform = document.getElementById("registerwaybillform");
  const editwaybillform = document.getElementById("editwaybillform");
  const state = document.getElementById("state");
  const additemform = document.getElementById("additemform");

  const itemlist = await getitems();

  if (addwaybillitem) addwaybillitem.addEventListener("click", addItem);

  if (state)
    state.addEventListener("change", async e => {
      try {
        if (e.target.value.trim() === "") return;
        showLoader();
        const result = await getRequest(`public/state?stateid=${e.target.value}`);
        if (result.status) {
          document.getElementById("waybillfee").innerHTML = `<b>WAYBILL FEE:</b> ${numberFormat(result.data.waybill_charge)}`;
        }
      } catch ({ message: error }) {
        console.error(error);
      } finally {
        hideLoader();
      }
    });

  function addItem(e) {
    e.preventDefault();
    const unique_id = "id" + Math.random() * 999999 + Math.random() * 999999;

    let items = "<option selected value=''>--SELECT ITEM--</option>";

    if (itemlist.length > 0) {
      for (let i = 0; i < itemlist.length; i++) {
        items += `<option value="${itemlist[i]["item_id"]}">${itemlist[i]["name"]}</option>`;
      }
    }

    const itemtemplate = `
    <div style="position: relative;" class="row border border-light mt-2" id="${unique_id}">
        <div class="col-md-6">
            <div class="form-group">
                <label>Item</label>                
                <select type="text" class="custom-select waybill-items" name="item[]" required>
                ${items}
            </select>
            <div class="text-right"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" min="1" class="form-control qty" placeholder="Enter Item quantity" name="quantity[]" required>
            </div>
        </div>
            <i data-id="${unique_id}" class="removebtn btn btn-sm btn-danger fa fa-trash" style="position: absolute;   top: -40px;left: 15px"></i>
    </div>`;

    document.getElementById("waybill-items").insertAdjacentHTML("beforeend", itemtemplate);
  }

  document.addEventListener("click", e => {
    if (e.target.classList.contains("removebtn")) {
      removeItem(e.target.getAttribute("data-id"));
    }
  });

  function removeItem(id) {
    document.getElementById("waybill-items").removeChild(document.getElementById(id));
  }

  document.addEventListener("change", async e => {
    if (e.target.classList.contains("waybill-items")) {
      const waybillitems = document.querySelectorAll(".waybill-items");

      let exist = 0;
      if (waybillitems.length > 0) {
        waybillitems.forEach(item => {
          if (e.target.value === item.value && e.target.id !== item.id) {
            exist++;
          }
        });
      }
      if (exist > 0) {
        toastr.info("You have already added this item");
        e.target.nextElementSibling.innerHTML = "";
        return;
      }

      const item = itemlist.find(item => item.item_id == e.target.value);
      currentitem = item;

      e.target.nextElementSibling.innerHTML = `Quantity left = ${item.quantity}`;
    }
  });

  if (registerwaybillform)
    registerwaybillform.addEventListener("submit", async e => {
      try {
        e.preventDefault();
        e.currentTarget.classList.add("was-validated");
        if (!e.target.checkValidity()) return;
        const waybillitems = document.querySelectorAll(".waybill-items");

        let items = [];
        let exist = 0;
        if (waybillitems.length > 0) {
          waybillitems.forEach(item => {
            items.push(item.value);
          });
        }

        items.map((item, i) => {
          if (items.indexOf(item) !== i) {
            exist++;
          }
        });
        if (exist > 0) {
          toastr.info("You have the same items in your item list");
          return;
        }

        showLoader();
        const registerwaybillbtn = document.getElementById("registerwaybillbtn");

        registerwaybillbtn.innerHTML = "Submitting...";
        registerwaybillbtn.disabled = true;

        const data = new FormData(e.target);

        const result = await postRequest("waybill/add", data);

        if (result.status) {
          toastr.success(result.message);
          registerwaybillform.reset();
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(result.message);
      } finally {
        hideLoader();
        registerwaybillbtn.innerHTML = "Submit";
        registerwaybillbtn.disabled = false;
      }
    });

  if (editwaybillform)
    editwaybillform.addEventListener("submit", async e => {
      try {
        e.preventDefault();
        e.currentTarget.classList.add("was-validated");
        if (!e.target.checkValidity()) return;
        const waybillitems = document.querySelectorAll(".waybill-items");

        showLoader();
        const editwaybillbtn = document.getElementById("editwaybillbtn");

        const data = new FormData(e.target);
        const result = await postRequest("waybill/clientupdatewaybillitem", data);

        if (result.status) {
          toastr.success(result.message);
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        console.error(error);
      } finally {
        hideLoader();
        editwaybillbtn.innerHTML = "Save Changes";
        editwaybillbtn.disabled = false;
      }
    });

  document.addEventListener("click", e => {
    if (e.target.classList.contains("delwaybill")) {
      confirmDeleted(e.target.getAttribute("data-id"));
    }

    if (e.target.id === "cancelbtn") {
      toastr.confirm("Are you sure you want to cancel this waybill ?", {
        yes: () => cancelWaybill(e.target.getAttribute("data-waybillid")),
      });
    }
  });

  const confirmDeleted = id => {
    toastr.confirm("Are you sure you want to remove this item from the waybill list ?", {
      yes: async () => await deletePackage(id),
    });
  };

  async function deletePackage(id) {
    try {
      showLoader();

      const data = new FormData();
      data.append("id", id);

      const result = await postRequest("waybill/deletewaybillitem", data);

      if (result.status) {
        toastr.success(result.message);
        removeItem(id);
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      console.error(error);
    } finally {
      hideLoader();
    }
  }

  if (additemform)
    additemform.addEventListener("submit", async e => {
      try {
        e.preventDefault();
        e.currentTarget.classList.add("was-validated");
        if (!e.target.checkValidity()) return;

        showLoader();
        const additembtn = document.getElementById("additembtn");

        additembtn.innerHTML = "Submitting...";
        additembtn.disabled = true;

        const data = new FormData(e.target);

        const result = await postRequest("waybill/additem", data);

        if (result.status) {
          toastr.success(result.message);
          const waybillitems = document.querySelector("#waybill-items");

          const template = `<div class="row border border-light mt-2" style="position: relative;" id="${result.data.id}">
          <div class="col-md-6">
              <div class="form-group">
                  <label>Item</label>
                  <input type="text" class="form-control" value="${result.data.name}" readonly>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label>Quantity</label>
                  <div class="input-group">
                      <input type="number" class="form-control" value="${result.data.quantity}" readonly>
                      <div class="input-group-append">
                          <span class="btn btn-danger m-0 delwaybill" data-id="${result.data.id}"><i data-id="${result.data.id}" class="delwaybill fa fa-times" aria-hidden="true"></i></span>
                      </div>
                  </div>
              </div>
          </div>
      </div>`;

          waybillitems.insertAdjacentHTML("beforeend", template);
          additemform.reset();
          document.getElementById("qtydiv").innerHTML = "";
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        console.error(error);
      } finally {
        hideLoader();
        additembtn.innerHTML = "Submit";
        additembtn.disabled = false;
      }
    });

  const cancelWaybill = async id => {
    try {
      showLoader();
      const result = await getRequest(`waybill/cancelwaybill?id=${id}`);

      if (result.status) {
        toastr.success(result.message);
        window.location = "/clients/waybill";
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(error);
    } finally {
      hideLoader();
    }
  };
});
