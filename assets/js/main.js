const BASE_URL = "http://localhost:8080/api";

function notify(type, message) {
  $.notify(
    {
      icon: "nc-icon nc-bell-55",
      message: message,
    },
    {
      type,
      timer: 4000,
      placement: { from: "top", align: "right" },
    }
  );
}

async function postRequest(url, data, headers = new Headers()) {
  try {
    const res = await fetch(`${BASE_URL}/${url}`, { method: "POST", headers, body: data });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    notify("danger", "Oops something went wrong");
  }
}

async function getRequest(url, headers = new Headers()) {
  try {
    const res = await fetch(`${BASE_URL}/${url}`, { method: "GET", headers });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    notify("danger", "Oops something went wrong");
  }
}

async function loadcities(state) {
  const result = await getRequest(`public/cities?state=${state}`);
  let cities = "";
  result.status && result.data.map(data => (cities += `<option value="${data.city}">${data.city}</option>`));
  return cities;
}