const type = document.getElementById("type");
const entity = document.getElementById("entity");
const verifybtn = document.getElementById("verifybtn");
const beneficiaryform = document.getElementById("addbeneficiaryform");
const savebtn = document.getElementById("savebtn");

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

if (entity)
  entity.addEventListener("change", async e => {
    try {
      showLoader();

      const result = await getRequest(`transaction/account?id=${e.target.value}`);
      if (result.status) {
        document.getElementById("accountnumber").value = result.data.accountnumber;
        $(`#bankcode option[value='${result.data.bankcode}']`).prop("selected", true);
      } else {
        document.getElementById("accountnumber").value = "";
        $(`#bankcode option[value='']`).prop("selected", true);
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      console.error(error);
    } finally {
      hideLoader();
    }
  });

async function verifyAccountNumber() {
  try {
    const beneficiaryform = document.getElementById("addbeneficiaryform");
    if (!beneficiaryform.checkValidity()) return;
    showLoader();
    verifybtn.innerHTML = "verifying...";
    verifybtn.disabled = true;

    const data = new FormData(beneficiaryform);

    const result = await postRequest(`transaction/verifybankdetails`, data);
    console.log(result);

    if (result.status) {
      document.getElementById("accountname").value = result.data.account_name;
    } else {
      document.getElementById("accountname").value = "invalid account details";
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.error(error);
  } finally {
    verifybtn.innerHTML = "Verify";
    verifybtn.disabled = false;
    hideLoader();
  }
}

if (verifybtn)
  verifybtn.addEventListener("click", e => {
    e.preventDefault();
    verifyAccountNumber();
  });

const saveBeneficiary = async () => {
  try {
    showLoader();
    savebtn.innerHTML = "Saving Beneficiary...";
    savebtn.disabled = true;

    const data = new FormData(beneficiaryform);

    const result = await postRequest(`transaction/savebeneficiary`, data);

    if (result.status) {
      toastr.success(result.message);
      beneficiaryform.reset();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.error(error);
  } finally {
    savebtn.innerHTML = "Save Beneficiary";
    savebtn.disabled = false;
    hideLoader();
  }
};

const confirmSubmit = () => {
  toastr.confirm("Are you sure you want to save this beneficiary ?", {
    yes: () => saveBeneficiary(),
  });
};

if (savebtn)
  savebtn.addEventListener("click", e => {
    e.preventDefault();
    if (!beneficiaryform.checkValidity()) return;
    if (
      document.getElementById("accountname").value.trim() == "" ||
      document.getElementById("accountname").value.trim() == "invalid account details"
    ) {
      toastr.warning("please verify account first");
      return;
    }
    confirmSubmit();
  });
