const searchbtn = document.getElementById("searchbtn");

async function searchTransaction() {
  try {
    showLoader();
    searchbtn.disabled = true;
    const startdate = document.getElementById("startdate").value;
    const enddate = document.getElementById("enddate").value;
    const status = document.getElementById("status").value;
    const type = document.getElementById("type").value;

    const result = await getRequest(`transaction/clienttransactions?status=${status}&type=${type}&startdate=${startdate}&enddate=${enddate}`);
    renderResults(result.data);
  } catch ({ message: error }) {
    console.log(error);
    document.getElementById("result-container").innerHTML =
      "There was an error getting your results, Try again. If error persists, contact your administrator";
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
              <th role="columnheader">REFERENCE</th>
              <th role="columnheader">TYPE</th>
              <th role="columnheader">DEBIT (₦)</th>
              <th role="columnheader">CREDIT (₦)</th>
              <th role="columnheader">STATUS</th>
              <th role="columnheader">DESCRIPTION</th>
              <th role="columnheader">CREATED AT</th>
              <th role="columnheader">UPDATED AT</th>
          </tr>
      </thead>
      <tbody role="rowgroup">`;
  data.map((transaction, i) => {
    table += `<tr role="row">
        <td role="cell" data-label="SN"><span>${i + 1}</span></td>
        <td role="cell" data-label="REFERENCE : ">${transaction["reference"].toUpperCase()}</td>
        <td role="cell" data-label="TYPE : ">${transaction["type"]}</td>
        <td role="cell" class='text-danger' data-label="DEBIT : ">${transaction["debit"] == 0 ? "-" : `-${number_format(transaction["debit"])}`}</td>
        <td role="cell" data-label="CREDIT : ">${transaction["credit"] == 0 ? "-" : number_format(transaction["credit"])}</td>
        <td data-label="STATUS">
            <span class="text-uppercase badge badge-${determineClass(transaction["status"])} p-2">${transaction["status"]}</span>
        </td>
        <td role="cell" data-label="DESCRIPTION : ">${transaction["description"]}</td>
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

if (searchbtn) searchbtn.addEventListener("click", searchTransaction);
