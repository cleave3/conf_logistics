document.addEventListener("DOMContentLoaded", async () => {
  const state = document.getElementById("state");
  const city = document.getElementById("city");
  const clientphoto = document.getElementById("clientphoto");
  const image = document.getElementById("image");
  const changebtn = document.getElementById("changebtn");
  const uploadbtn = document.getElementById("uploadbtn");

  state.addEventListener("change", async e => {
    city.innerHTML = await loadcities(e.target.value);
  });

  changebtn.addEventListener("click", e => {
    e.preventDefault();
    image.click();
  });

  image.addEventListener("change", async e => {
    if (e.target.files.length < 1) {
      clientphoto.src = "/files/photo/default.jpg";
      changebtn.classList.remove("d-none");
      uploadbtn.classList.add("d-none");
      return;
    }

    const file = e.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", e => (clientphoto.src = reader.result));
    reader.readAsDataURL(file);
    uploadbtn.classList.remove("d-none");
  });

  document.getElementById("photoform").addEventListener("submit", async e => {
    e.preventDefault();
    try {
      uploadbtn.innerHTML = "UPLOADING...";
      uploadbtn.disabled = true;

      const data = new FormData(e.target);
      const result = await postRequest(`client/updatephoto`, data);

      if (result.status) {
        toastr.success(result.message);
        uploadbtn.classList.add("d-none");
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.success(result.message);
    } finally {
      uploadbtn.innerHTML = `UPLOAD PHOTO <i class="fa fa-upload" aria-hidden="true"></i>`;
      uploadbtn.disabled = false;
    }
  });

  document.getElementById("editprofileform").addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const updateprofilebtn = document.getElementById("updateprofilebtn");

      updateprofilebtn.innerHTML = "UPDATING...";
      updateprofilebtn.disabled = true;

      const data = new FormData(e.target);

      const result = await postRequest(`client/updateprofile`, data);

      if (result.status) {
        toastr.success(result.message);
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        toastr.error(result.message);
      }
    } catch ({ message: error }) {
      toastr.success(result.message);
    } finally {
      updateprofilebtn.innerHTML = "UPDATE PROFILE";
      updateprofilebtn.disabled = false;
    }
  });
});
