
// Datos de ejemplo (reemplaza con tus datos reales)
var data = {
  labels: ['Narrativa', 'Lirica', 'Teatro', 'Cientifico-Tecnico'],
  datasets: [
    {
      data: [25, 15, 10, 20], // Reemplaza con la cantidad real de unidades en cada categoría
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'] // Colores para cada categoría
    }
  ]
}

var ctx = document.getElementById('miGrafico').getContext('2d')
var myPieChart = new Chart(ctx, {
  type: 'doughnut', // Tipo de gráfico circular
  data: data
})
