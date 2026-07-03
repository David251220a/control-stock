<div class="modal fade" id="modal_categoria" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="crear_categoria" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="crear_categoria">
                    Crear Categoria
                </h5>
            </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-row mb-2">
                                <div class="form-group col-md-12">
                                    <label class="text-left d-block">Categoria</label>
                                    <input type="text" wire:model.defer="descripcion" class="form-control" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="text-left d-block">Categoria Padre</label>
                                    <select wire:model.defer="categoria_padre_id" class="form-control">
                                        <option value="">Sin padre / Categoría principal</option>
                                        @foreach ($categoriasPadres as $item)
                                            <option value="{{$item->id}}">{{$item->nombre_combo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="text-left d-block">Imagen</label>
                                    <input type="file" class="form-control" wire:model="imagen"  accept=".jpg,.jpeg,image/jpeg">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" wire:click="grabar_categoria" class="btn btn-success btn-sm">
                    Crear
                </button>
            </div>
        </div>
    </div>
</div>
