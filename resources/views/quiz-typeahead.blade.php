@extends('layouts.appLayout')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('typeahead/dist/typeahead-style.css')}}">
    <style>
        .custom-form {
            margin-top: 50px;
        }
        .form-input-student {
            width: 85%;
            display: inline-block;
        }
        .btn-delete-student {
            margin-left: 5px;
            display: inline-block;
        }

        /* .tt-input {
            display:none;
        } */
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
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <a href="#" type="button" class="btn btn-sm btn-info" onclick="addFormStudent()">Tambah Peserta</a>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Kelas </label>
                                            <select name="class_room_id" class="form-control form-control-inverse">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($classRoom as $index => $item )
                                                    <option
                                                        value="{{$item->id}}">
                                                        {{$item->name_class_room}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Kuis </label>
                                            <input class="form-control" type="text" name="name_quiz" id="name_quiz" value="{{old('name_quiz')}}" placeholder="contoh: MUSTAWA AWWAL">
                                        </div>
                                    </div>
                                    <div class="form-group row multi-student">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Peserta </label>
                                            <input class="form-control form-student" type="text" name="name_student" id="name_student" >
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
                        <h5> Data Kuis </h5>

                        <table id="quiz"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('typeahead/dist/typeahead.bundle.js')}}"></script>
    <script>
        var state = {
            countFormStudent: 1,
            dataStudent: [],
        }

        var data = [];
        var form = $('#form');
        var btnSave = $('#btnSave');
        var idClassRoom = $('#quiz');
        var nameClassRoom = $('#name_quiz');
        var firstStudent = $('#first_student');
        var multiStudent = $('.multi-student');

        $(document).ready(function() {
            readData();
            setAutoCompleteStudent();
        });

        function edit(index) {
            data = idClassRoom.bootstrapTable('getData');
            form.attr('action', $('#edit_'+index).attr('data-link'));

            nameClassRoom.val(data[index].name_quiz);
        }

        function remove(index) {
            data = idClassRoom.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus data kelas <b>'+data[index].name_quiz+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('quiz/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function readData() {
            idClassRoom.bootstrapTable({
                url: "{{url('/quiz/ajaxRead')}}",
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
                            var str = '';
                            str += `<a type="button" id="edit_${index}" data-link="{{url('quiz/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" id="remove_${index}" data-link="{{url('quiz/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'name_quiz',
                        title: 'Nama kuis',
                    },
                ]
            });
        }

        function setAutoCompleteStudent(index) {
            var indexFormStudent = index ? index : 1;

            // Initializing the typeahead
            $('.form-student').typeahead({
                hint: true,
                highlight: true, /* Enable substring highlighting */
                minLength: 1, /* Specify minimum characters required for showing result */
            },
            {
                name: 'student',
                source:  new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: "{{url('/master/student/ajaxReadTypeahead')}}",
                        replace: function (url, query) {
                            return url + '?search=' + query;
                        },
                        transform: function(data) {
                            items = [];
                            map = {};
                            $.each(data['data'], function (i, item) {
                                var nameStudent = item['name_student'];

                                console.log(nameStudent);

                                //console.log(item['EQUIP_NO']);
                                map[nameStudent] = { name: nameStudent };
                                items.push(nameStudent);
                            });
                            return items;
                        }
                    }
                }),
            });
        }

        function addFormStudent() {
            setAutoCompleteStudent();
            var newFormStudent = '';
            var countFormStudent = state.countFormStudent + 1;
            state.countFormStudent = countFormStudent;

            newFormStudent += '<div class="form-group row multi-student" id="form_student_'+countFormStudent+'">';
                newFormStudent +='<div class="col-sm-12 col-md-12">';
                    newFormStudent +='<label class="block"> Nama Peserta </label>';
                    newFormStudent +='<input type="text" name="student[]" class="form-control form-input-student form-student" id="input_student_'+countFormStudent+'">';
                    newFormStudent +='<button type="submit" class="btn btn-sm btn-danger btn-delete-student" onclick="removeFormStudent('+countFormStudent+')">';
                        newFormStudent += '<i class="fas fa-close"></i>';
                    newFormStudent +='</button>';
                newFormStudent +='</div>';
            newFormStudent +='</div>';

            multiStudent.after(newFormStudent);


        }

        function removeFormStudent(index) {
            var countFormStudent = state.countFormStudent - 1;
            state.countFormStudent = countFormStudent;

            $('#form_student_'+index).remove();
        }
    </script>
@endsection
