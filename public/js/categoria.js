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
    Livewire.on('cerrar_modal_categoria', () => {
        $('#modal_categoria').removeClass('show').hide();
        $('body').removeClass('modal-open').css('padding-right', '');
        $('.modal-backdrop').remove();
    });

    Livewire.on('cerrar_modal_categoria_editar', () => {
        cerrar_modal();
    });
});

document.addEventListener('livewire:init', () => {
    Livewire.on('abrir_modal_categoria_editar', () => {
        const modal = document.getElementById('modal_categoria_editar');

        modal.style.display = 'block';
        modal.classList.add('show');
        modal.removeAttribute('aria-hidden');
        modal.setAttribute('aria-modal', 'true');

        document.body.classList.add('modal-open');

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    });
});

function cerrar_modal()
{
    const modal = document.getElementById('modal_categoria_editar');
    if (document.activeElement) {
        document.activeElement.blur();
    }
    modal.classList.remove('show');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modal.removeAttribute('aria-modal');

    document.body.classList.remove('modal-open');
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
}

