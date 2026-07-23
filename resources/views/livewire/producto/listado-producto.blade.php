<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">

        <div class="widget-header">
            <div class="row align-items-center p-3">

                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        Listado Producto
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

            <div class="row mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label>Buscar</label>

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>

                        <input type="text"
                            class="form-control"
                            wire:model.live.debounce.500ms="buscar"
                            placeholder="Producto, variante, código o código de barras...">

                        @if(trim($buscar) !== '')
                            <div class="input-group-append">
                                <button type="button"
                                        class="btn btn-light"
                                        wire:click="limpiarBusqueda"
                                        title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="row align-items-end">
                {{-- CATEGORÍA --}}
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Categoría</label>

                        <div wire:ignore>
                            <select id="search_categoria_id" class="form-control">
                                <option value="0">TODAS LAS CATEGORÍAS</option>

                                @foreach($categorias as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- PRODUCTO --}}
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Producto</label>

                        <div wire:ignore>
                            <select id="search_producto_id" class="form-control">
                                <option value="0">TODOS LOS PRODUCTOS</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                {{-- ESTADO DE STOCK --}}
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="form-group">

                        <div class="stock-filter-group">
                            <button type="button" class="stock-filter-btn {{ (int) $search_estado_stock === 0 ? 'active estado-todos' : '' }}" wire:click="$set('search_estado_stock', 0)">
                                <i class="fas fa-boxes mr-1"></i>
                                Todos
                            </button>

                            <button type="button" class="stock-filter-btn {{ (int) $search_estado_stock === 1 ? 'active estado-bajo' : '' }}" wire:click="$set('search_estado_stock', 1)">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Stock bajo
                            </button>

                            <button type="button" class="stock-filter-btn {{(int) $search_estado_stock === 2 ? 'active estado-agotado' : ''  }}" wire:click="$set('search_estado_stock', 2)">
                                <i class="fas fa-times-circle mr-1"></i>
                                Agotados
                            </button>

                            <button type="button" class="btn btn-aplicar-filtros" wire:click="aplicarFiltros">
                                <i class="fas fa-filter mr-1"></i>
                                Aplicar filtros
                            </button>

                            <button type="button" class="btn btn-info mb-2" wire:click="limpiarFiltros">
                                <i class="fas fa-eraser mr-1"></i>
                                Limpiar
                            </button>

                        </div>
                    </div>
                </div>
            </div>


            {{-- LISTADO DE VARIANTES --}}
            <div class="row mt-4">

                @forelse($data as $item)

                    @php
                        if ($item->stock_actual <= 0) {
                            $claseStock = 'stock-agotado';
                            $textoStock = 'SIN STOCK';
                            $iconoStock = 'fas fa-times-circle';
                        } elseif ($item->stock_actual <= $item->stock_minimo) {
                            $claseStock = 'stock-bajo';
                            $textoStock = 'STOCK BAJO: '
                                . number_format($item->stock_actual, 0, ',', '.');
                            $iconoStock = 'fas fa-exclamation-triangle';
                        } else {
                            $claseStock = 'stock-disponible';
                            $textoStock = 'STOCK: '
                                . number_format($item->stock_actual, 0, ',', '.');
                            $iconoStock = 'fas fa-check-circle';
                        }
                    @endphp

                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 producto-variante-col" wire:key="variante-{{ $item->id }}">

                        <div class="categoria-card variante-card {{ $claseStock }}">

                            <div class="variante-acciones">

                                <button type="button" class="btn-variante-accion accion-editar" wire:click.stop="editarVariante({{ $item->id }})" title="Editar variante">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button" class="btn-variante-accion accion-ver" wire:click.stop="verMovimientos({{ $item->id }})" title="Ver movimientos">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="btn-variante-accion accion-ajustar" wire:click.stop="abrirAjusteStock({{ $item->id }})" title="Ajustar stock">
                                    <span class="signo-ajuste">±</span>
                                </button>

                            </div>

                            {{-- ESTADO DEL STOCK --}}
                            <div class="categoria-badge stock-badge">
                                <i class="{{ $iconoStock }} mr-1"></i>
                                {{ $textoStock }}
                            </div>

                            {{-- IMAGEN --}}
                            <div class="categoria-img">
                                <img src="{{ $item->imagen ? Storage::url($item->imagen) : Storage::url('categorias/categoria.jpg') }}" alt="{{ $item->descripcion }}">
                            </div>

                            {{-- INFORMACIÓN --}}
                            <div class="categoria-body">

                                <h4 title="{{ $item->descripcion }}">
                                    {{ $item->descripcion }}
                                </h4>

                                <div class="mb-2">
                                    <strong>Producto:</strong>
                                    {{ $item->producto->descripcion ?? 'SIN PRODUCTO' }}
                                </div>

                                <div class="mb-2">
                                    <strong>Categoría:</strong>
                                    {{ $item->producto->categoria->descripcion
                                        ?? 'SIN CATEGORÍA' }}
                                </div>

                                <div class="mb-2">
                                    <strong>Marca:</strong>
                                    {{ $item->marca->descripcion ?? 'SIN MARCA' }}
                                </div>

                                @if($item->codigo_barra)
                                    <div class="mb-2">
                                        <strong>Código de barras:</strong>
                                        {{ $item->codigo_barra }}
                                    </div>
                                @endif

                                <div class="categoria-info d-flex justify-content-between mt-3">

                                    <div title="Stock mínimo">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>
                                            {{ number_format(
                                                $item->stock_minimo,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </strong>
                                    </div>

                                    <div title="Precio de venta">
                                        <i class="fas fa-money-bill"></i>
                                        <strong>
                                            {{ number_format(
                                                $item->precio_venta,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">
                        <div class="alert alert-warning text-center mb-0">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            No se encontraron productos.
                        </div>
                    </div>

                @endforelse

            </div>

            {{-- PAGINACIÓN --}}
            @if($data->hasPages())
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $data->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif

        </div>

    </div>

    @script
        <script>

            const iniciarSelectCategoria = () => {
                const categoria = $('#search_categoria_id');

                if (categoria.hasClass('select2-hidden-accessible')) {
                    categoria.select2('destroy');
                }

                categoria.select2({
                    width: '100%',
                    placeholder: 'Seleccione una categoría'
                });

                categoria
                    .val(String($wire.search_categoria_id ?? 0))
                    .trigger('change.select2');

                categoria
                    .off('change.livewireCategoria')
                    .on('change.livewireCategoria', function () {
                        const categoriaId = parseInt($(this).val() || 0);

                        // Limpia inmediatamente el segundo Select2
                        $('#search_producto_id')
                            .empty()
                            .append(new Option('TODOS', '0', true, true))
                            .trigger('change.select2');

                        $wire.set('search_categoria_id', categoriaId);
                    });
            };

            const iniciarSelectProducto = () => {
                const producto = $('#search_producto_id');

                if (producto.hasClass('select2-hidden-accessible')) {
                    producto.select2('destroy');
                }

                producto.select2({
                    width: '100%',
                    placeholder: 'Seleccione un producto'
                });

                producto
                    .off('change.livewireProducto')
                    .on('change.livewireProducto', function () {
                        const productoId = parseInt($(this).val() || 0);

                        $wire.set('search_producto_id', productoId);
                    });
            };

            iniciarSelectCategoria();
            iniciarSelectProducto();

            $wire.on('productos-actualizados', (event) => {
                const productos = event.productos ?? [];
                const productoSelect = $('#search_producto_id');

                productoSelect.empty();
                productoSelect.append(
                    new Option('TODOS', '0', true, true)
                );

                productos.forEach((producto) => {
                    productoSelect.append(
                        new Option(
                            producto.descripcion,
                            String(producto.id),
                            false,
                            false
                        )
                    );
                });

                productoSelect
                    .val('0')
                    .trigger('change.select2');
            });

            $wire.on('filtros-limpiados', () => {
                $('#search_categoria_id')
                    .val('0')
                    .trigger('change.select2');

                $('#search_producto_id')
                    .empty()
                    .append(new Option('TODOS LOS PRODUCTOS', '0', true, true))
                    .trigger('change.select2');
            });

        </script>
    @endscript

</div>
