<div class="modal fade" wire:ignore.self id="modal_variante" tabindex="-1" role="dialog"
     data-backdrop="static" data-keyboard="false" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $titulo_variante }}</h5>
            </div>

            <div class="modal-body">
                <div class="form-row">

                    <div wire:ignore class="form-group col-md-12">
                        <label>Marca</label>
                        <select id="marca_variante_id" class="form-control">
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                            @endforeach
                        </select>
                        @error('marca_variante_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-12">
                        <label>Descripción</label>
                        <input type="text" wire:model.defer="variante_descripcion" class="form-control" placeholder="Ej: K120, LATA 350 ML, MADERA, NEGRA TALLE M">
                        @error('variante_descripcion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-12">
                        <label>Imagen</label>
                        <input type="file" wire:model="imagen" class="form-control" accept="image/jpeg,image/jpg,image/webp">
                        <div wire:loading wire:target="imagen" class="mt-2">
                            <span class="text-primary">
                                Subiendo imagen...
                            </span>
                        </div>
                        @error('imagen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-12">
                        <label>Código de barras</label>
                        <input type="text" wire:model.defer="codigo_barra" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Stock inicial</label>
                        <input type="number" wire:model.defer="stock_actual" class="form-control" min="0"
                            @if ($tipo_variante == 2)
                                readonly
                            @endif
                        >
                    </div>

                    <div class="form-group col-md-6">
                        <label>Stock mínimo</label>
                        <input type="number" wire:model.defer="stock_minimo" class="form-control" min="0">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Precio compra</label>
                        <input type="text" step="0.01" wire:model.defer="precio_compra" class="form-control text-right" onkeyup="punto_decimal(this)">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Precio venta</label>
                        <input type="text" step="0.01" wire:model.defer="precio_venta" class="form-control text-right" onkeyup="punto_decimal(this)">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" wire:click="cerrar_modal('modal_variante')">
                    Cancelar
                </button>

                @if ($tipo_variante == 1)
                    <button type="button" wire:click="grabar_variante" wire:loading.attr="disabled" wire:target="grabar_variante" class="btn btn-success btn-sm">
                        <span wire:loading.remove wire:target="grabar_variante">Guardar</span>
                        <span wire:loading wire:target="grabar_variante">
                            <i class="fas fa-spinner fa-spin"></i> Guardando...
                        </span>
                    </button>
                @endif

                @if ($tipo_variante == 2)
                    <button type="button" wire:click="grabar_edicion_variante" wire:loading.attr="disabled" wire:target="grabar_edicion_variante" class="btn btn-success btn-sm">
                        <span wire:loading.remove wire:target="grabar_edicion_variante">Guardar</span>
                        <span wire:loading wire:target="grabar_edicion_variante">
                            <i class="fas fa-spinner fa-spin"></i> Guardando...
                        </span>
                    </button>
                @endif

            </div>

        </div>
    </div>
</div>
