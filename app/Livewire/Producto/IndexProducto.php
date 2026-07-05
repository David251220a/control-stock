<?php

namespace App\Livewire\Producto;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class IndexProducto extends Component
{
    public $categoria_id;
    public $marca_id;
    public $descripcion;
    public $observacion;
    public $maneja_stock = true;
    public $maneja_variantes = false;
    public $categorias;
    public $marcas;
    public $buscar;

    public $imagen;
    public $variante_descripcion;
    public $codigo_barra;
    public $stock_actual = 0;
    public $stock_minimo = 1;
    public $precio_compra = 0;
    public $precio_venta = 0;
    public $producto_id;
    public $marca_variante_id;

    public $titulo_producto = 'Crear Producto';
    public $tipo = 1;

    public $producto_edicion_id;

    public $producto_actual_id = null;
    public $producto_actual_nombre = null;

    public $tipo_lista = 1;

    public $titulo_variante = 'Agregar Variante';
    public $tipo_variante = 1;
    public $variante_id;

    public $filtro_categoria_id = 0;
    public $filtro_producto_id = 0;
    public $filtro_marca_id = 0;


    use WithFileUploads;

    public function mount()
    {

    }


    public function render()
    {
        $this->categorias = $this->categoriasParaCombo();
        $this->marcas = Marca::where('estado_id', 1)->get();

        $data = collect();
        $productosCombo = Producto::where('estado_id', 1)
        ->orderBy('descripcion')
        ->get();

        $buscar = trim($this->buscar ?? '');

        if ($this->tipo_lista == 1) {

            $data = Producto::with(['categoria', 'variantes'])
            ->where('estado_id', 1)
            ->when($this->filtro_categoria_id > 0, function ($q) {
                $q->where('categoria_id', $this->filtro_categoria_id);
            })
            ->when(trim($this->buscar) != '', function ($q) {
                $q->where('descripcion', 'like', '%' . trim($this->buscar) . '%');
            })
            ->orderBy('descripcion')
            ->get();
        } else {
            $data = ProductoVariante::with(['marca', 'producto.categoria'])
            ->where('estado_id', 1)

            ->when($this->producto_actual_id > 0, function ($q) {
                $q->where('producto_id', $this->producto_actual_id);
            })

            ->when($this->filtro_categoria_id > 0, function ($q) {
                $q->whereHas('producto', function ($p) {
                    $p->where('categoria_id', $this->filtro_categoria_id);
                });
            })

            ->when($this->filtro_producto_id > 0, function ($q) {
                $q->where('producto_id', $this->filtro_producto_id);
            })

            ->when($this->filtro_marca_id > 0, function ($q) {
                $q->where('marca_id', $this->filtro_marca_id);
            })

            ->where(function ($q) use ($buscar) {
                $q->where('descripcion', 'like', "%{$buscar}%")
                ->orWhere('codigo', 'like', "%{$buscar}%")
                ->orWhere('codigo_barra', 'like', "%{$buscar}%")
                ->orWhereHas('marca', function ($m) use ($buscar) {
                    $m->where('descripcion', 'like', "%{$buscar}%");
                })
                ->orWhereHas('producto', function ($p) use ($buscar) {
                    $p->where('descripcion', 'like', "%{$buscar}%");
                });
            })

            ->orderBy('descripcion')
            ->get();
        }

        return view('livewire.producto.index-producto', compact('data','productosCombo'));
    }

    public function buscarProducto()
    {

    }

    public function grabar_producto()
    {
        $this->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:250',
            'observacion' => 'nullable',
        ]);

        $existe = Producto::where('categoria_id', $this->categoria_id)
        ->where('descripcion', strtoupper(trim($this->descripcion)))
        ->where('estado_id', 1)
        ->exists();

        if ($existe) {
            $this->dispatch('mensaje_error', 'Ya existe un producto con la misma descripción.');
            return;
        }

        Producto::create([
            'categoria_id' => $this->categoria_id,
            'descripcion' => strtoupper(trim($this->descripcion)),
            'observacion' => $this->observacion,
            'estado_id' => 1,
            'user_id' => auth()->id(),
            'usuario_modificacion' => auth()->id(),
        ]);

        $this->reset([
            'categoria_id',
            'descripcion',
            'observacion',
        ]);

        $this->reset(['categoria_id']);
        $this->js("$('#categoria_id').val(null).trigger('change');");

        $this->reset(['marca_id']);
        $this->js("$('#marca_id').val(null).trigger('change');");

        $this->dispatch('cerrar_modal_producto');
    }

    public function categoriasParaCombo($padreId = null, $nivel = 0)
    {
        $categorias = Categoria::where('estado_id', 1)
        ->where('categoria_padre_id', $padreId)
        ->orderBy('descripcion')
        ->get();

        $resultado = collect();

        foreach ($categorias as $categoria) {
            $categoria->nombre_combo = str_repeat('— ', $nivel) . $categoria->descripcion;
            $resultado->push($categoria);

            $hijos = $this->categoriasParaCombo($categoria->id, $nivel + 1);
            $resultado = $resultado->merge($hijos);
        }

        return $resultado;
    }

    public function agregarVariante($producto_id)
    {
        $this->reset([
            'variante_descripcion',
            'codigo_barra',
            'stock_actual',
            'stock_minimo',
            'precio_compra',
            'precio_venta',
            'imagen',
            'marca_variante_id'
        ]);

        $this->stock_actual = 0;
        $this->stock_minimo = 1;
        $this->precio_compra = 0;
        $this->precio_venta = 0;

        $this->titulo_variante = 'Agregar Variante';
        $this->tipo_variante = 1;

        $this->producto_id = $producto_id;
        $this->marca_variante_id = $this->marcas->first()->id;

        $this->dispatch('abrir_modal', id: 'modal_variante');

        $this->js("
            setTimeout(() => {
                $('#marca_variante_id').val('{$this->marca_variante_id}').trigger('change');
            }, 200);
        ");
    }

    public function grabar_variante()
    {
        $this->precio_compra = str_replace('.', '', $this->precio_compra);
        $this->precio_compra = str_replace(',', '.', $this->precio_compra);

        $this->precio_venta = str_replace('.', '', $this->precio_venta);
        $this->precio_venta = str_replace(',', '.', $this->precio_venta);

        $this->validate([
            'producto_id' => 'required|exists:productos,id',
            'marca_variante_id' => 'nullable|exists:marcas,id',
            'variante_descripcion' => 'required|max:150',
            'codigo_barra' => 'nullable|max:100',
            'imagen' => 'nullable|image|max:2048',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
        ]);

        if ($this->precio_venta <= 0) {
            $this->dispatch('mensaje_error', 'Debe ingresar un precio de venta mayor a cero.');
            return;
        }

        $descripcion = strtoupper(trim($this->variante_descripcion));

        $existe = ProductoVariante::where('producto_id', $this->producto_id)
        ->where('descripcion', $descripcion)
        ->where('estado_id', 1)
        ->where(function ($q) {
            if ($this->marca_variante_id) {
                $q->where('marca_id', $this->marca_variante_id);
            } else {
                $q->whereNull('marca_id');
            }
        })
        ->exists();

        if ($existe) {
            $this->addError('variante_descripcion', 'Ya existe una variante con la misma marca y descripción.');
            return;
        }

        $rutaImagen = null;
        $codigo = $this->generarCodigoVariante();

        if ($this->imagen) {
            $rutaImagen = $this->imagen->store('productos', 'public');
        }

        ProductoVariante::create([
            'producto_id' => $this->producto_id,
            'marca_id' => $this->marca_variante_id,
            'descripcion' => $descripcion,
            'imagen' => $rutaImagen,
            'codigo' => $codigo,
            'codigo_barra' => $this->codigo_barra,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'estado_id' => 1,
            'user_id' => auth()->id(),
            'usuario_modificacion' => auth()->id(),
        ]);

        $this->reset([
            'marca_variante_id',
            'variante_descripcion',
            'codigo_barra',
            'imagen',
            'stock_actual',
            'stock_minimo',
            'precio_compra',
            'precio_venta',
        ]);

        $this->js("$('#marca_variante_id').val(null).trigger('change');");

        $this->dispatch('cerrar_modal', id: 'modal_variante');
        $this->dispatch('mensaje_exitoso', mensaje: 'Variante creada correctamente');
    }

    private function generarCodigoVariante()
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $codigo = '';

            for ($i = 0; $i < 10; $i++) {
                $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }

        } while (ProductoVariante::where('codigo', $codigo)->exists());

        return $codigo;
    }

    public function editarProducto($id)
    {
        $producto = Producto::find($id);
        $this->producto_edicion_id = $producto->id;
        $this->titulo_producto = 'Editar Producto';
        $this->tipo = 2;
        $this->categoria_id = $producto->categoria_id;
        $this->descripcion = $producto->descripcion;
        $this->observacion = $producto->observacion;
        $this->dispatch('abrir_modal', id: 'modal_producto');
        $this->js("
            setTimeout(() => {
                $('#categoria_id').val('{$this->categoria_id}').trigger('change');
            }, 200);
        ");
    }

    public function grabar_edicion_producto()
    {
        $this->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:250',
            'observacion' => 'nullable',
        ]);

        $existe = Producto::where('categoria_id', $this->categoria_id)
        ->where('descripcion', strtoupper(trim($this->descripcion)))
        ->where('estado_id', 1)
        ->where('id', '<>', $this->producto_edicion_id)
        ->exists();

        if ($existe) {
            $this->addError('descripcion', 'Ya existe un producto con la misma descripción.');
            return;
        }

        $producto = Producto::find($this->producto_edicion_id);

        $producto->update([
            'categoria_id' => $this->categoria_id,
            'descripcion' => strtoupper(trim($this->descripcion)),
            'observacion' => $this->observacion,
            'estado_id' => 1,
            'user_id' => auth()->id(),
            'usuario_modificacion' => auth()->id(),
        ]);

        $this->reset([
            'categoria_id',
            'descripcion',
            'observacion',
        ]);

        $this->reset(['categoria_id']);
        $this->js("$('#categoria_id').val(null).trigger('change');");

        $this->reset(['marca_id']);
        $this->js("$('#marca_id').val(null).trigger('change');");
        $this->cerrar_modal('modal_producto');
    }

    public function cerrar_modal($id)
    {
        $this->dispatch('cerrar_modal', id: $id);
    }

    public function abril_modal($id, $tipo)
    {
        if($tipo == 1){
            $this->titulo_producto = 'Crear Producto';
            $this->tipo = 1;
            $this->categoria_id = $this->categorias->first()->id;
            $this->js("
                setTimeout(() => {
                    $('#categoria_id').val('{$this->categoria_id}').trigger('change');
                }, 200);
            ");
        }

        if($tipo == 2){
            $this->titulo_producto = 'Editar Producto';
            $this->tipo = 2;
        }
        $this->dispatch('abrir_modal', id: $id);
    }

    public function abrirProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $this->tipo_lista = 2;
        $this->producto_actual_id = $producto->id;
        $this->producto_actual_nombre = $producto->descripcion;
    }

    public function volverProductos()
    {
        $this->tipo_lista = 1;
        $this->buscar = '';
        $this->producto_actual_id = null;
        $this->producto_actual_nombre = null;
    }

    public function editarVariante($id)
    {
        $variante = ProductoVariante::find($id);
        $this->variante_id = $variante->id;

        $this->reset([
            'variante_descripcion',
            'codigo_barra',
            'stock_actual',
            'stock_minimo',
            'precio_compra',
            'precio_venta',
            'imagen',
            'marca_variante_id'
        ]);

        $this->stock_actual = $variante->stock_actual;
        $this->stock_minimo = $variante->stock_minimo;
        $this->precio_compra = number_format($variante->precio_compra, 0, ',', '.');
        $this->precio_venta  = number_format($variante->precio_venta, 0, ',', '.');
        $this->producto_id = $variante->producto_id;
        $this->marca_variante_id = $variante->marca_id;
        $this->variante_descripcion = $variante->descripcion;
        $this->titulo_variante = 'Editar Variante';
        $this->tipo_variante = 2;
        $this->dispatch('abrir_modal', id: 'modal_variante');
        $this->js("
            setTimeout(() => {
                $('#marca_variante_id').val('{$this->marca_variante_id}').trigger('change');
            }, 200);
        ");

    }

    public function grabar_edicion_variante()
    {
        $this->precio_compra = str_replace('.', '', $this->precio_compra);
        $this->precio_compra = str_replace(',', '.', $this->precio_compra);

        $this->precio_venta = str_replace('.', '', $this->precio_venta);
        $this->precio_venta = str_replace(',', '.', $this->precio_venta);

        $this->validate([
            'producto_id' => 'required|exists:productos,id',
            'marca_variante_id' => 'nullable|exists:marcas,id',
            'variante_descripcion' => 'required|max:150',
            'codigo_barra' => 'nullable|max:100',
            'imagen' => 'nullable|image|max:2048',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
        ]);

        if ($this->precio_venta <= 0) {
            $this->dispatch('mensaje_error', 'Debe ingresar un precio de venta mayor a cero.');
            return;
        }

        $descripcion = strtoupper(trim($this->variante_descripcion));

        $existe = ProductoVariante::where('producto_id', $this->producto_id)
        ->where('descripcion', $descripcion)
        ->where('estado_id', 1)
        ->where('id', '<>', $this->variante_id)
        ->where('marca_id', $this->marca_variante_id)
        ->exists();

        if ($existe) {
            $this->addError('variante_descripcion', 'Ya existe una variante con la misma marca y descripción.');
            return;
        }

        $variante = ProductoVariante::find($this->variante_id);
        $rutaImagen = $variante->imagen;
        $codigo = $variante->codigo;

        if ($this->imagen) {
            $rutaImagen = $this->imagen->store('productos', 'public');
        }

        $variante->update([
            'producto_id' => $this->producto_id,
            'marca_id' => $this->marca_variante_id,
            'descripcion' => $descripcion,
            'imagen' => $rutaImagen,
            'codigo' => $codigo,
            'codigo_barra' => $this->codigo_barra,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'estado_id' => 1,
            'user_id' => auth()->id(),
            'usuario_modificacion' => auth()->id(),
        ]);

        $this->reset([
            'marca_variante_id',
            'variante_descripcion',
            'codigo_barra',
            'imagen',
            'stock_actual',
            'stock_minimo',
            'precio_compra',
            'precio_venta',
        ]);

        $this->js("$('#marca_variante_id').val(null).trigger('change');");

        $this->dispatch('cerrar_modal', id: 'modal_variante');
        $this->dispatch('mensaje_exitoso', mensaje: 'Variante editado correctamente');
    }

}
