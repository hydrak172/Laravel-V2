@extends('admin.layout.master')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <h1>Create Product Category</h1>
    </div>
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Create Product Category</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.product_category.save') }}">
                {{-- Loi 419 la thieu @csrf --}}
                @csrf
                {{-- Loi 419 la thieu @csrf --}}
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                </div>
                <div>
                    @error('name')
                        <span style="color:red">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Slug</label>
                  <input type="text" class="form-control" id="slug" placeholder="Slug" name="slug">
                </div>
                <div>
                    @error('slug')
                        <span style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                  <label for="status">Status</label>
                    <select name="status" id="status" class="form-select form control">
                      <option value="">-----Please Select-----</option>
                      <option value="1">Show</option>
                      <option value="0">Hide</option>
                    </select>
                    <div>
                        @error('status')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                    </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary" name="create">Create</button>
              </div>
            </form>
          </div>
      </div>
  </div>
<!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection

@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#name').on('keyup',function(){
                let name = $(this).val();
                $.ajax({
                    method: 'POST', //methos of form
                    url: "{{ route('admin.product_category.slug') }}",
                    data: {
                        name: name,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res){
                        // console.log(res);
                        $('#slug').val(res.slug);
                    },
                    error: function(res){

                    }
                });
                //alert(value);
            });
        });
    </script>
@endsection
