const addpackageitem = document.getElementById("addpackageitem");
const registerpackageform = document.getElementById("registerpackageform");
const editpackageform = document.getElementById("editpackageform");
// const downloaddetails = document.getElementById("downloaddetails");

if (addpackageitem) addpackageitem.addEventListener("click", addPackage);
let items;
(async () => {
  items = await clientitems();
})();
function addPackage(e) {
  e.preventDefault();
  const unique_id = "id" + Math.random() * 999999 + Math.random() * 999999;
  const itemtemplate = `
    <div style="position: relative;" class="row border border-light mt-2" id="${unique_id}">
        <div class="col-md-5">
            <div class="form-group">
                <label>Item</label>                
                <select type="text" class="custom-select inventory-items" name="item[]" required>
                ${items}
            </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Unit Cost</label>
                <input type="text" class="form-control unit-cost" placeholder="Enter unit cost of item" name="cost[]" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" min="1" class="form-control qty" placeholder="Enter Item quantity" name="quantity[]" required>
            </div>
        </div>
            <i data-id="${unique_id}" class="removebtn btn btn-sm btn-danger fa fa-trash" style="position: absolute;   top: -40px;left: 50px"></i>
    </div>`;

  document.getElementById("package-items").insertAdjacentHTML("beforeend", itemtemplate);
}

document.addEventListener("click", e => {
  if (e.target.classList.contains("removebtn")) {
    removeItem(e.target.getAttribute("data-id"));
  }
});

function removeItem(id) {
  document.getElementById("package-items").removeChild(document.getElementById(id));
}

document.addEventListener("change", async e => {
  if (e.target.classList.contains("inventory-items")) {
    const inventoryitems = document.querySelectorAll(".inventory-items");

    let exist = 0;
    if (inventoryitems.length > 0) {
      inventoryitems.forEach(item => {
        if (e.target.value === item.value && e.target.id !== item.id) {
          exist++;
        }
      });
    }
    if (exist > 0) {
      toastr.info("You have already added this item");
      return;
    }

    showLoader();
    const itemdetail = await getRequest(`inventory/item?itemid=${e.target.value}`);
    hideLoader();
    if (itemdetail.status) {
      e.target.parentElement.offsetParent.nextElementSibling.firstElementChild.children[1].value = itemdetail.data.unit_cost;
    }
  }
});

if (registerpackageform)
  registerpackageform.addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const inventoryitems = document.querySelectorAll(".inventory-items");

      let items = [];
      let exist = 0;
      if (inventoryitems.length > 0) {
        inventoryitems.forEach(item => {
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
      const registerpackagebtn = document.getElementById("registerpackagebtn");

      registerpackagebtn.innerHTML = "Submitting...";
      registerpackagebtn.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest("package/add", data);

      if (result.status) {
        toastr.success(result.message);
        toastr.confirm("Have you sent this package ?", {
          yes: () => sendPackage(result.data.packageid),
        });
        registerpackageform.reset();
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(result.message);
    } finally {
      hideLoader();
      registerpackagebtn.innerHTML = "Submit";
      registerpackagebtn.disabled = false;
    }
  });

async function sendPackage(packageid, reload = false) {
  try {
    showLoader();

    const data = new FormData();
    data.append("status", "sent");
    data.append("packageid", packageid);

    const result = await postRequest("package/edit", data);

    if (result.status) {
      toastr.success(result.message);
      reload && window.location.reload();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(result.message);
  } finally {
    hideLoader();
  }
}

if (editpackageform)
  editpackageform.addEventListener("submit", async e => {
    try {
      e.preventDefault();
      showLoader();
      const data = new FormData(e.target);
      const result = await postRequest("package/edit", data);

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
  });

async function sendPackageNow(packageid) {
  toastr.confirm("Are you sure you want to send this package now ?", { yes: () => sendPackage(packageid, true) });
}

async function deletePackage(packageid) {
  try {
    console.log("here");
    showLoader();

    const data = new FormData();
    data.append("packageid", packageid);

    const result = await postRequest("package/delete", data);

    if (result.status) {
      toastr.success(result.message);
      window.location.reload();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(result.message);
  } finally {
    hideLoader();
  }
}

async function removePackageNow(packageid) {
  toastr.confirm("Are you sure you want to delete this package ?", { yes: () => deletePackage(packageid) });
}
// if (downloaddetails) downloaddetails.addEventListener("click", () => makePDF(".waybill", "waybillinfo"));
