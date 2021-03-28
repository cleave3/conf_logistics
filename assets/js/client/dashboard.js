document.addEventListener("DOMContentLoaded", () => {
  async function getStats() {
    try {
      const result = await getRequest("dashboard/clientstats");

      if (result.status) {
        drawChart("bar", "#monthlystats", {
          labels: result.data.labels,
          datasets: [
            {
              label: "completed",
              borderColor: "#6bd098",
              backgroundColor: "#6bd098",
              pointRadius: 2,
              pointHoverRadius: 3,
              // borderWidth: 3,
              data: result.data.completed,
            },
            {
              label: "uncompleted",
              borderColor: "#f17e5d",
              backgroundColor: "#f17e5d",
              pointRadius: 2,
              pointHoverRadius: 3,
              // borderWidth: 3,
              data: result.data.uncompleted,
            },
          ],
        });
        drawChart("line", "#statestats", {
          labels: result.statestats.labels,
          datasets: [
            {
              label: "states",
              borderColor: "#51cbce",
              backgroundColor: "#51cbce",
              pointRadius: 2,
              pointHoverRadius: 3,
              // borderWidth: 3,
              data: result.statestats.data,
              fill: false,
            },
          ],
        });
      }
    } catch ({ message: error }) {
      console.log(error);
    } finally {
    }
  }
  getStats();
});
