<?php

namespace App\Livewire;

use App\Models\Banco;
use App\Models\Entidad;
use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaCobro;
use App\Models\FacturaDetalle;
use App\Models\FormaCobro;
use App\Models\Numeracion;
use App\Models\Persona;
use Livewire\Component;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Sexo;
use App\Models\Timbrado;
use App\Models\TipoPersona;
use Illuminate\Support\Facades\DB;

class VentaCarrito extends Component
{
    public $carrito = [];
    public $documento_cliente;
    public $cliente_id;
    public $cliente_nombre;
    public $cliente_documento;

    public $nuevo_documento;
    public $nuevo_nombre;
    public $nuevo_apellido;
    public $nuevo_celular;
    public $nuevo_email;
    public $nuevo_sexo_id;
    public $nuevo_tipo_persona_id;

    public $tipo_persona;
    public $sexo;

    public $forma_cobro_id;
    public $banco_id = 0;
    public $numero_comprobante;
    public $monto_recibido = 0;

    public $forma_cobros;
    public $bancos;
    public $banco_ver = 0;

    public $entidad;
    public $establecimiento;
    public $timbrado;

    public function mount()
    {
        $this->carrito = session()->get('carrito_venta', []);
        $cliente = session()->get('cliente_carrito');
        if ($cliente) {
            $this->cliente_id = $cliente['cliente_id'];
            $this->cliente_nombre = $cliente['cliente_nombre'];
            $this->cliente_documento = $cliente['cliente_documento'];
            $this->documento_cliente = $cliente['cliente_documento'];
        }
        $this->tipo_persona = TipoPersona::all();
        $this->sexo = Sexo::all();
        $this->forma_cobros = FormaCobro::where('estado_id', 1)->get();
        $this->bancos = Banco::where('estado_id', 1)->orderBy('descripcion')->get();
        $this->forma_cobro_id = $this->forma_cobros->first()->id ?? null;
        $this->monto_recibido = $this->total;
        $this->entidad = Entidad::find(1);
        $this->establecimiento = Establecimiento::find(1);
        $this->timbrado = Timbrado::find(1);

    }

    public function updatedFormaCobroId()
    {
        $forma = FormaCobro::find($this->forma_cobro_id);

        $this->banco_ver = $forma->banco_ver ?? 0;

        if ($this->banco_ver == 0) {
            $this->banco_id = 1; // SIN ESPECIFICAR o EFECTIVO
            $this->numero_comprobante = '';
        } else {
            $this->banco_id = 0;
        }
    }

    public function guardarSesion()
    {
        session()->put('carrito_venta', $this->carrito);
    }

    public function agregarVariante($varianteId)
    {
        $variante = ProductoVariante::with('producto')
            ->where('id', $varianteId)
            ->where('estado_id', 1)
            ->where('stock_actual', '>', 0)
            ->first();

        if (!$variante) {
            $this->dispatch('mensaje_error', 'No hay stock disponible.');
            return;
        }

        if (isset($this->carrito[$varianteId])) {
            if ($this->carrito[$varianteId]['cantidad'] >= $variante->stock_actual) {
                $this->dispatch('mensaje_error', 'No puede superar el stock disponible.');
                return;
            }

            $this->carrito[$varianteId]['cantidad']++;
        } else {
            $this->carrito[$varianteId] = [
                'variante_id' => $variante->id,
                'producto_id' => $variante->producto_id,
                'descripcion' => $variante->producto->descripcion,
                'variante' => $variante->descripcion,
                'stock_actual' => $variante->stock_actual,
                'precio' => $variante->precio_venta ?? $variante->producto->precio_venta ?? 0,
                'cantidad' => 1,
            ];
        }

        $this->guardarSesion();

    }

    public function quitar($productoId)
    {
        unset($this->carrito[$productoId]);
        $this->guardarSesion();
    }

    public function aumentar($varianteId)
    {
        if (!isset($this->carrito[$varianteId])) {
            return;
        }

        $variante = ProductoVariante::find($varianteId);

        if (!$variante || $variante->estado_id != 1) {
            $this->dispatch('mensaje_error', 'Producto no disponible.');
            return;
        }

        if ($this->carrito[$varianteId]['cantidad'] >= $variante->stock_actual) {
            $this->dispatch('mensaje_error', 'No puede superar el stock disponible.');
            return;
        }

        $this->carrito[$varianteId]['cantidad']++;
        $this->guardarSesion();
    }

    public function limpiar()
    {
        $this->carrito = [];

        $this->cliente_id = null;
        $this->cliente_nombre = null;
        $this->cliente_documento = null;
        $this->documento_cliente = null;

        session()->forget('carrito_venta');
        session()->forget('cliente_carrito');
    }

    public function disminuir($productoId)
    {
        if ($this->carrito[$productoId]['cantidad'] > 1) {
            $this->carrito[$productoId]['cantidad']--;
        } else {
            unset($this->carrito[$productoId]);
        }
        $this->guardarSesion();
    }

    public function getTotalProperty()
    {
        return collect($this->carrito)->sum(function ($item) {
            return $item['precio'] * $item['cantidad'];
        });
    }

    public function render()
    {
        $variantes = ProductoVariante::with(['producto.categoria', 'marca'])
        ->where('estado_id', 1)
        ->where('stock_actual', '>', 0)
        ->orderBy('descripcion')
        ->get();

        return view('livewire.venta-carrito', compact('variantes'));
    }

    public function guardarCliente()
    {
        if (trim($this->nuevo_documento) == '') {
            $this->dispatch('mensaje_error', 'Ingrese el documento.');
            return;
        }

        if (trim($this->nuevo_nombre) == '') {
            $this->dispatch('mensaje_error', 'Ingrese el nombre.');
            return;
        }
        $valor = trim($this->nuevo_documento);
        $valor = str_replace(['.', ' '], '', $valor);

        $documento = '';
        $ruc = '';

        if (str_contains($valor, '-')) {
            // Es RUC: 80012345-6
            $ruc = $valor;
            // Documento queda sin guion: 800123456
            $documento = str_replace('-', '', $valor);
        } else {
            // Es documento: 4567890
            $documento = $valor;
            // RUC queda igual al documento
            $ruc = $valor;
        }

        $cliente = Persona::create([
            'tipo_persona_id' => $this->nuevo_tipo_persona_id,
            'sexo_id' => $this->nuevo_sexo_id,
            'estado_civil' => 0,
            'documento' => $documento,
            'ruc' => $ruc,
            'nombre' => strtoupper(trim($this->nuevo_nombre)),
            'apellido' => strtoupper(trim($this->nuevo_apellido)),
            'celular' => trim($this->nuevo_celular),
            'email' => trim($this->nuevo_email),
            'fecha_nacimiento' => null,
            'direccion' => '',
            'barrio' => '',
            'estado_id' => 1,
            'user_id' => auth()->id(),
            'usuario_modificacion' => auth()->id(),
        ]);

        $this->cliente_id = $cliente->id;
        $this->cliente_nombre = $cliente->nombre;
        $this->cliente_documento = $cliente->documento;
        $this->documento_cliente = $cliente->documento;

        session()->put('cliente_carrito', [
            'cliente_id' => $this->cliente_id,
            'cliente_nombre' => $this->cliente_nombre,
            'cliente_documento' => $this->cliente_documento,
        ]);

        $this->dispatch('cerrar_modal_cliente');
        $this->dispatch('mensaje_exitoso', 'Cliente registrado.');
    }

    public function buscarCliente()
    {
        $valor = trim($this->documento_cliente);

        if ($valor == '') {

            $this->cliente_id = null;
            $this->cliente_nombre = null;
            $this->cliente_documento = null;

            session()->forget('cliente_carrito');

            return;
        }

        $cliente = Persona::where(function ($q) use ($valor) {
            $q->where('documento', $valor)
            ->orWhere('ruc', $valor);
        })
        ->where('estado_id', 1)
        ->first();

        if ($cliente) {
            $this->cliente_id = $cliente->id;
            $this->cliente_nombre = $cliente->nombre . ' ' . $cliente->apellido;
            $this->cliente_documento = $cliente->documento;

            session()->put('cliente_carrito', [
                'cliente_id' => $this->cliente_id,
                'cliente_nombre' => $this->cliente_nombre . ' ' . $cliente->apellido ,
                'cliente_documento' => $this->cliente_documento,
            ]);

            return;
        }

        $this->nuevo_documento = $valor;
        $this->nuevo_nombre = '';
        $this->nuevo_apellido = '';
        $this->nuevo_celular = '';
        $this->nuevo_sexo_id = $this->sexo->first()->id;
        $this->nuevo_tipo_persona_id = $this->tipo_persona->first()->id;
        $this->nuevo_email = '';


        $this->dispatch('abrir_modal_cliente');
    }

    public function cobrar()
    {
        if (count($this->carrito) == 0) {
            $this->dispatch('mensaje_error', 'Debe agregar productos.');
            return;
        }

        if (!$this->cliente_id) {
            $this->dispatch('mensaje_error', 'Debe seleccionar un cliente.');
            return;
        }

        $forma = FormaCobro::find($this->forma_cobro_id);
        $this->banco_ver = $forma->banco_ver ?? 0;
        $this->banco_id = 1;
        $this->monto_recibido = number_format($this->total, 0, ',', '.');

        $this->dispatch('abrir_modal_cobro');
    }

    public function confirmarCobro()
    {
        if (!$this->cliente_id) {
            $this->dispatch('mensaje_error', 'Debe seleccionar un cliente.');
            return;
        }

        if (count($this->carrito) == 0) {
            $this->dispatch('mensaje_error', 'Debe agregar productos.');
            return;
        }

        $forma = FormaCobro::find($this->forma_cobro_id);

        if (!$forma) {
            $this->dispatch('mensaje_error', 'Seleccione una forma de cobro.');
            return;
        }

        if ($forma->banco_ver == 1 && (int)$this->banco_id == 0) {
            $this->dispatch('mensaje_error', 'Debe seleccionar el banco.');
            return;
        }

        $montoRecibido = $this->limpiarMonto($this->monto_recibido);

        if ($montoRecibido < $this->total) {
            $this->dispatch('mensaje_error', 'El monto recibido no puede ser menor al total.');
            return;
        }

        $factura = null;
        DB::beginTransaction();
        try {

            $numeracion = Numeracion::where('timbrado_id', $this->timbrado->id)
            ->where('establecimiento_id', $this->establecimiento->id)
            ->where('tipo_documento_id', 1)
            ->lockForUpdate()
            ->first();

            $numeroActual = $numeracion->numero_siguiente;

            $factura = Factura::create([
                'persona_id' => $this->cliente_id,
                'timbrado_id' => $this->timbrado->id,
                'establecimiento_id' => $this->establecimiento->id,
                'registro_id' => 0,
                'factura_sucursal' => $this->establecimiento->sucursal,
                'factura_general' => $this->establecimiento->general,
                'factura_numero' => $numeroActual,
                'fecha_factura' => date('Y-m-d'),
                'tipo_documento_id' => 1,
                'tipo_transaccion_id' => $this->entidad->tipo_transaccion_id,
                'condicion_pago' => 1, // contado
                'estado_pago' => 1, // pagado
                'concepto' => 'VENTA DE PRODUCTOS',
                'plazo' => 0,
                'monto_total' => $this->total,
                'monto_abonado' => $this->total,
                'monto_devuelto' => $montoRecibido - $this->total,
                'estado_id' => 1,
                'generado_sifen' => 0,
                'observacion' => '',
                'usuario_pago' => auth()->id(),
                'user_id' => auth()->id(),
            ]);

            foreach ($this->carrito as $item) {

                $variante = ProductoVariante::with('producto')
                ->where('id', $item['variante_id'])
                ->lockForUpdate()
                ->first();

                if (!$variante) {
                    DB::rollBack();
                    $this->dispatch('mensaje_error', 'Producto no encontrado.');
                    return;
                }

                if ($variante->stock_actual < $item['cantidad']) {
                    DB::rollBack();
                    $this->dispatch('mensaje_error', 'Stock insuficiente para ' . $item['descripcion']);
                    return;
                }

                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio'];
                $precioTotal = $precio * $cantidad;

                // Si el producto tiene afecta_iva, usá ese. Si no, por defecto 10.
                // $afectaIva = $variante->producto->afecta_iva ?? 10;
                $afectaIva = 10;

                $grabado10 = 0;
                $grabado5 = 0;
                $iva10 = 0;
                $iva5 = 0;

                if ($afectaIva == 10) {
                    $grabado10 = round($precioTotal / 1.10);
                    $iva10 = $precioTotal - $grabado10;
                }

                if ($afectaIva == 5) {
                    $grabado5 = round($precioTotal / 1.05);
                    $iva5 = $precioTotal - $grabado5;
                }

                // Control final: gravada + IVA nunca debe superar ni diferir del total
                $sumaFiscal = $grabado10 + $grabado5 + $iva10 + $iva5;

                if ($sumaFiscal != $precioTotal && in_array($afectaIva, [5, 10])) {
                    $diferencia = $precioTotal - $sumaFiscal;

                    if ($afectaIva == 10) {
                        $iva10 += $diferencia;
                    }

                    if ($afectaIva == 5) {
                        $iva5 += $diferencia;
                    }
                }

                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $variante->producto_id,
                    'producto_variante_id' => $variante->id,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'precio_total' => $precioTotal,
                    'afecta_iva' => $afectaIva,
                    'grabado_10' => $grabado10,
                    'grabado_5' => $grabado5,
                    'iva_10' => $iva10,
                    'iva_5' => $iva5,
                ]);

                $variante->decrement('stock_actual', $cantidad);
            }

            FacturaCobro::create([
                'factura_id' => $factura->id,
                'forma_cobro_id' => $this->forma_cobro_id,
                'fecha' => date('Y-m-d'),
                'banco_id' => $this->banco_id,
                'numero_comprobante' => trim($this->numero_comprobante),
                'monto' => $this->total,
                'estado_id' => 1,
            ]);

            $numeracion->update([
                'numero_siguiente' => $numeroActual + 1
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('mensaje_error', $e->getMessage());
            return;
        }

        $this->limpiar();
        $this->dispatch('cerrar_modal_cobro');
        return redirect()->route('factura.show', $factura)->with('message', 'Facturado correctamente');

    }

    private function limpiarMonto($valor)
    {
        if ($valor === null || $valor === '') {
            return 0;
        }

        return (float) str_replace(',', '.', str_replace('.', '', $valor));
    }

    public function getMontosRapidosProperty()
    {
        $total = (int)$this->total;

        // Primer botón: siguiente múltiplo de 5.000
        // $m1 = ceil($total / 5000) * 5000;

        // Primer botón: siguiente múltiplo de 5.000
        $m2 = ceil($total / 20000) * 20000;

        // Segundo botón: siguiente múltiplo de 50.000
        $m3 = ceil($total / 50000) * 50000;

        // Tercer botón: siguiente múltiplo de 100.000
        $m4 = ceil($total / 100000) * 100000;

        return array_unique([$m2, $m3, $m4]);
    }

    public function seleccionarMonto($monto)
    {
        $this->monto_recibido = $monto;
    }

}
