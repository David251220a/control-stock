const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
});

document.addEventListener('livewire:init', function () {

    Livewire.on('mensaje_error', (event) => {
        console.log(event);
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

document.addEventListener('livewire:init', () => {
    Livewire.on('cerrar_modal_producto', () => {
        $('#modal_producto').removeClass('show').hide();
        $('body').removeClass('modal-open').css('padding-right', '');
        $('.modal-backdrop').remove();
    });
});

document.addEventListener('livewire:init', () => {

    Livewire.on('abrir_modal', ({ id }) => {

        const modal = document.getElementById(id);

        modal.style.display = 'block';
        modal.classList.add('show');
        modal.removeAttribute('aria-hidden');
        modal.setAttribute('aria-modal', 'true');

        document.body.classList.add('modal-open');

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.setAttribute('data-modal-backdrop', id);
        document.body.appendChild(backdrop);

    });

    Livewire.on('cerrar_modal', ({ id }) => {

        const modal = document.getElementById(id);

        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('modal-open');

        const backdrop = document.querySelector(
            `.modal-backdrop[data-modal-backdrop="${id}"]`
        );

        if (backdrop) {
            backdrop.remove();
        }

    });

});

