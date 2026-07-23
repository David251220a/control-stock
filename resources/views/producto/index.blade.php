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
    </style>

@endsection

@section('content')

    @livewire('producto.index-producto')

@endsection


@section('js')
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/producto.js') }}"></script>
@endsection
