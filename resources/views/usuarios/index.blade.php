@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
@auth
    @if (Auth::user()->tipoUsuario = 'administrador')
        <div class="container-fluid py-4">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-md-flex justify-content-between align-items-center">
                                <h2 class="card-title text-center">
                                    Administrar usuarios
                                </h2>
                                <div class="d-flex justify-content-between align-items-center">
                                    <form action="">
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Eliminar<button/>
                                    </form>
                                    <form action="{{ route('users.create')}}" method="GET" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_token" id="csrf-token" value="" />
                                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Usuario<button/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col"><div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                            </div></th>
                                            <th scope="col">Nombres</th>
                                            <th scope="col">Apellidos</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Telefono</th>
                                            <th scope="col">Tipo de Usuario</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($users as $user)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-group form-check">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                    </div>
                                                </th>
                                                <td>{{ $user->nombres }}</td>
                                                <td>{{ $user->apellidos }}</td>
                                                <td>{{ $user->correo }}</td>
                                                <td>{{ $user->telefono }}</td>
                                                <td>{{ $user->tipoUsuario}}</td>
                                                <td class="d-flex justify-content-center gap-1">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="bi bi-key"></i>
                                                    </button>
                                                    <a href="#" class="btn btn-success">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('users.destroy', $user->id)}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li class="page-item"><a class="page-link  text-success" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link  text-success" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link  text-success" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link  text-success" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link  text-success" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth

@endsection
@section('footer')
    <x-footer />
@endsection



