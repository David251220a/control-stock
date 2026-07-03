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
                    @include('categoria.crear')
                </div>

            </div>
        </div>

        <div class="widget-content widget-content-area">

            <div class="row mb-4">

                <div class="col-md-12">

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar categoría..."
                            wire:model.debounce.500ms="buscar">

                    </div>

                </div>

            </div>

            <div class="row mt-4">

                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

                    <div class="categoria-card"
                        wire:click="abrirCategoria(1)">

                        <button type="button"
                                class="btn-editar-cat"
                                wire:click.stop="editarCategoria(1)"
                                title="Editar categoría">
                            <i class="fas fa-edit"></i>
                        </button>

                        <div class="categoria-badge">
                            5 Hijos
                        </div>

                        <div class="categoria-img">
                            <img src="{{ Storage::url('iconos/juguetes.jpg') }}" alt="Juguetes">
                        </div>

                        <div class="categoria-body">
                            <h4>Juguetes</h4>

                            <div class="categoria-info">
                                <div>
                                    <i class="fas fa-box"></i>
                                    120 Productos
                                </div>

                                <div>
                                    <i class="fas fa-folder-open"></i>
                                    5 Subcategorías
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
