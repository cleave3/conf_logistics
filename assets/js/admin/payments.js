function confirmSubmissionVerify(id) {
  toastr.confirm("Have you verified this submission ? ", {
    yes: () => verifySubmission(id),
  });
}

async function verifySubmission(id) {
  try {
    showLoader();
    const data = new FormData();
    data.append("taskid", id);
    const result = await postRequest("task/verifysubmission", data);

    if (result.status) {
      window.location.reload();
    } else {
      toastr.error(result.message);
    }
  } catch ({ message: error }) {
    console.log(result.message);
  } finally {
    hideLoader();
  }
}
