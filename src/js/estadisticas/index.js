import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { Chart } from "chart.js/auto";

// Contextos de los gráficos
const grafico = document.getElementById('grafico1').getContext('2d');
const grafico2 = document.getElementById('grafico2').getContext('2d');
const grafico3 = document.getElementById('grafico3').getContext('2d');
const grafico4 = document.getElementById('grafico4').getContext('2d');

// Paletas de colores más fuertes e intensos
const paletaColoresBarras = [
    '#C0392B', '#D68910', '#8E44AD', '#2E86C1', '#148F77',
    '#B7950B', '#A93226', '#6C3483', '#1A5490', '#0E6655',
    '#7D6608', '#943126', '#5B2C6F', '#154360', '#0B5345',
    '#76448A', '#D35400', '#A04000', '#7E5109', '#6E2C00',
    '#922B21', '#6A1B9A', '#1565C0', '#00695C', '#E65100'
];

const paletaColoresPastel = [
    '#E74C3C', '#F39C12', '#9B59B6', '#3498DB', '#1ABC9C',
    '#F1C40F', '#E67E22', '#E91E63', '#2196F3', '#4CAF50',
    '#FF9800', '#795548', '#607D8B', '#FF5722', '#8BC34A',
    '#FFEB3B', '#00BCD4', '#673AB7', '#FF4081', '#536DFE',
    '#69F0AE', '#FFD740', '#FF6E40', '#B39DDB', '#81C784'
];

const paletaColoresVibrantes = [
    '#B71C1C', '#BF360C', '#E65100', '#F57F17', '#33691E',
    '#1B5E20', '#006064', '#0D47A1', '#1A237E', '#4A148C',
    '#880E4F', '#AD1457', '#C2185B', '#D81B60', '#E91E63',
    '#F44336', '#E53935', '#D32F2F', '#C62828', '#B71C1C',
    '#3F51B5', '#303F9F', '#283593', '#1976D2', '#1565C0'
];

const paletaColoresGradiente = [
    '#8E24AA', '#7B1FA2', '#6A1B9A', '#4A148C', '#3F51B5',
    '#303F9F', '#283593', '#1976D2', '#1565C0', '#0277BD',
    '#00695C', '#00796B', '#388E3C', '#689F38', '#827717',
    '#F57F17', '#FF8F00', '#FF6F00', '#E65100', '#BF360C',
    '#D84315', '#FF3D00', '#DD2C00', '#C62828', '#B71C1C'
];

// Gráfico 1: Productos más vendidos (barras)
window.graficaProductos = new Chart(grafico, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Productos Más Vendidos'
            }
        }
    }
});

// Gráfico 2: Productos más vendidos (pie)
window.graficaProductos2 = new Chart(grafico2, {
    type: 'pie',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right'
            },
            title: {
                display: true,
                text: 'Distribución de Productos Vendidos'
            }
        }
    }
});

// Gráfico 3: Clientes con más compras (doughnut)
window.graficaClientes = new Chart(grafico3, {
    type: 'doughnut',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Clientes con Más Compras'
            }
        }
    }
});

// Gráfico 4: Ventas por mes (línea)
const datosLineaVentas = {
    labels: [],
    datasets: [{
        label: 'Ventas Mensuales',
        data: [],
        fill: true,
        backgroundColor: 'rgba(183, 28, 28, 0.3)', // Color más fuerte
        borderColor: '#B71C1C', // Rojo intenso
        pointBackgroundColor: '#D32F2F',
        pointBorderColor: '#fff',
        pointBorderWidth: 3,
        pointRadius: 8,
        tension: 0.4
    }]
};

const configuracionLinea = {
    type: 'line',
    data: datosLineaVentas,
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            title: {
                display: true,
                text: 'Ventas por Mes - Vista Detallada'
            }
        }
    }
};

window.graficaVentasMesLinea = new Chart(grafico4, configuracionLinea);

// Función mejorada para obtener colores más fuertes según cantidad
function obtenerColorPorCantidad(cantidad) {
    if (cantidad > 50) {
        return paletaColoresVibrantes[Math.floor(Math.random() * 5)]; // Colores muy intensos para alta cantidad
    }
    if (cantidad > 16 && cantidad < 40) {
        return paletaColoresBarras[Math.floor(Math.random() * 10)]; // Colores fuertes medios
    }
    if (cantidad <= 15) {
        return paletaColoresPastel[Math.floor(Math.random() * 10)]; // Colores fuertes pero suaves
    }
    return paletaColoresGradiente[Math.floor(Math.random() * paletaColoresGradiente.length)];
}

// Función para generar colores aleatorios de una paleta
function generarColoresAleatorios(cantidad, paleta = paletaColoresBarras) {
    const coloresSeleccionados = [];
    for (let i = 0; i < cantidad; i++) {
        const indiceAleatorio = Math.floor(Math.random() * paleta.length);
        coloresSeleccionados.push(paleta[indiceAleatorio]);
    }
    return coloresSeleccionados;
}

const buscarAPI = async () => {
    const url = '/apis_juarez/estadisticas/buscarAPI';
    const configuracion = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, configuracion);
        const datos = await respuesta.json();
        const { codigo, mensaje, productos, clientes, ventasMes } = datos;

        if (codigo == 1) {
            
            // GRÁFICO 1: Productos más vendidos (barras)
            const productos2 = [];
            const datosProductos = new Map();

            productos.forEach(d => {
                if (!datosProductos.has(d.producto)) {
                    datosProductos.set(d.producto, d.cantidad);
                    productos2.push({ 
                        producto: d.producto, 
                        pro_id: d.pro_id, 
                        cantidad: d.cantidad 
                    });
                }
            });

            const etiquetasProductos = [...new Set(productos.map(d => d.producto))];

            // Construimos datasets con colores más fuertes
            const conjuntosDatos = productos2.map(elemento => ({
                label: elemento.producto,
                data: etiquetasProductos.map(producto => {
                    const coincidencia = productos.find(d => 
                        d.producto === producto && d.producto === elemento.producto
                    );
                    return coincidencia ? coincidencia.cantidad : 0;
                }),
                backgroundColor: obtenerColorPorCantidad(elemento.cantidad),
                borderColor: obtenerColorPorCantidad(elemento.cantidad),
                borderWidth: 3, // Bordes más gruesos
                borderRadius: 6, // Esquinas más redondeadas
                borderSkipped: false,
            }));

            // Actualizar la gráfica de barras
            if (window.graficaProductos) {
                window.graficaProductos.data.labels = etiquetasProductos;
                window.graficaProductos.data.datasets = conjuntosDatos;
                window.graficaProductos.update();
            }

            // GRÁFICO 2: Productos más vendidos (pie) con colores más fuertes
            if (window.graficaProductos2) {
                const etiquetas = Array.from(datosProductos.keys());
                const cantidades = Array.from(datosProductos.values());
                
                // Generar colores más vibrantes para cada segmento
                const coloresPie = generarColoresAleatorios(cantidades.length, paletaColoresVibrantes);

                window.graficaProductos2.data = {
                    labels: etiquetas,
                    datasets: [{
                        data: cantidades,
                        backgroundColor: coloresPie,
                        borderColor: '#ffffff',
                        borderWidth: 4, // Bordes más gruesos
                        hoverOffset: 8 // Mayor separación en hover
                    }]
                };
                window.graficaProductos2.update();
            }

            // GRÁFICO 3: Clientes con más compras
            actualizarGraficoClientes(clientes);
            
            // GRÁFICO 4: Ventas por mes (línea)
            actualizarGraficoVentasMesLinea(ventasMes);

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
};

function actualizarGraficoClientes(clientes) {
    // Tomar solo los primeros 10 clientes para mejor visualización
    const clientesTop = clientes.slice(0, 10);
    const etiquetasClientes = clientesTop.map(c => c.cliente);
    const cantidadesClientes = clientesTop.map(c => c.cantidad_total_prod);
    
    // Usar paleta de colores más intensos para el gráfico de dona
    const coloresClientes = generarColoresAleatorios(cantidadesClientes.length, paletaColoresGradiente);

    if (window.graficaClientes) {
        window.graficaClientes.data = {
            labels: etiquetasClientes,
            datasets: [{
                data: cantidadesClientes,
                backgroundColor: coloresClientes,
                borderColor: '#ffffff',
                borderWidth: 4, // Bordes más gruesos
                hoverOffset: 10 // Mayor separación en hover
            }]
        };
        window.graficaClientes.update();
    }
}

function actualizarGraficoVentasMesLinea(ventasMes) {
    const etiquetasMeses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    const datosVentas = ventasMes[0];
    const ventasPorMes = [
        datosVentas.enero, datosVentas.febrero, datosVentas.marzo, 
        datosVentas.abril, datosVentas.mayo, datosVentas.junio,
        datosVentas.julio, datosVentas.agosto, datosVentas.septiembre,
        datosVentas.octubre, datosVentas.noviembre, datosVentas.diciembre
    ];

    // Colores más fuertes para la línea de ventas
    const coloresLineaFuertes = [
        '#B71C1C', '#D32F2F', '#1976D2', '#388E3C', '#F57F17',
        '#FF6F00', '#E65100', '#5D4037', '#455A64', '#BF360C',
        '#4A148C', '#00695C'
    ];

    const datosActualizados = {
        labels: etiquetasMeses,
        datasets: [{
            label: 'Ventas Mensuales',
            data: ventasPorMes,
            fill: true,
            backgroundColor: 'rgba(183, 28, 28, 0.2)', // Fondo más intenso
            borderColor: '#B71C1C', // Línea roja muy fuerte
            pointBackgroundColor: coloresLineaFuertes,
            pointBorderColor: '#ffffff',
            pointBorderWidth: 4, // Bordes más gruesos en puntos
            pointRadius: 10, // Puntos más grandes
            pointHoverRadius: 14, // Hover más grande
            tension: 0.4,
            borderWidth: 4 // Línea más gruesa
        }]
    };

    if (window.graficaVentasMesLinea) {
        window.graficaVentasMesLinea.data = datosActualizados;
        window.graficaVentasMesLinea.update();
    }
}

// Ejecutar la función principal
buscarAPI();