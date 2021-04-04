const type = document.getElementById("type");
const addmore = document.getElementById("addmore");
const savebtn = document.getElementById("savebtn");
const addtransactionform = document.getElementById("addtransactionform");

if (type)
  type.addEventListener("change", async e => {
    try {
      showLoader();

      const result = await getRequest(`transaction/entities?type=${e.target.value}`);

      let options = `<option value="" selected>--select ${e.target.value}--</option>`;
      if (result.status) {
        for (let i = 0; i < result.data.length; i++) {
          options += `<option value="${result.data[i].id}">${result.data[i].name}</option>`;
        }
      }
      entity.innerHTML = options;
    } catch ({ message: error }) {
      console.error(error);
    } finally {
      hideLoader();
    }
  });

function addMore() {
  const unique_id = "id" + Math.random() * 999999 + Math.random() * 999999;
  const template = `
    <div class="col-12 mx-auto row mt-2" id="${unique_id}">
    <div class="col-md-2">
  <div class="form-group">
      <label>Type</label>
      <select name="type[]" class="custom-select" required="">
          <option value="">--transaction type--</option>
          <option value="delivery_charge">Delivery Charge</option>
          <option value="waybill_charge">Waybill Charge</option>
          <option value="delivered_order">Delivered Order</option>
          <option value="payment">Payment</option>
          <option value="other_credit">Other Credit</option>
          <option value="other_debit">Other Debit</option>
       </select>
  </div>
</div>
<div class="col-md-3">
<div class="form-group">
    <label>Amount</label>
    <input type="text" name="amount[]" placeholder="0.00" class="form-control" required>
</div>
</div>
<div class="col-md-7">
<div class="form-group">
    <label>Description</label>
    <div class="input-group">
    <input type="text" name="description[]" placeholder="description.." class="form-control" required>
    <div class="input-group-append">
    <span class="btn btn-danger m-0 fa fa-times removebtn" data-id="${unique_id}"></span>
    </div>
</div>
    </div>
</div>
</div>`;

  document.getElementById("detail-container").insertAdjacentHTML("beforeend", template);
}
function removeItem(id) {
  document.getElementById("detail-container").removeChild(document.getElementById(id));
}

document.addEventListener("click", e => {
  if (e.target.classList.contains("removebtn")) {
    removeItem(e.target.getAttribute("data-id"));
  }
});

if (addmore)
  addmore.addEventListener("click", e => {
    e.preventDefault();
    addMore();
  });

const saveTransaction = async () => {
  try {
    showLoader();
    savebtn.innerHTML = "Saving Transaction...";
    savebtn.disabled = true;

    const data = new FormData(addtransactionform);

    const result = await postRequest(`transaction/addtransaction`, data);

    if (result.status) {
      toastr.success(result.message);
      addtransactionform.reset();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.error(error);
  } finally {
    addtransactionform.classList.remove("was-validated");
    savebtn.innerHTML = `Save Transaction <i class="fas fa-save"></i>`;
    savebtn.disabled = false;
    hideLoader();
  }
};

const confirmSubmit = () => {
  toastr.confirm("Are you sure you want to save this transaction ?", {
    yes: () => saveTransaction(),
  });
};

if (savebtn)
  savebtn.addEventListener("click", e => {
    e.preventDefault();
    addtransactionform.classList.add("was-validated");
    if (!addtransactionform.checkValidity()) return;
    confirmSubmit();
  });
