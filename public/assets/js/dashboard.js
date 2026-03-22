(function ($) {
  'use strict';

  // ===================== CHART 1 =====================
  if ($("#visit-sale-chart").length) {
    const ctx = document.getElementById('visit-sale-chart');

    if (ctx) {
      var graphGradient1 = ctx.getContext("2d");
      var graphGradient2 = ctx.getContext("2d");
      var graphGradient3 = ctx.getContext("2d");

      var gradientStrokeViolet = graphGradient1.createLinearGradient(0, 0, 0, 181);
      gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
      gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');

      var gradientStrokeBlue = graphGradient2.createLinearGradient(0, 0, 0, 360);
      gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
      gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');

      var gradientStrokeRed = graphGradient3.createLinearGradient(0, 0, 0, 300);
      gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
      gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG'],
          datasets: [
            {
              label: "CHN",
              backgroundColor: gradientStrokeViolet,
              borderColor: gradientStrokeViolet,
              data: [20, 40, 15, 35, 25, 50, 30, 20],
            },
            {
              label: "USA",
              backgroundColor: gradientStrokeRed,
              borderColor: gradientStrokeRed,
              data: [40, 30, 20, 10, 50, 15, 35, 40],
            },
            {
              label: "UK",
              backgroundColor: gradientStrokeBlue,
              borderColor: gradientStrokeBlue,
              data: [70, 10, 30, 40, 25, 50, 15, 30],
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: { display: false }
          }
        }
      });
    }
  }

  // ===================== CHART 2 =====================
  if ($("#traffic-chart").length) {
    const ctx = document.getElementById('traffic-chart');

    if (ctx) {
      var graphGradient1 = ctx.getContext('2d');
      var graphGradient2 = ctx.getContext('2d');
      var graphGradient3 = ctx.getContext('2d');

      var gradientStrokeBlue = graphGradient1.createLinearGradient(0, 0, 0, 181);
      gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
      gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');

      var gradientStrokeRed = graphGradient2.createLinearGradient(0, 0, 0, 50);
      gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
      gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');

      var gradientStrokeGreen = graphGradient3.createLinearGradient(0, 0, 0, 300);
      gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
      gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');

      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Search Engines 30%', 'Direct Click 30%', 'Bookmarks Click 40%'],
          datasets: [{
            data: [30, 30, 40],
            backgroundColor: [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed],
          }]
        },
        options: {
          cutout: 50,
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: { display: false }
          }
        }
      });
    }
  }

  // ===================== DATEPICKER =====================
  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }
  // ===================== CLOSE BUTTON =====================
  const bannerClose = document.querySelector('#bannerClose');

  if (bannerClose) {
    bannerClose.addEventListener('click', function () {
      proBanner?.classList.add('d-none');
      proBanner?.classList.remove('d-flex');
      navbar?.classList.remove('pt-5');
      navbar?.classList.add('fixed-top');
      pageWrapper?.classList.add('proBanner-padding-top');
      navbar?.classList.remove('mt-3');

      var date = new Date();
      date.setTime(date.getTime() + 24 * 60 * 60 * 1000);

      $.cookie('purple-pro-banner', "true", {
        expires: date
      });
    });
  }

})(jQuery);