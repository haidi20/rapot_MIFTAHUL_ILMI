@extends('layouts.appLayout')

@section('style')
    <style>
        .custom-form {
            margin-top: 50px;
        }
    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5>Masukkan Data Murid</h5> --}}

                        <form action="{{ $action }}" method="post" id="form" >
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    {{-- <div class="form-group row custom-form">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama  </label>
                                            <input class="form-control" type="text" name="name_quiz" id="name_quiz" value="{{old('name_quiz')}}" placeholder="contoh: Kuis 1">
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Tingkatan </label>
                                            <input class="form-control" type="text" name="description" id="description" value="{{old('description')}}" placeholder="contoh: MUSTAWA AWWAL">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <button type="submit" class="btn btn-md btn-success " id="btnSave">Kirim</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                {!! session()->get('message') !!}
                <div class="card">
                    <div class="card-body">
                        <h5> Data Tingkatan </h5>

                        <table id="master_quiz"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let data = [];
        let form = $('#form');
        let btnSave = $('#btnSave');
        let masterQuiz = $('#master_quiz');
        let nameClassRoom = $('#name_quiz');
        let description = $('#description');

        $(document).ready(function() {
            readData();
        });

        function edit(index) {
            data = masterQuiz.bootstrapTable('getData');
            form.attr('action', $('#edit_'+index).attr('data-link'));

            nameClassRoom.val(data[index].name_quiz);
            description.val(data[index].description);
        }

        function remove(index) {
            data = masterQuiz.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus data tingkatan <b>'+data[index].description+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('master/quiz/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function readData() {
            masterQuiz.bootstrapTable({
                url: "{{url('/master/quiz/ajaxRead')}}",
                method: 'get',
                locale: 'en-US',
                classes: 'table table-bordered table-hover',
                toolbar: '#toolbar',
                //showRefresh: true,
                search: true,
                pagination: true,
                sidePagination: 'server',
                pageSize: 10,
                sortable: true,
                //idField: 'NRP',
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            let str = '';
                            str += `<a type="button" id="edit_${index}" data-link="{{url('master/quiz/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" id="remove_${index}" data-link="{{url('master/quiz/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    // {
                    //     field: 'name_quiz',
                    //     title: 'Nama Kuis',
                    // },
                    {
                        field: 'description',
                        title: 'Nama Tingkatan',
                    },
                ]
            });
        }
    </script>
@endsection
