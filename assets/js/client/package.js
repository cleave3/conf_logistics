const addpackageitem = document.getElementById("addpackageitem");
const registerpackageform = document.getElementById("registerpackageform");

if (addpackageitem) addpackageitem.addEventListener("click", addPackage);

function addPackage(e) {
  e.preventDefault();
  const items = document.getElementById("items");
  const unique_id = "id" + Math.random() * 999999 + Math.random() * 999999;
  const itemtemplate = `
    <div style="position: relative;" class="row border border-light mt-2" id="${unique_id}">
        <div class="col-md-5">
            <div class="form-group">
                <label>Item</label>                
                <select type="text" class="custom-select inventory-items" name="item[]" required>
                ${items.innerHTML}
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
    showLoader();
    const itemdetail = await getRequest(`inventory/item?itemid=${e.target.value}`);
    hideLoader();
    if (itemdetail.status) {
      e.target.parentElement.offsetParent.nextElementSibling.firstElementChild.children[1].value = itemdetail.data.unit_cost;
      e.target.parentElement.offsetParent.nextElementSibling.nextElementSibling.children[0].children[1].setAttribute(
        "data-qty",
        Number(itemdetail.data.quantity)
      );
    }
  }
});

document.addEventListener("input", e => {
  if (e.target.classList.contains("qty")) {
    if (Number(e.target.getAttribute("data-qty")) < Number(e.target.value)) {
      toastr.warning(`Only ${Number(e.target.getAttribute("data-qty"))} of this item is left. You can't send more item than you have in stock`);
      e.target.value = "";
    }
  }
});

if (registerpackageform)
  registerpackageform.addEventListener("submit", async e => {
    e.preventDefault();
    try {
      showLoader();
      const registerpackagebtn = document.getElementById("registerpackagebtn");

      registerpackagebtn.innerHTML = "Submitting...";
      registerpackagebtn.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest("package/add", data);
      console.log(result);

      if (result.status) {
        toastr.success(result.message);
        toastr.confirm("Have you sent this package ?", {
          yes: () => sendPackage(result.data.packageid),
        });
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.success(result.message);
    } finally {
      hideLoader();
      registerpackagebtn.innerHTML = "Submit";
      registerpackagebtn.disabled = false;
    }
  });
