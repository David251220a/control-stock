<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class IndexCategoria extends Component
{
    public $categoriasPadres;
    public $descripcion;
    public $categoria_padre_id;
    public $imagen;

    public function mount()
    {
        $this->categoriasPadres = $this->categoriasParaCombo();
        $this->categoria_padre_id = $this->categoriasPadres->first();

    }

    public function render()
    {
        return view('livewire.categoria.index-categoria');
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
            $this->emit('mensaje_error', 'Debe ingresar la descripción.');
            return;
        }

        if ($this->imagen) {

            $permitidos = ['jpg', 'jpeg'];
            $extension = strtolower($this->imagen->getClientOriginalExtension());

            if (!in_array($extension, $permitidos)) {
                $this->emit('mensaje_error', 'La imagen debe ser JPG o JPEG.');
                return;
            }

            if ($this->imagen->getSize() > 2048 * 1024) {
                $this->emit('mensaje_error', 'La imagen no debe superar los 2 MB.');
                return;
            }
        }

        $rutaImagen = null;

        if ($this->imagen) {
            $rutaImagen = $this->imagen->store('categorias', 'public');
        }

        Categoria::create([
            'descripcion' => strtoupper(trim($this->descripcion)),
            'categoria_padre_id' => $this->categoria_padre_id ?: null,
            'imagen' => $rutaImagen,
            'estado_id' => 1,
            'user_id' => auth()->id(),
        ]);

        $this->emit('mensaje_exitoso', 'Categoría creada correctamente.');
        $this->reset(['descripcion', 'categoria_padre_id', 'imagen']);
    }
}
