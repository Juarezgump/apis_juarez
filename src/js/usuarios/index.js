import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import {validarFormulario} from '../funciones';


const formUsuarios = document.getElementById('formUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const InputUsuarioTelefono = document.getElementById('usuario_telefono');
const usuario_nit = document.getElementById('usuario_nit');

const ValidarTelefono = () => {
    const CantidadDigitos = InputUsuarioTelefono.value;

    if(CantidadDigitos.length < 1){
        InputUsuarioTelefono.classList.remove('is-valid', 'is-invalid');
    } else {
        if(CantidadDigitos.length != 8){
            Swal.fire({
                title: "Error en la cantidad de Dígitos en el teléfono",
                text: "Error en la cantidad de dígitos en el teléfono",
                icon: "error"
            });
            InputUsuarioTelefono.classList.remove('is-valid');
            InputUsuarioTelefono.classList.add('is-invalid');
        } else {
            InputUsuarioTelefono.classList.remove('is-invalid');
            InputUsuarioTelefono.classList.add('is-valid');
        }
    }
}

function validarNit(nitValue){
    // Si no se proporcionó un valor, obtener el valor del campo
    const nit = nitValue || usuario_nit.value.trim();
    let nd, add = 0;

    if(nd = /^(\d+)\-?([\dkK])$/.exec(nit)){
        nd[2] = (nd[2].toLowerCase()==='k') ? 10 : parseInt(nd[2]);
        for (let i = 0; i < nd[1].length; i++) {
            add += ((((i-nd[1].length)*-1)+1) * parseInt(nd[1][i]));
        }
        return ((11 - (add % 11)) % 11) === nd[2];
    } else {
        return false;
    }
}

const EsValidoNit = () => {
    const nitValue = usuario_nit.value.trim();
    
    // Si el campo está vacío, no mostrar validación
    if (nitValue.length === 0) {
        usuario_nit.classList.remove('is-valid', 'is-invalid');
        return;
    }
    
    if(validarNit(nitValue)){
        usuario_nit.classList.add('is-valid');
        usuario_nit.classList.remove('is-invalid');
    } else {
        usuario_nit.classList.remove('is-valid');
        usuario_nit.classList.add('is-invalid');

        Swal.fire({
            title: "Error en NIT",
            text: "El Número de NIT es inválido",
            icon: "error"
        });
    }
}



const GuardarUsuario = async (event) =>{

    event.preventDefault();
    BtnGuardar.disabled = true;

    if(!validarFormulario(formUsuarios, ['usuario_id'])){
         Swal.fire({
            title: "formulario incompleto",
            text: "debe validar todos los campos",
            icon: "error",
            showConfirmButton: true,
        });
        BtnGuardar.Disabled = false;
    }
    const body = new FormData(formUsuarios)

    
    const url= '/apis_juarez/usuarios/guardarAPI'
    const config = {
        method : 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config)
        const datos = await respuesta.json()
        console.log(datos)

    } catch (error) {
        console.log(error)
    }



}
formUsuarios.addEventListener('submit', GuardarUsuario);
usuario_nit.addEventListener('change', EsValidoNit);
InputUsuarioTelefono.addEventListener('change', ValidarTelefono);