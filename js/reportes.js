const ctxProductos = document.getElementById('graficoProductos').getContext('2d');
new Chart(ctxProductos, {
  type: 'bar',
  data: {
    labels: ['Producto A', 'Producto B', 'Producto C'],
    datasets: [{
      label: 'Cantidad',
      data: [45, 70, 30],
      backgroundColor: ['#3498db', '#2ecc71', '#e67e22']
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

const ctxMovimientos = document.getElementById('graficoMovimientos').getContext('2d');
new Chart(ctxMovimientos, {
  type: 'pie',
  data: {
    labels: ['Entradas', 'Salidas'],
    datasets: [{
      data: [60, 40],
      backgroundColor: ['#f1c40f', '#e74c3c']
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

const ctxEntradas = document.getElementById('graficoEntradas').getContext('2d');
new Chart(ctxEntradas, {
  type: 'line',
  data: {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
    datasets: [{
      label: 'Entradas',
      data: [15, 25, 35, 28, 40],
      borderColor: '#2ecc71',
      backgroundColor: 'rgba(46, 204, 113, 0.2)',
      fill: true
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

const ctxSalidas = document.getElementById('graficoSalidas').getContext('2d');
new Chart(ctxSalidas, {
  type: 'line',
  data: {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
    datasets: [{
      label: 'Salidas',
      data: [10, 15, 20, 18, 25],
      borderColor: '#e74c3c',
      backgroundColor: 'rgba(231, 76, 60, 0.2)',
      fill: true
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});
