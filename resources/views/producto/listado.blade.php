@extends('layouts.admin')

@section('styles')
    <link href="{{asset('plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
    <style>
        .categoria-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg,#4f46e5,#7c3aed);
            color: #fff;
            box-shadow: 0 12px 30px rgba(0,0,0,.22);
            transition: .25s;
            position: relative;
            cursor: pointer;
        }

        .categoria-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 42px rgba(0,0,0,.30);
        }

        .categoria-img {
            height: 170px;
            overflow: hidden;
            position: relative;
            background: rgba(255,255,255,.08);
        }

        .categoria-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .92;
            transition: .35s;
        }

        .categoria-card:hover .categoria-img img {
            transform: scale(1.08);
        }

        .categoria-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 2;
            padding: 7px 14px;
            border-radius: 25px;
            background: linear-gradient(90deg,#ec4899,#a855f7);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
        }

        .btn-editar-cat {
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 3;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            background: rgba(255,255,255,.90);
            color: #4f46e5;
            box-shadow: 0 6px 14px rgba(0,0,0,.18);
        }

        .categoria-body {
            padding: 24px 20px 20px 20px;
        }

        .categoria-card.variante-card .categoria-body h4 {
            padding-left: 0;
            padding-right: 0;
            width: 100%;
            font-size: 22px;
            line-height: 1.15;
            word-break: normal;
            overflow-wrap: break-word;
        }

        .categoria-body h4 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 20px;
            padding-left: 38px;
            padding-right: 105px;
            font-size: 22px;
            line-height: 1.2;
            word-break: break-word;
        }

        .categoria-info div{
            flex:1;
            margin:0 4px;
        }

        .categoria-info div{

            background:rgba(255,255,255,.12);

            padding:8px 15px;

            border-radius:12px;

            min-width:95px;

            text-align:center;

            transition:.25s;
        }

        .categoria-card:hover .categoria-info div{

            background:rgba(255,255,255,.20);

        }

        .categoria-info i{

            font-size:18px;

            margin-right:6px;

        }

        .btn-editar-cat {
            top: 18px;
            left: 16px;
        }

        /* Stock disponible */
        .categoria-card.variante-card.stock-disponible {
            background: linear-gradient(135deg, #047857, #10b981);
            border: 2px solid #34d399;
        }

        /* Stock igual o inferior al mínimo */
        .categoria-card.variante-card.stock-bajo {
            background: linear-gradient(135deg, #b45309, #f59e0b);
            border: 2px solid #fbbf24;
        }

        /* Stock agotado */
        .categoria-card.variante-card.stock-agotado {
            background: linear-gradient(135deg, #991b1b, #dc2626);
            border: 2px solid #f87171;
        }

        /* Badge correspondiente al estado del stock */
        .variante-card.stock-disponible .stock-badge {
            background: #065f46;
        }

        .variante-card.stock-bajo .stock-badge {
            background: #92400e;
        }

        .variante-card.stock-agotado .stock-badge {
            background: #7f1d1d;
            animation: stock-agotado-alerta 1.8s infinite;
        }

        @keyframes stock-agotado-alerta {
            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.55);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(248, 113, 113, 0);
            }
        }

        .stock-filter-group {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .stock-filter-btn {
            min-height: 54px;
            padding: 10px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 13px;
            background: #f1f2f6;
            color: #1f2937;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .stock-filter-btn:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .stock-filter-btn.active {
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.14);
        }

        .stock-filter-btn.active.estado-todos {
            background: linear-gradient(135deg, #4f46e5, #9333ea);
        }

        .stock-filter-btn.active.estado-bajo {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        .stock-filter-btn.active.estado-agotado {
            background: linear-gradient(135deg, #b91c1c, #ef4444);
        }

        .btn-aplicar-filtros {
            min-width: 150px;
            padding: 11px 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            border: none;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 7px 16px rgba(22, 163, 74, 0.2);
        }

        .btn-aplicar-filtros:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #15803d, #166534);
            transform: translateY(-2px);
        }

        .btn-limpiar-filtros {
            min-width: 110px;
            padding: 11px 20px;
            border-radius: 12px;
            background: #4162e4;
            border: 1px solid #4162e4;
            color: #eff0f1;
            font-weight: 600;
        }

        /* .btn-limpiar-filtros:hover {
            background: #e5e7eb;
            color: #111827;
        } */

        @media (max-width: 767px) {
            .stock-filter-group {
                grid-template-columns: 1fr;
            }

            .stock-filter-btn {
                min-height: 48px;
            }

            .btn-aplicar-filtros,
            .btn-limpiar-filtros {
                width: 100%;
                margin-right: 0 !important;
            }
        }

        .btn-aplicar-filtros,
        .btn-aplicar-filtros:hover,
        .btn-aplicar-filtros:focus {
            color: #ffffff !important;
        }

        .stock-filter-group .btn {
            margin: 0 !important;
            min-height: 54px;
            border-radius: 12px;
        }

        /* Todas las columnas trabajan con la misma altura */
        .producto-variante-col {
            display: flex;
        }

        /* La tarjeta ocupa toda la altura disponible */
        .producto-variante-col .variante-card {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
        }

        /* El cuerpo completa el espacio restante */
        .producto-variante-col .categoria-body {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        /* Título limitado a dos líneas */
        .producto-variante-col
        .variante-card
        .categoria-body h4 {
            display: -webkit-box;
            height: 52px;
            min-height: 52px;
            max-height: 52px;
            margin-bottom: 16px;
            overflow: hidden;
            line-height: 26px;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        /* Stock mínimo y precio siempre quedan abajo */
        .producto-variante-col .categoria-info {
            margin-top: auto !important;
        }

        .variante-acciones {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 4;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-variante-accion {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 5px 13px rgba(0, 0, 0, 0.18);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-variante-accion:hover {
            transform: translateY(-2px) scale(1.05);
        }

        .accion-editar {
            color: #6246ea;
        }

        .accion-ver {
            color: #1687d9;
        }

        .accion-ajustar {
            color: #d97706;
        }

        .signo-ajuste {
            font-size: 21px;
            font-weight: 800;
            line-height: 1;
        }

        .variante-card .variante-acciones {
            position: absolute !important;
            top: 16px !important;
            left: 16px !important;
            z-index: 10 !important;

            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;

            width: auto !important;
            max-width: none !important;
            gap: 7px !important;
        }

        .variante-card
        .variante-acciones
        .btn-variante-accion {
            position: relative !important;
            top: auto !important;
            right: auto !important;
            bottom: auto !important;
            left: auto !important;

            display: inline-flex !important;
            flex: 0 0 34px !important;
            align-items: center !important;
            justify-content: center !important;

            width: 34px !important;
            min-width: 34px !important;
            height: 34px !important;
            margin: 0 !important;
            padding: 0 !important;

            float: none !important;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 13px rgba(0, 0, 0, 0.18);
        }

        .variante-card
        .variante-acciones
        .btn-variante-accion:hover {
            transform: translateY(-2px);
        }

        .accion-editar {
            color: #6246ea;
        }

        .accion-ver {
            color: #1687d9;
        }

        .accion-ajustar {
            color: #d97706;
        }

        .signo-ajuste {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
        }

    </style>

@endsection

@section('content')

    @livewire('producto.listado-producto')

@endsection


@section('js')
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/listado.js') }}"></script>
@endsection
