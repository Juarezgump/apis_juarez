import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormProductos = document.getElementsById('FormProductos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const InputProductoPrecio = document.getElementById('pro_precio');
const InputProductoCantidad = document.getElementById('pro_cantidad');


const ValidarPrecio = () =>{
    const precio = InputProductoPrecio.value;

    if (precio.length < 1) {
        InputProductoPrecio.classList.remove('is-valid', 'is-invalid');
    }else{ 
        if (precio <= 0) {
             Swal.fire({
                position: "center",
                icon: "error",
                title: "Precio inválido",
                text: "El precio debe ser mayor a cero",
                showConfirmButton: true,
            });

            InputProductoPrecio.classList.remove('is-valid');
            InputProductoPrecio.classList.add('is-invalid');
        }else {
            InputProductoPrecio.classList.remove('is-invalid');
            InputProductoPrecio.classList.add('is-valid');
        }
    }


    const ValidarCantidad = () => {
        const cantidad = InputProductoCantidad.value;

        if (cantidad.length < 0) {
            InputProductoCantidad.classList.remove('is-valid', 'is-invalid');
        }else{
            if (cantidad < 0) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Cantidad inválida",
                text: "La cantidad no puede ser negativa",
                showConfirmButton: true,
            });

            InputProductoCantidad.classList.remove('is-valid');
            InputProductoCantidad.classList.add('is-invalid');
        }else {
            InputProductoCantidad.classList.remove('is-invalid');
            InputProductoCantidad.classList.add('is-valid');
         }
        }

    }

    const GuardarProducto = async (event) => {
        event.preventDefault();
        BtnGuardar.disabled = true;

        if(!validarFormulario(FormProductos, ['pro_id'])){
            Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;

        }

        const body = new FormData(FormProductos);

        const url = '/apis_juarez/prodcutos/guardarAPI';
        const config = {
            method: 'POST',
            body
        }

        try {
            const respuesta = await fetch(url, config)
            const datos = await respuesta.json();

            const { codigo, mensaje } = datos
            if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarProductos();

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
            console.log(error)
        }
        BtnGuardar.disabled = false; 
    }


    const BuscarProductos = async () => {
        const url = '/apis_juarez/productos/BuscarAPI';
        const config = {
            method: 'GET'
        }
         try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        
        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
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
        console.log(error)
    }
    }


    const datatable = new DataTable('#TableProductos')

   

}