@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')

<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-lg-flex justify-content-between align-items-center">
                        <h2 class="card-title text-center">
                            Usuarios
                        </h2>
                                <form>
                                    @csrf
                                    <a href="#" class="btn btn-success">Nuevo Usuario<a/>
                                </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Nombres</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Tipo de Usuario</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                        <tr>
                                            <th scope="row"></th>
                                            <td>s</td>
                                            <td>s</td>
                                            <td>s</td>
                                            <td>s</td>
                                            <td>s</td>
                                            <td class="d-flex justify-content-center">
                                                        <a href="#" class="btn btn-warning me-1">
                                                            <i class="bi bi-key"></i>
                                                        </a>
                                                        <form method="POST" action="">
                                                            <a href="#" class="btn btn-primary">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                        </form>
                                            </td>
                                        </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <x-footer />
@endsection
