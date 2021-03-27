document.addEventListener("DOMContentLoaded", () => {
  async function getStats() {
    try {
      showLoader();

      const result = await getRequest("dashboard/agentstats");

      if (result.status) {
        drawChart({
          labels: result.data.labels,
          datasets: [
            {
              label: "completed",
              borderColor: "#6bd098",
              backgroundColor: "#6bd098",
              pointRadius: 0,
              pointHoverRadius: 0,
              borderWidth: 3,
              data: result.data.data,
            },
          ],
        });
      }
    } catch ({ message: error }) {
      console.log(error);
    } finally {
      hideLoader();
    }
  }
  getStats();

  function drawChart(data) {
    const ctx = document.getElementById("monthlystats").getContext("2d");

    const myChart = new Chart(ctx, {
      type: "bar",

      data,
      options: {
        legend: {
          display: true,
        },

        tooltips: {
          enabled: true,
        },
        scales: {
          yAxes: [
            {
              ticks: {
                fontColor: "#9f9f9f",
                beginAtZero: false,
                maxTicksLimit: 5,
                //padding: 20
              },
              gridLines: {
                drawBorder: false,
                zeroLineColor: "#ccc",
                color: "rgba(255,255,255,0.05)",
              },
            },
          ],

          xAxes: [
            {
              barPercentage: 1.6,
              gridLines: {
                drawBorder: false,
                color: "rgba(255,255,255,0.1)",
                zeroLineColor: "transparent",
                display: false,
              },
              ticks: {
                padding: 20,
                fontColor: "#9f9f9f",
              },
            },
          ],
        },
      },
    });
  }
});
