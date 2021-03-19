document.addEventListener("DOMContentLoaded", async () => {
  const state = document.getElementById("state");
  const city = document.getElementById("city");
  const items = document.getElementById("items");
  const orderform = document.getElementById("orderitemform");
  const registerorderform = document.getElementById("registerorderform");

  let prices = [];
  let orderitems = [];
  let waybillitems = [];
  const deliveryfee = await getdeliveryfee();
  const itemlist = await getitems();
  let extracharge = 0;
  let waybillfee = 0;
  let bckwaybill = 0;
  let selecteditem = null;
  let removing = false;

  calculateOrderDetails();

  document.addEventListener("click", e => {
    if (e.target.id == "submitorder") {
      confirmOrder();
    }

    if (e.target.classList.contains("removeitem")) {
      removeItem(e.target.getAttribute("data-id"));
    }

    if (e.target.id === "cancelbtn") {
      toastr.confirm("Are you sure you want to cancel this order ?", {
        yes: () => cancelOrder(e.target.getAttribute("data-orderid")),
      });
    }

    if (e.target.id === "verifybtn") {
      toastr.confirm("Have you recieved this payment ?", {
        yes: () => verifyPayment(e.target.getAttribute("data-orderid")),
      });
    }
  });

  function confirmOrder() {
    try {
      if (!registerorderform.reportValidity()) return;
      if (orderitems.length < 1) throw new Error("No item has been selected");
      toastr.confirm("Are you sure you want to send this order", { yes: () => submitOrder() });
    } catch ({ message }) {
      toastr.error(message);
    }
  }

  function determineAdditionalWayBillFee() {
    if (selecteditem !== null || state.value.trim() !== "") {
      const item = itemlist.find(item => item.item_id === selecteditem.item_id);
      if (state.value !== item.itemstate_id) {
        waybillfee = bckwaybill;
        if (!removing) {
          waybillfee += Number(item.waybillfee);
        }
        removing = false;
        waybillitems.push(item.name);
      } else {
        waybillitems = [];
      }
    }
    calculateOrderDetails();
  }

  if (state)
    state.addEventListener("change", async e => {
      e.preventDefault();
      try {
        extracharge = 0;
        waybillfee = 0;
        calculateOrderDetails();
        showLoader();
        const result = await getRequest(`public/deliverypricing?stateid=${e.target.value}`);
        console.log(result);
        prices = result.data;
        let options = `<option value="">--SELECT CITY--</option>`;
        for (let i = 0; i < result.data.length; i++) {
          options += `<option value=${result.data[i].id}>${result.data[i].city}</option>`;
        }
        city.innerHTML = options;
      } catch ({ message: error }) {
        console.log(error);
      } finally {
        hideLoader();
      }
    });

  if (city)
    city.addEventListener("change", e => {
      if (e.target.value.trim() === "") return;
      const price = prices.find(p => p.id === e.target.value);
      extracharge = price.extra_charge;
      // waybillfee = Number(price.waybill_charge);
      bckwaybill = waybillfee;
      calculateOrderDetails();
    });

  function calculateOrderDetails() {
    try {
      let detail = "";
      let total = 0;
      let charges = Number(waybillfee) + Number(deliveryfee) + Number(extracharge);
      if (orderitems.length > 0) {
        detail += `<table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Item</th>
                    <th>Price (₦)</th>
                    <th>Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>`;
        for (let i = 0; i < orderitems.length; i++) {
          total += orderitems[i].quantity * orderitems[i].price;
          detail += `
              <tr>
              <td>
              <button title="remove item from list" class="btn btn-sm btn-outline-danger removeitem" data-id="${orderitems[i].itemid}">
              <i class="fa fa-minus removeitem" data-id="${orderitems[i].itemid}"aria-hidden="true"></i>
              </button>
              </td>
                  <td>${orderitems[i].item}</td>
                  <td>${number_format(orderitems[i].price)}</td>
                  <td>${orderitems[i].quantity}</td>
                  <td class="text-right">${number_format(orderitems[i].quantity * orderitems[i].price)}</td>
              </tr>`;
        }
        detail += `<tr>
      <td colspan='4'>Sub Total</td>
      <td class="text-right font-weight-bold">${number_format(total)}</td>
      </tr>`;
        detail += `</tbody></table>`;
      }
      const amountpayable = Number(total) - Number(charges);
      detail += `<div class="border-bottom px-1 py-3">
    <div class="d-flex justify-content-between flex-wrap">
        <div class="">Base Fee</div>
        <div class="font-weight-bold text-danger"> -${number_format(deliveryfee)}</div>
    </div>
    </div>
    <div class="border-bottom px-1 py-3">
    <div class="d-flex justify-content-between flex-wrap">
        <div class="">City Based Extra Charge</div>
        <div class="font-weight-bold text-danger"> -${number_format(Number(extracharge))}</div>
    </div>
    </div>`;
      if (Number(waybillfee) > 0) {
        detail += `<div class="border-bottom px-1 py-3">
        <small class="text-muted">Kindly note that you are been charge a waybill fee because some of your items are not currently in this delivery state</small>
        <div class="d-flex justify-content-between flex-wrap">
            <div class="">Addition Waybill Fee</div>
            <div class="font-weight-bold text-danger"> -${number_format(Number(waybillfee))}</div>
        </div></div>`;
      }
      detail += `<div class="border-bottom px-1 py-3">
    <div class="d-flex justify-content-between flex-wrap">
        <div class="">Total Delivery Fee</div>
        <div class="font-weight-bold text-danger"> -${number_format(charges)}</div>
    </div>
    </div>
    <div class="px-1 py-3">
    <div class="d-flex justify-content-between flex-wrap">
        <div class="font-weight-bold" style="font-size: 1.2rem">Balance</div>
        <div style="font-size: 1.2rem" class="font-weight-bold">${numberFormat(amountpayable)}</div>
    </div>
    </div>`;

      detail += `<button class="btn btn-dark w-100 p-4 shadow" id="submitorder">Submit Order <i class="fa fa-paper-plane" aria-hidden="true"></i></button>`;
      document.getElementById("order-summary-container").innerHTML = detail;
    } catch ({ message }) {
      console.log(message);
    }
  }

  if (items)
    items.addEventListener("change", async e => {
      if (e.target.value.trim() === "") return;
      selecteditem = itemlist.find(i => i.item_id == e.target.value);
      document.getElementById("amount").value = selecteditem.unit_cost;
      document.getElementById("qtyleft").innerHTML = `Quantity left: ${selecteditem.quantity}`;
    });

  function addItemToOrder() {
    try {
      const quantity = Number(document.getElementById("quantity").value);
      if (!registerorderform.reportValidity()) return;
      if (quantity > Number(selecteditem.quantity)) throw new Error(`You only have ${selecteditem.quantity} left of this item`);
      const inlist = orderitems.find(item => item.itemid === selecteditem.item_id);
      if (inlist) throw new Error("Item is already in list");
      orderitems.push({
        item: selecteditem.name,
        itemid: selecteditem.item_id,
        price: Number(document.getElementById("amount").value),
        quantity: Number(quantity),
      });
      determineAdditionalWayBillFee();
      document.getElementById("qtyleft").innerHTML = "";
      orderform.reset();
    } catch ({ message }) {
      toastr.info(message);
    }
  }

  function removeItem(id) {
    console.log(id);
    const newlist = orderitems.filter(item => item.itemid !== id);
    orderitems = newlist;
    waybillfee = bckwaybill;
    removing = true;
    determineAdditionalWayBillFee();
  }

  if (orderform)
    orderform.addEventListener("submit", e => {
      e.preventDefault();
      addItemToOrder();
    });

  const submitOrder = async () => {
    try {
      showLoader();
      determineAdditionalWayBillFee();
      const submitorder = document.getElementById("submitorder");

      submitorder.innerHTML = "SUBMITTING...";
      submitorder.disabled = true;

      const data = new FormData(registerorderform);
      data.append("items", JSON.stringify(orderitems));
      data.append("conf_ec", extracharge);
      data.append("conf_wbf", waybillfee);

      const result = await postRequest(`order/submit`, data);

      if (result.status) {
        toastr.success(result.message);
        registerorderform.reset();
        extracharge = 0;
        waybillfee = 0;
        orderitems = [];
        calculateOrderDetails();
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(error);
    } finally {
      hideLoader();
      submitorder.innerHTML = `Submit Order <i class="fa fa-paper-plane" aria-hidden="true"></i>`;
      submitorder.disabled = false;
    }
  };

  const cancelOrder = async id => {
    try {
      showLoader();
      const result = await getRequest(`order/cancelorder?orderid=${id}`);

      if (result.status) {
        toastr.success(result.message);
        window.location = "/clients/orders";
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(error);
    } finally {
      hideLoader();
    }
  };

  const verifyPayment = async id => {
    try {
      showLoader();
      const result = await getRequest(`order/verifypayment?orderid=${id}`);

      if (result.status) {
        toastr.success(result.message);
        window.location = "/clients/orders";
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.error(error);
    } finally {
      hideLoader();
    }
  };
});
