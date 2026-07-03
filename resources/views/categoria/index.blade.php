@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
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
            padding: 20px;
        }

        .categoria-body h4 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .categoria-info {
            font-size: 15px;
            color: rgba(255,255,255,.90);
        }

        .categoria-info div {
            margin-bottom: 9px;
        }

        .categoria-info i {
            width: 23px;
        }
    </style>

@endsection

@section('content')

    @livewire('categoria.index-categoria')

@endsection


@section('js')
@endsection
