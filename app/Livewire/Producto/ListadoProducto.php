<?php

namespace App\Livewire\Producto;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoProducto extends Component
{

    public $categorias;
    public $search_categoria_id;
    public $productos;
    public $search_producto_id;
    public $search_estado_stock = 0;
    public $buscar;


    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->categorias = Categoria::where('estado_id', 1)
            ->orderBy('descripcion')
            ->get();

        $this->search_categoria_id = 0;
        $this->search_producto_id = 0;
        $this->productos = [];
    }

    public function updatedSearchCategoriaId()
    {
        $this->search_producto_id = 0;

        $this->cargar_productos();
        $this->resetPage();

        $this->dispatch(
            'productos-actualizados',
            productos: $this->productos
            ->map(fn ($producto) => [
                'id' => $producto->id,
                'descripcion' => $producto->descripcion,
            ])
            ->values()
            ->toArray()
        );
    }

    public function cargar_productos()
    {
        $categoriaId = (int) $this->search_categoria_id;

        if ($categoriaId > 0) {
            $this->productos = Producto::where('categoria_id', $categoriaId)
                ->where('estado_id', 1)
                ->orderBy('descripcion')
                ->get();
        } else {
            $this->productos = collect();
        }
    }

    public function render()
    {
        $buscar = trim($this->buscar);
        $estadoStock = (int) $this->search_estado_stock;

        $data = ProductoVariante::with([
            'producto.categoria',
            'marca',
        ])
        ->where('estado_id', 1)
        // Búsqueda general
        ->when($buscar !== '', function ($query) use ($buscar) {
            $query->where(function ($filtro) use ($buscar) {
                $filtro->where('descripcion', 'like', "%{$buscar}%")
                    ->orWhere('codigo', 'like', "%{$buscar}%")
                    ->orWhere('codigo_barra', 'like', "%{$buscar}%")
                    ->orWhereHas('producto', function ($producto) use ($buscar) {
                        $producto->where(
                            'descripcion',
                            'like',
                            "%{$buscar}%"
                        );
                    })
                    ->orWhereHas('marca', function ($marca) use ($buscar) {
                        $marca->where(
                            'descripcion',
                            'like',
                            "%{$buscar}%"
                        );
                    });
            });
        })
        // Categoría
        ->when(
            (int) $this->search_categoria_id > 0,
            function ($query) {
                $query->whereHas('producto', function ($producto) {
                    $producto->where(
                        'categoria_id',
                        $this->search_categoria_id
                    );
                });
            }
        )
        // Producto
        ->when(
            (int) $this->search_producto_id > 0,
            fn ($query) => $query->where(
                'producto_id',
                $this->search_producto_id
            )
        )
        // Stock bajo
        ->when($estadoStock === 1, function ($query) {
            $query->where('stock_actual', '>', 0)
                ->whereColumn('stock_actual', '<=', 'stock_minimo');
        })
        // Agotados
        ->when($estadoStock === 2, function ($query) {
            $query->where('stock_actual', '<=', 0);
        })
        ->orderBy('descripcion')
        ->paginate(30);

        return view('livewire.producto.listado-producto', compact('data'));
    }

    public function aplicarFiltros()
    {
        $this->buscar = '';
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->buscar = '';
        $this->search_categoria_id = 0;
        $this->search_producto_id = 0;
        $this->search_estado_stock = 0;
        $this->productos = collect();

        $this->dispatch('filtros-limpiados');

        // Si utilizás paginación:
        // $this->resetPage();
    }

    public function updatedBuscar($valor)
    {
        if (trim($valor) !== '') {
            $this->search_categoria_id = 0;
            $this->search_producto_id = 0;
            $this->search_estado_stock = 0;
            $this->productos = collect();
            $this->dispatch('filtros-limpiados');
        }

        $this->resetPage();
    }

    public function updatedSearchProductoId()
    {
        $this->resetPage();
    }

    public function updatedSearchEstadoStock()
    {
        $this->resetPage();
    }

}
