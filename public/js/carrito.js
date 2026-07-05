const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
});

document.addEventListener('livewire:init', function () {

    Livewire.on('mensaje_error', (event) => {
        swalWithBootstrapButtons(
            'Atención',
            event[0],
            'error'
        );
    });

    Livewire.on('mensaje_exitoso', (event) => {
        swal({
            title: 'Buen Trabajo',
            text: event[0],
            type: 'success',
            padding: '2em'
        });
    });

});


window.addEventListener('abrir_modal_cliente', event => {
    $('#modal_cliente').modal('show');
});

window.addEventListener('cerrar_modal_cliente', event => {
    $('#modal_cliente').modal('hide');
});

window.addEventListener('abrir_modal_cobro', event => {
    $('#modal_cobro').modal('show');
});


window.addEventListener('cerrar_modal_cobro', event => {
    $('#modal_cobro').modal('hide');
});
