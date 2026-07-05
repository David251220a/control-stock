@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div  class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h3 class="mb-0">Facturas</h3>
                    </div>
                </div>

                @include('varios.mensaje')

                <form action="{{ route('factura.index') }}" method="GET">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-row mb-2">
                            <div class="form-group col-md-3">
                                <label for="fecha_desde">Fecha Desde</label>
                                <input type="date" name="fecha_desde" value="{{ $fecha_desde }}" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fecha_hasta">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" value="{{ $fecha_hasta }}" class="form-control">
                            </div>


                            <div class="form-group col-md-3">
                                <label for="estado">Estado</label>
                                <div class="input-group">
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="9" {{ $estado == 9 ? 'selected' : '' }}>TODOS</option>
                                        <option value="0" {{ $estado == 0 ? 'selected' : '' }}>PENDIENTE</option>
                                        <option value="1" {{ $estado == 1 ? 'selected' : '' }}>PAGADO</option>
                                        <option value="2" {{ $estado == 2 ? 'selected' : '' }}>ANULADO</option>
                                    </select>

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            🔍 Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row mt-1">
                    <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                                <thead>
                                    <tr>
                                        <th class="">Factura</th>
                                        <th class="">Fecha</th>
                                        <th class="">Tipo Factura</th>
                                        <th class="">Beneficiario</th>
                                        <th class="">Concepto</th>
                                        <th>Total</th>
                                        <th class="">Estado</th>
                                        <th class="">Estado Sifen</th>
                                        <th class="">Link Sifen</th>
                                        <th class="">Sifen Evento</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td class="">
                                                {{ $item->factura_sucursal }}-{{ $item->factura_general }}-{{ str_pad($item->factura_numero, 7, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="">
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                {{ $item->tipo_documento->descripcion ?? '' }}
                                            </td>
                                            <td class="text-right">
                                                {{$item->persona->nombre}} {{$item->persona->apellido}}
                                            </td>
                                            <td class="">
                                                {{$item->concepto}}
                                            </td>
                                            <td class="text-right">
                                                {{number_format($item->monto_total, 0, ',', '.')}}
                                            </td>
                                            <td class="text-center">
                                                @if($item->estado_pago == 0)
                                                    <span class="badge badge-warning">PENDIENTE</span>
                                                @endif

                                                @if($item->estado_pago == 1)
                                                    <span class="badge badge-success">APROBADO</span>
                                                @endif

                                                @if($item->estado_pago == 2)
                                                    <span class="badge badge-danger">ANULADO</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->sifen?->sifen_estado == 'APROBADO')
                                                    <span class="badge badge-success">APROBADO</span>
                                                @elseif($item->sifen?->sifen_estado == 'RECHAZADO')
                                                    <span class="badge badge-danger">RECHAZADO</span>
                                                @else
                                                    <span class="badge badge-warning">PENDIENTE</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($item->sifen?->link_qr)
                                                    <a href="{{ $item->sifen->link_qr }}" class="btn btn-info" target="_blank">
                                                        Sifen
                                                    </a>
                                                @endif
                                            </td>

                                            <td>
                                                {{$item->sifen?->sifen_evento_estado}}
                                            </td>

                                            <td class="text-center">
                                                {{-- @can('factura.show') --}}
                                                    <a href="{{route('factura.show', $item)}}" class="mr-3" title="Imprimir Factura" target="_blank">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                {{-- @endcan --}}

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>
                                        <td colspan="11"></td>
                                    </th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
@endsection
