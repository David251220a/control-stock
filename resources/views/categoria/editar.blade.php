<div class="modal fade" wire:ignore.self id="modal_categoria_editar" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="editar_categoria" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editar_categoria">
                    Editar Categoria
                </h5>
            </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-row mb-2">
                                <div class="form-group col-md-12">
                                    <label class="text-left d-block">Categoria</label>
                                    <input type="text" wire:model.defer="editar_descripcion" class="form-control" required>
                                </div>
                                {{-- <div class="form-group col-md-12">
                                    <label class="text-left d-block">Categoria Padre</label>
                                    <select wire:model.defer="editar_categoria_padre_id" class="form-control">
                                        <option value="">Sin padre / Categoría principal</option>
                                        @foreach ($categoriasPadres as $item)
                                            <option value="{{$item->id}}">{{$item->nombre_combo}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group col-md-12">

                                    <label class="text-left d-block">
                                        Imagen
                                    </label>
                                    <input type="file" class="form-control" wire:model="editar_imagen" wire:key="{{ $editarInputFile }}" accept=".jpg,.jpeg,image/jpeg">
                                </div>
                                <div wire:loading wire:target="editar_imagen" class="mt-2">
                                    <span class="text-primary">Subiendo imagen...</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="cerrar_modal()">
                    Cancelar
                </button>
                <button type="button" wire:click="editar_categoria" wire:loading.attr="disabled" wire:target="editar_categoria" class="btn btn-success btn-sm">
                    <span wire:loading.remove wire:target="editar_categoria">
                        Editar
                    </span>

                    <span wire:loading wire:target="editar_categoria">
                        <i class="fas fa-spinner fa-spin"></i>
                        Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
