@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-fluid pb-5 mb-4">
    <div class="row pt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="card-title text-uppercase">Instituciones</h1>
            <a href="{{ route('institutions.create') }}" class="btn btn-success">Nueva Institución</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-column justify-content-center align-items-center gap-2">
              @foreach ($institutions as $institution)
                <a class="text-decoration-none text-dark institution" href="">
                  <div class="card p-2" style="max-width: 600px;">
                    <div class="row g-0">
                      <div class="col-md-4">
                        <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" class="img-fluid rounded-start" alt="Logo de la Institución">
                      </div>
                      <div class="col-md-8">
                        <div class="card-body">
                          <h3 class="text-center card-title">{{ $institution->nombre }}<h3>
                          <p class="fs-5 text-center">Director(a): {{$institution->director}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="institution__overlay rounded"></div>
                </a>
              @endforeach
            </div>
          </div>
          <div class="card-footer d-flex justify-content-center">
            <nav aria-label="Page navigation example">
              <ul class="pagination m-0">
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
@endsection
@section('footer')
  <x-footer />
@endsection
