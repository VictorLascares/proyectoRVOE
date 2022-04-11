@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
<div class=" mt-4 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card shadow p-3 bg-body rounded">
            <div class="card-header">
                <h1 class="card-title text-center text-uppercase">Agregar Nueva Institución</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating mb-3">
                        <input required type="text" class="form-control" id="institutionName" placeholder="Nombre de la Institución" name="nombre">
                        <label for="institutionName">Nombre de la Institución</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input required type="text" class="form-control" id="nameDirectorInstitution" placeholder="Nombre del Director" name="director">
                        <label for="nameDirectorInstitution">Nombre del Director</label>
                    </div>
                    <div class="input-group form-group mb-3">
                        <input type="file" class="form-control" id="institutionLogo" name="logotipo">
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-lg btn-success">Agregar Institución</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer')
    <x-footer />
@endsection