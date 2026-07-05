<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" data-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registrar cliente</h5>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" wire:model="nuevo_documento" class="form-control">
                </div>

                <div class="form-group">
                    <label>Nombre / Razón social</label>
                    <input type="text" wire:model="nuevo_nombre" class="form-control">
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" wire:model="nuevo_apellido" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tipo Persona</label>
                        <select wire:model="nuevo_tipo_persona_id" class="form-control">
                            @foreach ($tipo_persona as $item)
                                <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Sexo</label>
                        <select wire:model="nuevo_sexo_id" class="form-control">
                            @foreach ($sexo as $item)
                                <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" wire:model="nuevo_email" class="form-control">
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" wire:model="nuevo_celular" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-success" wire:click="guardarCliente" wire:loading.attr="disabled" wire:target="guardarCliente">
                    <span wire:loading.remove wire:target="guardarCliente">
                        Guardar cliente
                    </span>

                    <span wire:loading wire:target="guardarCliente">
                        <span class="spinner-border spinner-border-sm mr-1" role="status"></span>
                        Guardando...
                    </span>

                </button>
            </div>

        </div>
    </div>
</div>
