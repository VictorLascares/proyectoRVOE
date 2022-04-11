@extends('layouts.layout')
@section('header')
    <x-bar  />
    <x-navbar />
@endsection
@section('main-content')
<div class="container-fluid py-5">
    <div class="row pt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="card-title text-uppercase">Instituciones</h1>
                    <a href="{{route('institutions.create')}}" class="btn btn-success">Nueva Institución</a>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach ($institutions as $institution)
                            <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="text-center  card-title">{{$institution->nombre}}<h3>
                                    </div>
                                    <div class="card-body">
                                        <img src="{{ asset("img/institutions/".$institution->logotipo) }}" class="card-img-top" alt="Logo de la Institución">
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex gap-2">
                                                <form action="">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
                                                <form action="">
                                                    <button class="btn btn-success">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <x-footer />
@endsection
