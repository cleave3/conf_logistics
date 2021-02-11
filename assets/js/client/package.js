const addpackageitem = document.getElementById("addpackageitem");

if (addpackageitem) addpackageitem.addEventListener("click", addPackage);

function addPackage(e) {
  e.preventDefault();
  const unique_id = "id" + Math.random() * 999999 + Math.random() * 999999;
  const itemtemplate = `
    <div style="position: relative;" class="row border border-light mt-2" id="${unique_id}">
        <div class="col-md-5">
            <div class="form-group">
                <label>Item</label>                
                <select type="text" class="custom-select" name="item[]" required>
                <option value="">--SELECT ITEM--</option>
            </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Unit Cost</label>
                <input type="text" class="form-control" placeholder="Enter unit cost of item" name="cost[]" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" min="1" class="form-control" placeholder="Enter Item quantity" name="quantity[]" required>
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
