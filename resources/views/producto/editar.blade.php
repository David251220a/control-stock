<div class="modal fade" wire:ignore.self id="modal_producto" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="crear_producto" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="crear_producto">
                    Editar Producto
                </h5>
            </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-row mb-2">
                                <div wire:ignore class="form-group col-md-12">
                                    <label>Categoría</label>
                                    <select id="categoria_id" wire:model="categoria_id" class="form-control">
                                        <option value="">Seleccione</option>
                                        @foreach($categorias as $item)
                                            <option value="{{$item->id}}">{{$item->nombre_combo}}</option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Descripción</label>
                                    <input type="text" wire:model="descripcion" class="form-control" placeholder="Nombre del producto">
                                    @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Observación</label>
                                    <textarea wire:model="observacion" class="form-control" rows="2"></textarea>
                                    @error('observacion') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" wire:click="grabar_producto" wire:loading.attr="disabled" wire:target="grabar_producto" class="btn btn-success btn-sm">
                    <span wire:loading.remove wire:target="grabar_producto">
                        Crear
                    </span>

                    <span wire:loading wire:target="grabar_producto">
                        <i class="fas fa-spinner fa-spin"></i>
                        Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
