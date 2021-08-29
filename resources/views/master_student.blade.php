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
                                    <div class="form-group row custom-form">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> NIS </label>
                                            <input class="form-control" type="text" name="nis" id="nis" value="{{old('nis')}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Peserta </label>
                                            <input class="form-control" type="text" name="name_student" id="name_student" value="{{old('name_student')}}">
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
                        <h5> Data Peserta </h5>

                        <table id="student"></table>
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
        let nis = $('#nis');
        let tblStudent = $('#student');
        let nameStudent = $('#name_student');

        $(document).ready(function() {
            readData();
        });

        function edit(index) {
            data = tblStudent.bootstrapTable('getData');
            form.attr('action', $('#edit_'+index).attr('data-link'));

            nis.val(data[index].nis);
            nameStudent.val(data[index].name_student);
        }

        function remove(index) {
            data = tblStudent.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus data kelas <b>'+data[index].name_student+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('master/student/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function readData() {
            tblStudent.bootstrapTable({
                url: "{{url('/master/student/ajaxRead')}}",
                method: 'get',
                locale: 'en-US',
                classes: 'table table-bordered table-hover',
                toolbar: '#toolbar',
                //showRefresh: true,
                search: true,
                pagination: true,
                sidePagination: 'server',
                pageSize: 3,
                sortable: true,
                //idField: 'NRP',
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            let str = '';
                            str += `<a type="button" id="edit_${index}" data-link="{{url('master/student/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" id="remove_${index}" data-link="{{url('master/student/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'name_student',
                        title: 'Nama Peserta',
                    },
                    {
                        field: 'nis',
                        title: 'NIS',
                    },
                ]
            });
        }
    </script>
@endsection
