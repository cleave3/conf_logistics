const entity = document.getElementById("entity");
const initiatebtn = document.getElementById("initiatebtn");
const transferform = document.getElementById("transferform");
const otpform = document.getElementById("otpform");
const otpbtn = document.getElementById("otpbtn");

if (entity)
  entity.addEventListener("change", async e => {
    try {
      showLoader();

      const result = await getRequest(`transaction/recipientdetail?id=${e.target.value}`);
      if (result.status) {
        document.getElementById("accountnumber").value = result.data.accountnumber;
        document.getElementById("bank").value = result.data.bankname;
        document.getElementById("accountname").value = result.data.accountname;
        document.getElementById("balance").value = number_format(result.data.balance);
      } else {
        document.getElementById("accountnumber").value = "";
        document.getElementById("accountnumber").value = "";
        document.getElementById("bank").value = "";
        document.getElementById("balance").value = "";
        document.getElementById("accountname").value = "";
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      console.error(error);
    } finally {
      hideLoader();
    }
  });

const initiateTransfer = async () => {
  try {
    showLoader();
    initiatebtn.innerHTML = "Submitting ...";
    initiatebtn.disabled = true;

    const data = new FormData(transferform);

    const result = await postRequest(`transaction/inittransfer`, data);

    if (result.status) {
      toastr.success(result.message);

      if (result.data.status === "success") {
        transferform.reset();
      }

      if (result.data.status === "otp") {
        $("#otpmodal").modal("show");
        const button = `<button type="button" id="finalisebtn" class="btn btn-warning mx-0 text-dark" data-toggle="modal" data-target="#otpmodal">Finialise Transfer</button>`;
        document.getElementById("finalisebtn-container").insertAdjacentHTML("afterbegin", button);
        document.getElementById("transfercode").value = result.data.transfer_code;
      }
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.error(error);
  } finally {
    initiatebtn.innerHTML = `Initiate Transfer <i class="fas fa-paper-plane "></i>`;
    initiatebtn.disabled = false;
    transferform.classList.remove("was-validated");
    hideLoader();
  }
};

const confirmSubmit = () => {
  toastr.confirm("Are you sure you want to initiate this transfer ?", {
    yes: () => initiateTransfer(),
  });
};

if (initiatebtn)
  initiatebtn.addEventListener("click", e => {
    e.preventDefault();
    transferform.classList.add("was-validated");
    if (!transferform.checkValidity()) return;
    if (
      document.getElementById("accountname").value.trim() == "" ||
      document.getElementById("accountname").value.trim() == "invalid account details" ||
      document.getElementById("accountnumber").value.trim() == "" ||
      document.getElementById("accountnumber").value.trim() == "" ||
      document.getElementById("bank").value.trim() == "" ||
      document.getElementById("balance").value.trim() == ""
    ) {
      toastr.warning("recipient is not valid");
      return;
    }
    confirmSubmit();
  });

if (otpform)
  otpform.addEventListener("submit", async e => {
    try {
      e.preventDefault();
      otpform.classList.add("was-validated");
      if (!otpform.checkValidity()) return;
      showLoader();
      otpbtn.innerHTML = "Submitting ...";
      otpbtn.disabled = true;

      const data = new FormData(transferform);
      data.append("otp", document.getElementById("otp").value);
      data.append("transfercode", document.getElementById("transfercode").value);

      const result = await postRequest(`transaction/completetransfer`, data);

      if (result.status) {
        toastr.success(result.message);
        if (result.data.status === "success") {
          transferform.reset();
          $("#otpmodal").modal("hide");
          document.getElementById("finalisebtn-container").removeChild(document.getElementById("finalisebtn"));
        }
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      console.error(error);
    } finally {
      otpbtn.innerHTML = `Initiate Transfer <i class="fas fa-paper-plane "></i>`;
      otpbtn.disabled = false;
      transferform.classList.remove("was-validated");
      otpform.classList.remove("was-validated");
      hideLoader();
    }
  });
