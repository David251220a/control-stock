<div class="modal fade" id="modal_cobro" tabindex="-1" role="dialog" data-backdrop="static" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar cobro</h5>
            </div>

            <div class="modal-body">

                <div class="alert alert-info p-2">
                    <strong>Cliente:</strong> {{ $cliente_nombre }} <br>
                    <strong>Documento:</strong> {{ $cliente_documento }}
                </div>

                <div class="form-group">
                    <label>Forma de cobro</label>
                    <select wire:model.live="forma_cobro_id" class="form-control">
                        @foreach ($forma_cobros as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->descripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($banco_ver == 1)
                    <div class="form-group">
                        <label>Banco</label>
                        <select wire:model="banco_id" class="form-control">
                            <option value="0">SELECCIONE BANCO</option>
                            @foreach ($bancos as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>N° Comprobante</label>
                        <input type="text" wire:model="numero_comprobante" class="form-control">
                    </div>
                @endif

                <div class="form-group">
                    <label>Monto recibido</label>
                    <input type="text" wire:model="monto_recibido" class="form-control text-right" onkeyup="punto_decimal(this)">
                </div>

                <div class="row mt-3">
                    @foreach($this->montosRapidos as $monto)
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-success btn-block" wire:click="seleccionarMonto({{ $monto }})">
                                {{ number_format($monto,0,',','.') }}
                            </button>
                        </div>
                    @endforeach
                </div>

                <h5 class="text-right mt-4">
                    Total: {{ number_format($this->total, 0, ',', '.') }}
                </h5>

                <h5 class="text-right">
                    Vuelto: {{ number_format(max(0, (int)$monto_recibido - $this->total), 0, ',', '.') }}
                </h5>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:loading.attr="disabled" wire:target="confirmarCobro">
                    Cancelar
                </button>

                <button type="button" class="btn btn-success" wire:click="confirmarCobro" wire:loading.attr="disabled" wire:target="confirmarCobro">
                    <span wire:loading.remove wire:target="confirmarCobro">
                        Confirmar cobro
                    </span>
                    <span wire:loading wire:target="confirmarCobro">
                        <span class="spinner-border spinner-border-sm mr-1"></span>
                        Procesando...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>
