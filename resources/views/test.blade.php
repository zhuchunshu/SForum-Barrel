@extends('app')

@section('content')

    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h3 class="card-title">上传测试</h3></div>
                <div class="card-body">
                    <form action="/test/5" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <input class="form-control" type="file" name="app">
                        </div>
                        <x-csrf/>
                        <button class="btn">上传</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection