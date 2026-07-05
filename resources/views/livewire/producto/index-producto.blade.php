<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">

        <div class="widget-header">
            <div class="row align-items-center p-3">

                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        Producto
                    </h3>
                    <small class="text-muted">
                        Administre los productos
                    </small>
                </div>

                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <button class="btn btn-primary" wire:click="abril_modal('modal_producto', 1)">
                        <i class="fas fa-plus"></i>
                        Nuevo Producto
                    </button>
                </div>

            </div>
        </div>

        <div class="widget-content widget-content-area">

            @if($tipo_lista == 2)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button class="btn btn-secondary btn-sm" wire:click="volverProductos">
                            <i class="fas fa-arrow-left"></i>
                            Volver a productos
                        </button>

                        <span class="ml-2 font-weight-bold">
                            Producto: {{ $producto_actual_nombre }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="row mb-4">

                <div class="col-md-12">

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>

                        <input type="text" class="form-control" placeholder="Buscar producto..." wire:model.defer="buscar">

                        <button type="button" class="btn btn-primary" wire:click="buscarProducto">
                            Buscar
                        </button>
                    </div>

                </div>

            </div>

            <div class="row mt-4">

                @forelse($data as $item)

                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

                        @if($tipo_lista == 1)

                            {{-- CARD PRODUCTO --}}
                            <div class="categoria-card" wire:click="abrirProducto({{ $item->id }})">

                                <button type="button" class="btn-editar-cat" wire:click.stop="editarProducto({{ $item->id }})" title="Editar producto">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <div class="categoria-badge">
                                    {{ $item->variantes_count ?? $item->variantes->count() }} Variantes
                                </div>

                                <div class="categoria-body">

                                    <h4>{{ $item->descripcion }}</h4>

                                    <div class="mb-2">
                                        <strong>Categoría:</strong>
                                        {{ $item->categoria->descripcion ?? 'SIN CATEGORÍA' }}
                                    </div>

                                    <div class="categoria-info d-flex justify-content-between mt-3">

                                        <div title="Variantes">
                                            <i class="fas fa-layer-group text-warning"></i>
                                            <strong>{{ $item->variantes_count ?? $item->variantes->count() }}</strong>
                                        </div>

                                        <div title="Stock total">
                                            <i class="fas fa-boxes text-info"></i>
                                            <strong>{{ number_format($item->variantes->sum('stock_actual'), 0, ',', '.') }}</strong>
                                        </div>

                                    </div>

                                    <div class="mt-3">
                                        <button type="button"
                                                class="btn btn-light btn-block btn-sm"
                                                wire:click.stop="agregarVariante({{ $item->id }})">
                                            <i class="fas fa-plus"></i>
                                            Variante
                                        </button>
                                    </div>

                                </div>
                            </div>

                        @else

                            {{-- CARD VARIANTE --}}
                            <div class="categoria-card variante-card">

                                <button type="button" class="btn-editar-cat" wire:click.stop="editarVariante({{ $item->id }})" title="Editar variante">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <div class="categoria-badge">
                                    Stock {{ number_format($item->stock_actual, 0, ',', '.') }}
                                </div>

                                <div class="categoria-img">
                                    <img src="{{ $item->imagen ? Storage::url($item->imagen) : Storage::url('categorias/categoria.jpg') }}" alt="{{ $item->descripcion }}">
                                </div>

                                <div class="categoria-body">

                                    <h4>{{ $item->descripcion }}</h4>

                                    <div class="mb-2">
                                        <strong>Producto:</strong>
                                        {{ $item->producto->descripcion ?? '' }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Categoría:</strong>
                                        {{ $item->producto->categoria->descripcion ?? 'SIN CATEGORÍA' }}
                                    </div>

                                    <div class="mb-2">
                                        <strong>Marca:</strong>
                                        {{ $item->marca->descripcion ?? 'SIN MARCA' }}
                                    </div>

                                    <div class="categoria-info d-flex justify-content-between mt-3">

                                        <div title="Stock mínimo">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            <strong>{{ number_format($item->stock_minimo, 0, ',', '.') }}</strong>
                                        </div>

                                        <div title="Precio venta">
                                            <i class="fas fa-money-bill text-info"></i>
                                            <strong>{{ number_format($item->precio_venta, 0, ',', '.') }}</strong>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        @endif

                    </div>

                @empty

                    <div class="col-12">
                        <div class="alert alert-warning text-center mb-0">
                            <i class="fas fa-exclamation-circle"></i>
                            No se encontraron registros.
                        </div>
                    </div>

                @endforelse

            </div>


        </div>


    </div>
    @include('producto.crear')
    @include('producto.crear_variante')

    @script
        <script>
            $('#marca_variante_id').select2({
                dropdownParent: $('#modal_variante'),
                width: '100%',
                placeholder: 'Seleccione marca'
            });

            $('#marca_variante_id').on('change', function () {
                $wire.set('marca_variante_id', $(this).val());
            });

            $('#categoria_id').select2({
                dropdownParent: $('#modal_producto'),
                width: '100%',
                placeholder: 'Seleccione categoría'
            });

            $('#categoria_id').on('change', function () {
                $wire.set('categoria_id', $(this).val());
            });
        </script>
    @endscript
</div>
