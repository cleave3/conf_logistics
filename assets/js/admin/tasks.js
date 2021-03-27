document.addEventListener("DOMContentLoaded", () => {
  const assignbtn = document.getElementById("assignbtn");
  const submitbtn = document.getElementById("submitbtn");
  const selectall = document.getElementById("selectall");
  const items = document.querySelectorAll(".items");
  const searchbtn = document.getElementById("searchbtn");

  if (items.length > 0) {
    let checked = 0;
    items.forEach(item =>
      item.addEventListener("change", e => {
        e.target.checked ? checked++ : checked > 0 ? checked-- : null;

        assignbtn.disabled = checked > 0 ? false : true;
      })
    );
  }

  if (selectall)
    selectall.addEventListener("click", e => {
      if (e.target.getAttribute("data-action") == "select") {
        items.forEach(item => (item.checked = true));
        e.target.setAttribute("data-action", "unselect");
        e.target.innerHTML = "Unselect All";
        assignbtn.disabled = false;
      } else {
        items.forEach(item => (item.checked = false));
        e.target.setAttribute("data-action", "select");
        e.target.innerHTML = `Select All  <i class="fa fa-check"></i>`;
        assignbtn.disabled = true;
      }
    });

  if (submitbtn)
    submitbtn.addEventListener("click", () => {
      if (document.getElementById("agents").value.trim() === "") {
        document.getElementById("error-div").classList.remove("d-none");
        return;
      }

      if (document.querySelectorAll(".items").length < 1) {
        toastr.warning("please select tasks to assign");
        return;
      }
      document.getElementById("error-div").classList.add("d-none");
      toastr.confirm("Please confirm this tasks assignment", {
        yes: () => assignTasks(),
      });
    });

  if (searchbtn) searchbtn.addEventListener("click", searchTask);

  async function assignTasks() {
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
      data.append("agentid", document.getElementById("agents").value);

      const result = await postRequest(`task/submit`, data);
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

  async function searchTask() {
    try {
      showLoader();
      searchbtn.disabled = true;
      const startdate = document.getElementById("startdate").value;
      const enddate = document.getElementById("enddate").value;
      const status = document.getElementById("status").value;
      const agent = document.getElementById("agents").value;

      const result = await getRequest(`task/search?status=${status}&agent=${agent}&startdate=${startdate}&enddate=${enddate}`);
      renderResults(result.data);
    } catch ({ message: error }) {
      resultcontainer.innerHTML = "There was an error getting your results, Try again. If error persists, contact your administrator";
    } finally {
      searchbtn.disabled = false;
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
            <th role="columnheader">SELLER</th>
            <th role="columnheader">SELLER&nbsp;TEL</th>
            <th role="columnheader">CUSTOMER</th>
            <th role="columnheader">CUSTOMER&nbsp;TEL</th>
            <th role="columnheader">ADDRESS</th>
            <th role="columnheader">ORDER&nbsp;ID</th>
            <th role="columnheader">AMOUNT (₦)</th>
            <th role="columnheader">FEE (₦)</th>
            <th role="columnheader">STATUS</th>
            <th role="columnheader">ASSIGNER</th>
            <th role="columnheader">AGENT</th>
            <th role="columnheader">ASSIGNED&nbsp;AT</th>
        </tr>
    </thead>
    <tbody role="rowgroup">`;

    data.map((task, i) => {
      table += `            <tr role="row">
      <td role="cell" data-label="SN">
          <span>${i + 1}</span>
      </td>
      <td role="cell" data-label="SELLER : ">${task["seller"]}</td>
      <td role="cell" data-label="SELLER TELEPHONE : ">${task["sellertelephone"]}</td>
      <td role="cell" data-label="CUSTOMER : ">${task["customer"]}</td>
      <td role="cell" data-label="CUSTOMER TELEPHONE : ">${task["customertelephone"]}</td>
      <td role="cell" data-label="ADDRESS : ">${task["deliveryaddress"]}</td>
      <td role="cell" data-label="ORDER ID : ">#${task["order_id"]}</td>
      <td role="cell" data-label="AMOUNT : ">${number_format(task["totalamount"])}</td>
      <td role="cell" data-label="FEE : ">${number_format(task["delivery_fee"])}</td>
      <td data-label="STATUS">
          <span class="text-uppercase badge badge-${determineClass(task["orderstatus"])} p-2">${task["orderstatus"]}</span>
      </td>
      <td role="cell" data-label="ASSIGNER : ">${task["assigner"]}</td>
      <td role="cell" data-label="AGENT : ">${task["assignee"]}</td>
      <td role="cell" data-label="CREATED AT : ">${task["created_at"]}</td>
  </tr>`;
    });

    table += `</tbody></table>`;
    resultcontainer.innerHTML = table;

    $("#resulttable").DataTable({
      fixedHeader: true,
      order: [[10, "desc"]],
    });
  }
});
