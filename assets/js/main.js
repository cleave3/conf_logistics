function notify(type, message) {
  $.notify(
    {
      icon: "now-ui-icons ui-1_bell-53",
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
    const res = await fetch(url, { method: "POST", headers, body: data });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    notify("danger", "Oops something went wrong");
  }
}

async function getRequest(url, headers = new Headers()) {
  try {
    const res = await fetch(url, { method: "GET", headers });
    const result = await res.json();
    return result;
  } catch ({ message: error }) {
    console.trace(error);
    notify("danger", "Oops something went wrong");
  }
}
