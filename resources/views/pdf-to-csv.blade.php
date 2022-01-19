@extends('adminlte::page')

@section('title', 'PDF to CSV')

@section('content_header')
    <h1>Convert PDF to CSV</h1>
@stop

@section('content')

   @if(isset($success))
   <div class="alert alert-success success_alert" role="alert">
  		Data Processed Successfully !!
   </div>
   @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('adminlte_js')

@stop
