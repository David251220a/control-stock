<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">

        <div class="widget-header">
            <div class="row align-items-center p-3">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        Productos en stock
                    </h3>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">

            {{-- BÚSQUEDA POR CÓDIGO DE BARRAS --}}
            <div class="card mb-3">
                <div class="card-body">
                    <label for="codigoBarra">Código de barra</label>

                    <input id="codigoBarra"
                        type="text"
                        wire:model="codigo_barra"
                        wire:keydown.enter="buscarPorCodigoBarra"
                        class="form-control"
                        placeholder="Escanee o escriba el código de barra"
                        autocomplete="off"
                        autofocus>
                </div>
            </div>

            <div class="row">

                {{-- PRODUCTOS --}}
                <div class="col-md-8">
                    <div class="row">
                        @foreach ($variantes as $item)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">

                                    <img
                                        src="{{ $item->imagen
                                            ? Storage::url($item->imagen)
                                            : Storage::url('categorias/categoria.jpg') }}"
                                        class="card-img-top"
                                        style="height: 150px; object-fit: cover;"
                                        alt="{{ $item->producto->descripcion }}">

                                    <div class="card-body d-flex flex-column">
                                        <h6 class="text-truncate"
                                            title="{{ $item->producto->descripcion }}">
                                            {{ $item->producto->descripcion }}
                                        </h6>

                                        <p class="mb-1 text-truncate"
                                            title="{{ $item->descripcion }}">
                                            Variante: {{ $item->descripcion }}
                                        </p>

                                        <p class="mb-1">
                                            Código: {{ $item->codigo }}
                                        </p>

                                        <p class="mb-1">
                                            Stock: {{ $item->stock_actual }}
                                        </p>

                                        <p class="mb-2">
                                            Precio:
                                            {{ number_format($item->precio_venta, 0, ',', '.') }}
                                        </p>

                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm w-100 mt-auto"
                                            wire:click="agregarVariante({{ $item->id }})">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CARRITO --}}
                <div class="col-md-4">
                    <h4>Carrito</h4>

                    <div class="card">
                        <div class="card-body">

                            {{-- PRODUCTOS DEL CARRITO --}}
                            @forelse ($carrito as $item)
                                <div class="border-bottom mb-2 pb-2">

                                    <strong
                                        class="d-block text-truncate"
                                        title="{{ $item['descripcion'] }} - {{ $item['variante'] }}">
                                        {{ $item['descripcion'] }} - {{ $item['variante'] }}
                                    </strong>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-secondary" wire:click="disminuir({{ $item['variante_id'] }})">
                                                -
                                            </button>

                                            <span class="mx-2">
                                                {{ $item['cantidad'] }}
                                            </span>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-secondary"
                                                wire:click="aumentar({{ $item['variante_id'] }})">
                                                +
                                            </button>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-danger" wire:click="quitar({{ $item['variante_id'] }})">
                                            X
                                        </button>
                                    </div>

                                    <div class="text-right mt-1">
                                        {{ number_format(
                                            $item['precio'] * $item['cantidad'],
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">
                                    Sin productos agregados.
                                </p>
                            @endforelse

                            <hr>

                            <h5 class="text-right">
                                Total: {{ number_format($this->total, 0, ',', '.') }}
                            </h5>

                            {{-- CLIENTE ANTES DE COBRAR --}}
                            <div class="card bg-light mt-3 mb-3">
                                <div class="card-body p-3">

                                    <div class="form-group mb-2">
                                        <label for="documentoCliente">
                                            Documento / RUC del cliente
                                        </label>

                                        <div class="input-group">
                                            <input
                                                id="documentoCliente"
                                                type="text"
                                                wire:model="documento_cliente"
                                                wire:keydown.enter="buscarCliente"
                                                class="form-control"
                                                placeholder="Documento o RUC"
                                                autocomplete="off">

                                            <div class="input-group-append">
                                                <button
                                                    type="button"
                                                    class="btn btn-primary"
                                                    wire:click="buscarCliente">
                                                    Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($cliente_id)
                                        <div class="alert alert-success p-2 mb-0">
                                            <strong>
                                                {{ $cliente_nombre }}
                                            </strong>

                                            <br>

                                            Documento:
                                            {{ $cliente_documento }}
                                        </div>
                                    @else
                                        <div class="alert alert-warning p-2 mb-0">
                                            Ningún cliente seleccionado.
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <button type="button" class="btn btn-success w-100 mt-2" wire:click="cobrar" @disabled(count($carrito) === 0)>
                                Cobrar
                            </button>

                            <button type="button" class="btn btn-warning w-100 mt-2" wire:click="dejarTesoreria" @disabled(count($carrito) === 0)>
                                Dejar en el Cajero
                            </button>

                            <button type="button" class="btn btn-danger w-100 mt-2" wire:click="limpiar" @disabled(count($carrito) === 0)>
                                Limpiar
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('producto.crear_cliente')
    @include('producto.cobro')
</div>


