const BASE_URL = "http://localhost:8080/api";

async function postRequest(url, data, headers = new Headers()) {
  try {
    const res = await fetch(`${BASE_URL}/${url}`, { method: "POST", headers, body: data });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    toastr.error("Oops something went wrong");
  }
}

async function getRequest(url, headers = new Headers()) {
  try {
    const res = await fetch(`${BASE_URL}/${url}`, { method: "GET", headers });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    toastr.error("Oops something went wrong");
  }
}

async function loadcities(state) {
  const result = await getRequest(`public/cities?state=${state}`);
  let cities = "";
  result.status && result.data.map(data => (cities += `<option value="${data.city}">${data.city}</option>`));
  return cities;
}

async function getdeliveryfee() {
  const result = await getRequest(`config/getDeliveryFee`);
  return result.data;
}

async function getitems() {
  const result = await getRequest(`package/getitems`);
  return result.data;
}

async function clientitems() {
  const result = await getRequest(`inventory`);
  let items = `<option value="">--SELECT ITEM--</option>`;
  if (result.status) {
    for (let i = 0; i < result.data.length; i++) {
      items += `<option value="${result.data[i].id}">${result.data[i].name}</option>`;
    }
  }
  return items;
}

function numberFormat(value) {
  const result = new Intl.NumberFormat("en-US", { style: "currency", currency: "NGN", minimumFractionDigits: 2 }).format(value);
  return result;
}

function number_format(value) {
  const result = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(value);
  return result;
}

function formatCurrencyInput(inputs) {
  if (inputs.length < 1) return;
  inputs.forEach(input => {
    let format = new Cleave(input, {
      numeral: true,
      numeralThousandsGroupStyle: "thousand",
    });
  });
}

function determineClass(status) {
  switch (status) {
    case "onhand":
    case "pending":
      return "text-warning";
    case "sent":
      return "text-primary";
    case "recieved":
    case "active":
      return "text-success";
    case "deactivated":
    case "suspended":
    case "inactive":
      return "text-danger";
    default:
      return "text-dark";
  }
}

// function makePDF(element, filename = "document") {
//   const doc = new jsPDF();
//   doc.html(document.querySelector(element), {
//     callback: function (doc) {
//       doc.save();
//     },
//     margin: 5,
//     filename: `${filename}.pdf`,
//   });
// }

const showLoader = () => document.getElementById("loader").classList.remove("d-none");
const hideLoader = () => document.getElementById("loader").classList.add("d-none");
