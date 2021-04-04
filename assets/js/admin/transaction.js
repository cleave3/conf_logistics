const type = document.getElementById("type");
const addmore = document.getElementById("addmore");
const savebtn = document.getElementById("savebtn");
const addtransactionform = document.getElementById("addtransactionform");
const searchbtn = document.getElementById("searchbtn");
const searchpayment = document.getElementById("searchpayment");

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

async function searchTransaction() {
  try {
    showLoader();
    searchbtn.disabled = true;
    const startdate = document.getElementById("startdate").value;
    const enddate = document.getElementById("enddate").value;
    const status = document.getElementById("status").value;
    const target = document.getElementById("target").value;
    const type = document.getElementById("type").value;

    const result = await getRequest(`transaction/search?status=${status}&type=${type}&target=${target}&startdate=${startdate}&enddate=${enddate}`);
    renderResults(result.data);
  } catch ({ message: error }) {
    document.getElementById("result-container").innerHTML =
      "There was an error getting your results, Try again. If error persists, contact your administrator";
  } finally {
    searchbtn.disabled = false;
    hideLoader();
  }
}

async function searchPayments() {
  try {
    showLoader();
    searchpayment.disabled = true;
    const startdate = document.getElementById("startdate").value;
    const enddate = document.getElementById("enddate").value;
    const status = document.getElementById("status").value;
    const target = document.getElementById("target").value;

    const result = await getRequest(`transaction/payments?status=${status}&target=${target}&startdate=${startdate}&enddate=${enddate}`);
    renderPayments(result.data);
  } catch ({ message: error }) {
    console.log(error);
    document.getElementById("result-container").innerHTML =
      "There was an error getting your results, Try again. If error persists, contact your administrator";
  } finally {
    searchpayment.disabled = false;
    hideLoader();
  }
}

function renderResults(data) {
  const resultcontainer = document.getElementById("result-container");
  resultcontainer.innerHTML = "";

  if (data.length < 1) {
    resultcontainer.innerHTML = `<div class="d-flex justify-content-center align-items-center my-5" style="height: 300px;">
            <div>
                <p class="text-center font-weight-bold">Your Search returned 0 results</p>
                <img src="/assets/icons/empty.svg" class="img-fluid" width="200px" height="200px"/>
            </div>
      </div>`;
    return;
  }
  let table = `<table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
    <thead role="rowgroup">
        <tr role="row">
            <th role="columnheader">S/N</th>
            <th role="columnheader">RECIPIENT</th>
            <th role="columnheader">REFERENCE</th>
            <th role="columnheader">TYPE</th>
            <th role="columnheader">DEBIT (₦)</th>
            <th role="columnheader">CREDIT (₦)</th>
            <th role="columnheader">STATUS</th>
            <th role="columnheader">DESCRIPTION</th>
            <th role="columnheader">INITIATOR</th>
            <th role="columnheader">CREATED AT</th>
            <th role="columnheader">UPDATED AT</th>
        </tr>
    </thead>
    <tbody role="rowgroup">`;
  data.map((transaction, i) => {
    table += `<tr role="row">
      <td role="cell" data-label="SN"><span>${i + 1}</span></td>
      <td role="cell" data-label="RECIPIENT : ">${transaction["client"] || transaction["agent"]}</td>
      <td role="cell" data-label="REFERENCE : ">${transaction["reference"]}</td>
      <td role="cell" data-label="TYPE : ">${transaction["type"]}</td>
      <td role="cell" data-label="DEBIT : ">${transaction["debit"] == 0 ? "-" : number_format(transaction["debit"])}</td>
      <td role="cell" data-label="CREDIT : ">${transaction["credit"] == 0 ? "-" : number_format(transaction["credit"])}</td>
      <td data-label="STATUS">
          <span class="text-uppercase badge badge-${determineClass(transaction["status"])} p-2">${transaction["status"]}</span>
      </td>
      <td role="cell" data-label="DESCRIPTION : ">${transaction["description"]}</td>
      <td role="cell" data-label="INITIATOR : ">${transaction["initiator"]}</td>
      <td role="cell" data-label="CREATED AT : ">${transaction["created_at"]}</td>
      <td role="cell" data-label="UPDATED AT : ">${!transaction["updated_at"] ? "never" : transaction["updated_at"]}</td>
  </tr>`;
  });

  table += `</tbody></table>`;
  resultcontainer.innerHTML = table;

  $("#resulttable").DataTable({
    fixedHeader: true,
    order: [[10, "desc"]],
  });
}

function renderPayments(data) {
  const resultcontainer = document.getElementById("result-container");

  resultcontainer.innerHTML = "";

  if (data.length < 1) {
    resultcontainer.innerHTML = `<div class="d-flex justify-content-center align-items-center my-5" style="height: 300px;">
            <div>
                <p class="text-center font-weight-bold">Your Search returned 0 results</p>
                <img src="/assets/icons/empty.svg" class="img-fluid" width="200px" height="200px"/>
            </div>
      </div>`;
    return;
  }
  let table = `<table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
    <thead role="rowgroup">
        <tr role="row">
            <th role="columnheader">S/N</th>
            <th role="columnheader">RECIPIENT</th>
            <th role="columnheader">REFERENCE</th>
            <th role="columnheader">AMOUNT (₦)</th>
            <th role="columnheader">STATUS</th>
            <th role="columnheader">DESCRIPTION</th>
            <th role="columnheader">INITIATOR</th>
            <th role="columnheader">CREATED AT</th>
            <th role="columnheader">UPDATED AT</th>
        </tr>
    </thead>
    <tbody role="rowgroup">`;
  data.map((transaction, i) => {
    table += `<tr role="row">
      <td role="cell" data-label="SN"><span>${i + 1}</span></td>
      <td role="cell" data-label="RECIPIENT: ">${transaction["client"] || transaction["agent"]}</td>
      <td role="cell" data-label="REFERENCE : ">${transaction["reference"]}</td>
      <td role="cell" data-label="AMOUNT : ">${number_format(transaction["debit"])}</td>
      <td data-label="STATUS">
          <span class="text-uppercase badge badge-${determineClass(transaction["status"])} p-2">${transaction["status"]}</span>
      </td>
      <td role="cell" data-label="DESCRIPTION : ">${transaction["description"]}</td>
      <td role="cell" data-label="INITIATOR : ">${transaction["initiator"]}</td>
      <td role="cell" data-label="CREATED AT : ">${transaction["created_at"]}</td>
      <td role="cell" data-label="UPDATED AT : ">${!transaction["updated_at"] ? "never" : transaction["updated_at"]}</td>
  </tr>`;
  });

  table += `</tbody></table>`;
  resultcontainer.innerHTML = table;

  $("#resulttable").DataTable({
    fixedHeader: true,
    order: [[7, "desc"]],
  });
}

if (savebtn)
  savebtn.addEventListener("click", e => {
    e.preventDefault();
    addtransactionform.classList.add("was-validated");
    if (!addtransactionform.checkValidity()) return;
    confirmSubmit();
  });

if (searchbtn) searchbtn.addEventListener("click", searchTransaction);
if (searchpayment) searchpayment.addEventListener("click", searchPayments);
