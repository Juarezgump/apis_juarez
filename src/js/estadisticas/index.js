import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data } from "jquery";
import { Chart } from "chart.js/auto";


const Grafico = document.getElementById('grafico1').getContext('2d');
const Grafico2 = document.getElementById('grafico2').getContext('2d');

window.graficaProductos = new Chart(Grafico, {
   type: 'bar',
   data: {
       labels: [],

       datasets: []
   },
   options: {
       responsive: true,
       scales: {
           y: { beginAtZero: true }
       }
   }
});



window.graficaProductos2 = new Chart(Grafico2, {
   type: 'pie',
   data: {
       labels: [],

       datasets: []
   },
   options: {
       responsive: true,
       scales: {
           y: { beginAtZero: true }
       }
   }
});





function getColorForEstado(cantidad) {
let color = ""
console.log(cantidad)
    if(cantidad > 50){
        color = "green"

    }
    if(cantidad > 2 && cantidad < 40){
        color = 'yellow'
    }
    if( cantidad <= 15){
color = 'red'
    }
   
    return color;
}






const BuscarAPI = async () => {
    const url = '/apis_juarez/estadisticas/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos


      

        if (codigo == 1) {
            

            const productos = [];
            const datosProductos = new Map();

            data.forEach(d => {
                if (!datosProductos.has(d.producto)) {
                    datosProductos.set(d.producto, d.cantidad);
                    productos.push({ producto: d.producto, pro_id: d.pro_id, cantidad: d.cantidad });
                }
            });
            console.log(datosProductos)
            console.log(productos)
            // Extraemos lotes únicos
            const etiquetasProductos = [...new Set(data.map(d => d.producto))];

            // Construimos datasets con el color según sit_cod
            const datasets = productos.map(e => ({
                label: e.producto,
                data: etiquetasProductos.map(producto => {
                    const match = data.find(d => d.producto === producto && d.producto === e.producto);
                    return match ? match.cantidad : 0;
                }),
                 backgroundColor: getColorForEstado(e.cantidad)
            }));

            // Actualizar la gráfica
            if (window.graficaProductos) {
                window.graficaProductos.data.labels = etiquetasProductos;
                window.graficaProductos.data.datasets = datasets;
                window.graficaProductos.update();
            }
              if (window.graficaProductos2) {
               const labels = Array.from(datosProductos.keys());
               const cantidades = Array.from(datosProductos.values());
               const colores = ['thistle', 'paleturquoise', 'lemonchiffon', 'moccasin', 'purple']
            

            window.graficaProductos2.data = {
                labels: labels,
                datasets:[{
                    data:cantidades,
                    backgroundColor:colores.slice(0,cantidades.lenth)
                }]
            };
            window.graficaProductos2.update();
        }

    }else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }


    } catch (error) {
        console.log(error)
    }
}

BuscarAPI();