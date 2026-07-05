<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
       $estado = $request->has('estado') ? (int) $request->estado : 9;
        $fecha_desde = $request->fecha_desde
        ? Carbon::parse($request->fecha_desde)->toDateString()
        : now()->toDateString();

        $fecha_hasta = $request->fecha_hasta
        ? Carbon::parse($request->fecha_hasta)->toDateString()
        : now()->toDateString();

        $data = Factura::query()
        ->whereBetween('fecha_factura', [$fecha_desde, $fecha_hasta])
        ->when($estado != 9, fn($q) => $q->where('estado_pago', $estado))
        ->orderByDesc('factura_sucursal')
        ->orderByDesc('factura_general')
        ->orderByDesc('factura_numero')
        ->paginate(50);
        return view('factura.index', compact('data', 'fecha_desde', 'fecha_hasta', 'estado'));
    }

    public function show(Factura $factura)
    {
        $entidad = Entidad::find(1);
        return view('factura.show', compact('factura','entidad'));
    }

    public function factura(Factura $factura)
    {
        // $textoQr = $factura->sifen->link_qr;
        // $result = Builder::create()
        // ->writer(new PngWriter())
        // ->data($textoQr)
        // ->size(750)
        // ->margin(25)
        // ->build();

        // $qrBase64 = base64_encode($result->getString());

        $pdf = Pdf::loadView('factura.factura', [
            // 'qrBase64' => $qrBase64,
            'factura' => $factura,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('factura.pdf');
    }
}
