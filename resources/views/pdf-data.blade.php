@extends('adminlte::page')

@section('title', 'PDF to CSV')

@section('content_header')
    <h1>Convert PDF to Data using Py</h1>
    <hr>
@stop
@section('content')
   <!-- <div class="col-md-12">
   		<div class="col-md-6">
   			<label for="pdf_type">Select Pdf Type</label>
   			<select name="pdf_type" class="form-control">
   				<option value="">Select type</option>
   				<option value="railways">Railways</option>
   				<option value="gem">Gem</option>
   			</select>
   		</div>
   		<br>
   		<div class="col-md-6" >
   			<input type="submit" class="btn btn-primary" name="submit" value="submit">
   		</div>
   </div> -->
   @if(isset($success))
   <div class="alert alert-success success_alert" role="alert">
        Data Processed Successfully !!
   </div>
   @endif
   <div class="card">
        <div class="card-body">
            <p>Please copy below text and paste it to CMD</p>
           py {{$path}}
            <br>
        </div>
   </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('adminlte_js')

@stop
