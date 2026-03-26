document.addEventListener('DOMContentLoaded', () => {

    const canvas = document.getElementById('chart');
    if (!canvas) return;

    const statsData = JSON.parse(canvas.dataset.stats);

    const labels = statsData.map(item => item.menu);
    const dataValues = statsData.map(item => item.total);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Commandes par menu',
                data: dataValues
            }]
        }
    });

});