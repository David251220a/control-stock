<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">

        <div class="widget-header">
            <div class="row align-items-center p-3">

                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        Categorías
                    </h3>
                    <small class="text-muted">
                        Administre las categorías de los productos
                    </small>
                </div>

                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <button class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#modal_categoria">
                        <i class="fas fa-plus"></i>
                        Nueva Categoría
                    </button>
                </div>

            </div>
        </div>

        <div class="widget-content widget-content-area">

            <div class="row mb-3">

                <div class="col-md-12">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb mb-0">

                            <li class="breadcrumb-item">

                                <a href="#"
                                wire:click.prevent="volverInicio">

                                    <i class="fas fa-home"></i>

                                    Categorías

                                </a>

                            </li>

                            @foreach($rutaCategorias as $indice => $ruta)

                                @if($loop->last)

                                    <li class="breadcrumb-item active">

                                        {{ $ruta['descripcion'] }}

                                    </li>

                                @else

                                    <li class="breadcrumb-item">

                                        <a href="#"
                                        wire:click.prevent="volverRuta({{ $indice }})">

                                            {{ $ruta['descripcion'] }}

                                        </a>

                                    </li>

                                @endif

                            @endforeach

                        </ol>

                    </nav>

                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-12">

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>

                        <input type="text" class="form-control" placeholder="Buscar producto..." wire:model.defer="buscar">

                        <button type="button" class="btn btn-primary" wire:click="buscarCategoria">
                            Buscar
                        </button>
                    </div>

                </div>

            </div>

            <div class="row mt-4">

                @forelse($categorias as $categoria)

                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

                        <div class="categoria-card" wire:click="abrirCategoria({{ $categoria->id }})">

                            <button type="button" class="btn-editar-cat" wire:click.stop="editarCategoria({{ $categoria->id }})" title="Editar categoría">
                                <i class="fas fa-edit"></i>
                            </button>

                            @if($categoria->hijos_count > 0)
                                <div class="categoria-badge">
                                    {{ $categoria->hijos_count }} Hijos
                                </div>
                            @endif

                            <div class="categoria-img">
                                <img src="{{ $categoria->imagen ? Storage::url($categoria->imagen) : Storage::url('categorias/categoria.jpg') }}" alt="{{ $categoria->descripcion }}">
                            </div>

                            <div class="categoria-body">

                                <h4>{{ $categoria->descripcion }}</h4>

                                <div class="categoria-info d-flex justify-content-between mt-3">

                                    <div title="Productos">
                                        <i class="fas fa-box-open text-warning"></i>
                                        <strong>{{ number_format($categoria->productos_count,0,',','.') }}</strong>
                                    </div>

                                    <div title="Subcategorías">
                                        <i class="fas fa-folder-open text-info"></i>
                                        <strong>{{ number_format($categoria->hijos_count,0,',','.') }}</strong>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="alert alert-warning text-center mb-0">

                            <i class="fas fa-exclamation-circle"></i>
                            No se encontraron categorías.

                        </div>

                    </div>

                @endforelse

            </div>

            @if($categorias->hasPages())
                <div class="d-flex justify-content-left mt-3">
                    {{ $categorias->links() }}
                </div>
            @endif

        </div>

    </div>
    @include('categoria.crear')
    @include('categoria.editar')
</div>
