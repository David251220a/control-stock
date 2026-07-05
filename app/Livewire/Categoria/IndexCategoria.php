<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Override;

class IndexCategoria extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $categoriasPadres;
    public $descripcion;
    public $categoria_padre_id;
    public $imagen;
    public $inputFile;
    public $buscar = '';
    public $categoriaActualId = null;
    public $rutaCategorias = [];

    public $editar_descripcion;
    public $editar_categoria_padre_id;
    public $editar_imagen;
    public $data ;
    public $editarInputFile = 0;
    public $selectCategoriaKey = 0;

    public function mount()
    {
        $this->categoriasPadres = $this->categoriasParaCombo();
        $this->categoria_padre_id = null;
    }

    public function render()
    {
        $categorias = Categoria::withCount(['hijos', 'productos'])
        ->where('estado_id', 1)
        ->when(trim($this->buscar) != '', function ($query) {
            $query->where('descripcion', 'like', '%' . trim($this->buscar) . '%');
        })
        ->when(trim($this->buscar) == '', function ($query) {
            $query->where('categoria_padre_id', $this->categoriaActualId);
        })
        ->orderBy('orden')
        ->paginate(20);

        $this->categoriasPadres = $this->categoriasParaCombo();

        return view('livewire.categoria.index-categoria', compact('categorias'));
    }

    public function buscarCategoria()
    {

    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function volverInicio()
    {
        $this->rutaCategorias = [];

        $this->categoriaActualId = null;

        $this->buscar = '';

        $this->resetPage();
    }

    public function abrirCategoria($id)
    {
        $categoria = Categoria::withCount('hijos')->find($id);

        if (!$categoria) {
            return;
        }

        if ($categoria->hijos_count == 0) {
            return;
        }

        $this->rutaCategorias[] = [
            'id' => $categoria->id,
            'descripcion' => $categoria->descripcion,
        ];

        $this->categoriaActualId = $categoria->id;
        $this->buscar = '';
        $this->resetPage();
    }

    public function volverRuta($indice)
    {
        $this->rutaCategorias = array_slice($this->rutaCategorias, 0, $indice + 1);

        $ultima = end($this->rutaCategorias);

        $this->categoriaActualId = $ultima['id'];

        $this->resetPage();
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

    public function grabar_categoria()
    {
        if (trim($this->descripcion) == '') {
            $this->dispatch('mensaje_error', 'Debe ingresar la descripción.');
            return;
        }

        if ($this->imagen) {

            $permitidos = ['jpg', 'jpeg'];
            $extension = strtolower($this->imagen->getClientOriginalExtension());

            if (!in_array($extension, $permitidos)) {
                $this->dispatch('mensaje_error', 'La imagen debe ser JPG o JPEG.');
                return;
            }

            if ($this->imagen->getSize() > 2048 * 1024) {
                $this->dispatch('mensaje_error', 'La imagen no debe superar los 2 MB.');
                return;
            }
        }

        $rutaImagen = null;

        if ($this->imagen) {
            $rutaImagen = $this->imagen->store('categorias', 'public');
        }
        $existe = Categoria::whereRaw('UPPER(descripcion) = ?', [strtoupper(trim($this->descripcion))])
        ->where('categoria_padre_id', $this->categoria_padre_id)
        ->where('estado_id', 1)
        ->exists();

        if ($existe) {
            $this->dispatch('mensaje_error', 'Ya existe una categoría con esa descripción.');
            return;
        }

        $orden = Categoria::where('categoria_padre_id', $this->categoria_padre_id)
        ->max('orden');
        $orden = ($orden ?? 0) + 1;

        $codigoBase = strtoupper(substr(trim($this->descripcion), 0, 3));
        $codigo = $codigoBase;
        $contador = 1;
        while (Categoria::where('codigo', $codigo)->exists()) {
            $codigo = $codigoBase . $contador;
            $contador++;
        }

        Categoria::create([
            'descripcion' => strtoupper(trim($this->descripcion)),
            'categoria_padre_id' => null,
            'codigo' => $codigo,
            'imagen' => $rutaImagen,
            'orden' => $orden,
            'estado_id' => 1,
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('mensaje_exitoso', 'Categoría creada correctamente.');
        $this->dispatch('cerrar_modal_categoria');
        $this->reset(['descripcion', 'categoria_padre_id', 'imagen']);
        $this->inputFile++;
        $this->categoriasPadres = $this->categoriasParaCombo();
        $this->categoria_padre_id = null;
        $this->selectCategoriaKey++;
    }

    public function editarCategoria($id)
    {
        $this->data = Categoria::find($id);
        if (!$this->data) {
            $this->dispatch('mensaje_error', 'Categoría no encontrada.');
            return;
        }

        $this->editar_categoria_padre_id = $this->data->categoria_padre_id;
        $this->editar_descripcion = $this->data->descripcion;
        $this->editar_imagen = null;
        $this->editarInputFile++;
        $this->categoriasPadres = $this->categoriasParaCombo();
        $this->dispatch('abrir_modal_categoria_editar');
    }

    public function editar_categoria()
    {
        if (trim($this->editar_descripcion) == '') {
            $this->dispatch('mensaje_error', 'Debe ingresar la descripción.');
            return;
        }

        if ($this->imagen) {

            $permitidos = ['jpg', 'jpeg'];
            $extension = strtolower($this->editar_imagen->getClientOriginalExtension());

            if (!in_array($extension, $permitidos)) {
                $this->dispatch('mensaje_error', 'La imagen debe ser JPG o JPEG.');
                return;
            }

            if ($this->editar_imagen->getSize() > 2048 * 1024) {
                $this->dispatch('mensaje_error', 'La imagen no debe superar los 2 MB.');
                return;
            }
        }

        $rutaImagen = $this->data->imagen;

        if ($this->editar_imagen) {
            $rutaImagen = $this->editar_imagen->store('categorias', 'public');
        }

        $existe = Categoria::whereRaw('UPPER(descripcion) = ?', [strtoupper(trim($this->editar_descripcion))])
        ->where('categoria_padre_id', $this->editar_categoria_padre_id)
        ->where('estado_id', 1)
        ->where('id', '<>', $this->data->id)
        ->exists();

        if ($existe) {
            $this->dispatch('mensaje_error', 'Ya existe una categoría con esa descripción.');
            return;
        }

        $this->data->update([
            'descripcion' => strtoupper(trim($this->editar_descripcion)),
            'categoria_padre_id' => null,
            'codigo' => $this->data->codigo,
            'imagen' => $rutaImagen,
            'orden' => $this->data->orden,
            'estado_id' => 1,
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('mensaje_exitoso', 'Categoría editada correctamente.');
        $this->dispatch('cerrar_modal_categoria_editar');
        $this->reset(['editar_descripcion', 'editar_categoria_padre_id', 'editar_imagen','data']);
        $this->editarInputFile++;
    }
}
