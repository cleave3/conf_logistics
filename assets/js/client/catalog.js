document.addEventListener("DOMContentLoaded", () => {
  const addinventoryform = document.getElementById("addinventoryform");
  const updateinventoryform = document.getElementById("updateinventoryform");
  const delbtn = document.getElementById("delbtn");
  const selectall = document.getElementById("selectall");
  const items = document.querySelectorAll(".items");

  if (items.length > 0) {
    let checked = 0;
    items.forEach(item =>
      item.addEventListener("change", e => {
        e.target.checked ? checked++ : checked > 0 ? checked-- : null;

        delbtn.disabled = checked > 0 ? false : true;
      })
    );
  }

  if (selectall)
    selectall.addEventListener("click", e => {
      if (e.target.getAttribute("data-action") == "select") {
        items.forEach(item => (item.checked = true));
        e.target.setAttribute("data-action", "unselect");
        e.target.innerHTML = "Unselect All";
        delbtn.disabled = false;
      } else {
        items.forEach(item => (item.checked = false));
        e.target.setAttribute("data-action", "select");
        e.target.innerHTML = `Select All  <i class="fa fa-check"></i>`;
        delbtn.disabled = true;
      }
    });

  if (delbtn)
    delbtn.addEventListener("click", () => {
      toastr.confirm("Are you sure you want to delete this item", {
        yes: () => deleteItem(),
      });
    });

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
          toastr.success(result.message);
          e.target.reset();
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.success(result.message);
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
          toastr.success(result.message);
          setTimeout(() => {
            window.location = "/clients/catalog";
          }, 1000);
        } else {
          toastr.error(result.message);
        }
      } catch ({ message: error }) {
        toastr.error(result.message);
      } finally {
        updateinventory.innerHTML = `Update <i class="fa fa-upload" aria-hidden="true"></i>`;
        updateinventory.disabled = false;
      }
    });

  async function loadInventory() {
    try {
      const result = await getRequest("inventory");
      console.log(result);

      let template = "";
      result.data.length > 0 &&
        result.data.map((inventory, i) => {
          template += `<tr role="row">
        <td role="cell" data-label="" class="dropdown dropright">
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ACTIONS</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="/clients/inventory/edit?itemid=${inventory.id}" title="Edit Item">Edit</a>
                    <a class="dropdown-item delbtn" href="#">Delete</a>
                </div>
            </div>
        </td>
        <td role="cell" data-label="S/N : ">${i + 1}</td>
        <td role="cell" data-label="NAME : ">${inventory.name}</td>
        <td role="cell" data-label="UNIT COST : ">${numberFormat(inventory.unit_cost)} </td>
        <td role="cell" data-label="MEASURE : ">${inventory.unit_measure} </td>
        <td role="cell" data-label="QTY : ">${numberFormat(inventory.quantity)} </td>
        <td role="cell" data-label="DESCRIPTION : ">${inventory.description}</td>
        <td role="cell" data-label="CREATED AT : ">${inventory.created_at} </td>
        <td role="cell" data-label="UPDATED AT : ">${!/[a-zA-Z0-9]/.test(inventory.updated_at) ? "" : inventory.updated_at} </td>
    </tr>`;
        });

      document.getElementById("inventorylist").innerHTML = template;
    } catch ({ message: error }) {
      toastr.error(error);
    }
  }

  async function deleteItem() {
    try {
      showLoader();

      const items = document.querySelectorAll(".items");
      if (items.length < 1) throw new Error("please select an item to delete");
      let itemsid = "";
      items.forEach(item => {
        if (item.checked) itemsid += itemsid == "" ? item.value : `,${item.value}`;
      });
      const data = new FormData();
      data.append("items", itemsid);

      const result = await postRequest(`inventory/delete`, data);
      console.log(result);

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
  }
});
