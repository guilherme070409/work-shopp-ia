// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar navigation
    const navItems = document.querySelectorAll('.sidebar-nav li');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Logout functionality
    document.getElementById('logout').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Deseja realmente sair?')) {
            // Redirect to login page or clear session
            window.location.href = 'login.html';
        }
    });

    // Sample data for charts (you can integrate with real data later)
    const salesData = {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Vendas (R$)',
            data: [12000, 19000, 15000, 18000, 22000, 25000],
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            tension: 0.4
        }]
    };

    // Initialize charts (if using Chart.js)
    // const salesChart = new Chart(document.getElementById('salesChart'), {
    //     type: 'line',
    //     data: salesData,
    //     options: {
    //         responsive: true,
    //         plugins: {
    //             legend: {
    //                 display: false
    //             }
    //         }
    //     }
    // });

    // Real-time updates simulation
    setInterval(() => {
        updateStats();
    }, 30000); // Update every 30 seconds
});

function updateStats() {
    // Simulate real-time data updates
    const stats = document.querySelectorAll('.stat-card h3');
    stats.forEach(stat => {
        const currentValue = parseInt(stat.textContent.replace(/\D/g, ''));
        const randomChange = Math.floor(Math.random() * 10) - 2; // -2 to +7
        const newValue = Math.max(0, currentValue + randomChange);
        
        if (stat.textContent.includes('R$')) {
            stat.textContent = `R$ ${newValue.toLocaleString('pt-BR')},00`;
        } else {
            stat.textContent = newValue.toLocaleString('pt-BR');
        }
    });
}

// Export data functionality
function exportData(type) {
    alert(`Exportando dados de ${type}...`);
    // Implement CSV/Excel export functionality here
}

// Quick actions
function quickAction(action) {
    const actions = {
        'add-product': () => window.location.href = '#produtos',
        'view-reports': () => exportData('relatórios'),
        'manage-users': () => window.location.href = '#clientes'
    };
    
    if (actions[action]) {
        actions[action]();
    }
}