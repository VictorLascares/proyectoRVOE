@extends('layouts.app')
@section('titulo')
  Administrar Usuarios
@endsection
@section('contenido')
  @auth
    @if (Auth::user()->tipoUsuario = 'administrador')
      <div class="container py-4">
        <div class="d-md-flex justify-content-between align-items-center mb-4">
          <div class="flex justify-end items-center gap-2">
            <form action="{{ route('users.create') }}" method="GET" enctype="multipart/form-data">
              @csrf
              <button type="submit" class="rounded-lg text-white bg-green-900 hover:bg-green-700 p-3">Nuevo Usuario</button>
            </form>
          </div>
        </div>

        <div class="flex flex-col">
          <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
              <div class="overflow-hidden rounded-lg">
                <table class="min-w-full">
                  <thead class="border-b bg-green-900">
                    <tr>
                      <th class="text-sm font-bold text-white px-6 py-4 text-left">#</th>
                      <th class="text-sm font-bold text-white px-6 py-4 text-left">Nombre</th>
                      <th class="text-sm font-bold text-white px-6 py-4 text-left">Correo Electronico</th>
                      <th class="text-sm font-bold text-white px-6 py-4 text-left">Telefono</th>
                      <th class="text-sm font-bold text-white px-6 py-4 text-left">Tipo de Usuario</th>
                      <th class="text-sm font-bold text-white px-6 py-4 text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                      <tr class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap">
                          {{ $loop->iteration }}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $user->telefono }}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ $user->tipoUsuario }}
                        </td>
                        <td>
                          <div class="flex justify-center items-center gap-1">
                            <a href="{{ route('users.edit', $user->id) }}"
                              class="text-sky-600 hover:text-sky-900 font-bold">
                              [Editar]
                            </a>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                              @csrf
                              @method('DELETE')
                              <input type="submit" value="[Eliminar]"
                                class="cursor-pointer font-bold text-red-600 hover:text-red-900">
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="my-10">{{ $users->links('pagination::tailwind') }}</div>
      </div>
    @endif
  @endauth
@endsection
