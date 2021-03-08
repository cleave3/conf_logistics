document.getElementById("changepasswordform").addEventListener("submit", async e => {
  e.preventDefault();
  try {
    const newpassword = document.getElementById("newpassword").value;
    const cpassword = document.getElementById("cpassword").value;

    if (newpassword != cpassword) throw new Error("password does not match");
    if (newpassword.length < 6) throw new Error("password must be atleast 6 characters long");

    const changepasswordbtn = document.getElementById("changepasswordbtn");

    changepasswordbtn.innerHTML = "Submitting...";
    changepasswordbtn.disabled = true;

    const data = new FormData(e.target);

    const result = await postRequest("auth/changepassword", data);

    if (result.status) {
      toastr.success(result.message);
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    toastr.error(error);
  } finally {
    changepasswordbtn.innerHTML = "SUBMIT";
    changepasswordbtn.disabled = false;
  }
});
