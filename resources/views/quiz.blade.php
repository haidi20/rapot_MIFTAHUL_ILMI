@extends('layouts.appLayout')

@section('style')
    <link rel="stylesheet" href="{{asset('adminty/files/bower_components/select2/css/select2.min.css')}}"/>
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/icon/font-awesome/css/font-awesome.min.css')}}"> --}}
    <style>
        .custom-form {
            margin-top: 50px;
        }
        .form-select-student {
            width: 85%;
            display: inline-block;
        }
        .btn-delete-student {
            margin-left: 5px;
            display: inline-block;
        }
        .btn-filter {
            margin-top: 30px;
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
                                            <label class="block"> Peserta </label>
                                            <select name="students[]" id="first_student" class="form-control .select2 form-student"></select>
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
                <div class="row">
                    {!! session()->get('message') !!}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-3">
                                        <label class="block"> Kelas </label>
                                        <select name="class_room_id" id="filter_class" class="form-control form-control-inverse">
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($classRoom as $index => $item )
                                                <option
                                                    value="{{$item->id}}">
                                                    {{$item->name_class_room}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <label class="block"> Kuis </label>
                                        <select name="class_room_id" id="filter_quiz" class="form-control form-control-inverse"></select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <button type="button" class="btn btn-sm btn-success btn-filter" onclick="sendFilter()">Pilih</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <h5> Data Kuis </h5>

                                <table id="quiz"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/select2/js/select2.full.min.js')}}"></script>
    <script>
        var state = {
            countFormStudent: 1,
            dataStudent: [],
            quiz_id: '',
        }

        var data = [];
        var form = $('#form');
        var btnSave = $('#btnSave');
        var quizTable = $('#quiz');
        var filterQuiz = $('#filter_quiz');
        var nameClassRoom = $('#name_quiz');
        var firstStudent = $('#first_student');
        var multiStudent = $('.multi-student');

        $(document).ready(function() {
            readData();
            loadDataStudent();
            selectFilterClassRoom();
            selectFilterQuiz();

            $('.select2').select2();
        });

        function edit(index) {
            data = quizTable.bootstrapTable('getData');
            form.attr('action', $('#edit_'+index).attr('data-link'));

            nameClassRoom.val(data[index].name_quiz);
        }

        function remove(index) {
            data = quizTable.bootstrapTable('getData');

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

        function selectFilterClassRoom() {
            $('#filter_class').on('click', function(){
                var classRoomId = $(this).val();
                // console.log(classRoomId);
                $.ajax({
                    url: "{{url('quiz/ajaxReadTypeahead')}}?search="+classRoomId,
                    dataType: "JSON",
                    contentType: "application/json",
                    type: "GET",
                    success: function (result) {
                        filterQuiz.empty();

                        var optionNewFormStudent = '';
                        state.dataStudent = result.data;

                        optionNewFormStudent += '<option> Pilih Kuis </option>';
                        if (result.data[0]) {
                            $.each(result.data, function(index, item) {
                                optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_quiz+' </option>';
                            });
                        }
                        filterQuiz.prepend(optionNewFormStudent);
                    },
                    error: function (error) {
                        // Swal.fire({
                        //     type: 'error',
                        //     title: 'Oops... Error...',
                        //     html: "Maaf, gagal mendapatkan data peserta",
                        // });

                        console.log(error);
                    }
                });
            });
        }

        function selectFilterQuiz() {
            $('#filter_quiz').on('click', function() {
                state.quiz_id = $(this).val();

                console.log(state.quiz_id);
            });
        }

        function sendFilter() {
            console.log(state.quiz_id);
            // quizTable.bootstrapTable('refresh');

            quizTable.bootstrapTable('refresh');
            readData();
        }

        function readData() {
            console.log('load read data');
            console.log('quiz id = ' + state.quiz_id);

            quizTable.bootstrapTable({
                url: "{{url('/absen/ajaxRead')}}",
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
                queryParams: function(params) {
                    params.quiz_id = state.quiz_id;

                    return params;
                },
                //idField: 'NRP',
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            var str = '';
                            // str += `<a type="button" id="edit_${index}" data-link="{{url('absen/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" id="remove_${index}" data-link="{{url('absen/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'name_student',
                        title: 'Nama Peserta',
                    },
                ]
            });
        }

        function loadDataStudent() {
            $.ajax({
                url: "{{url('master/student/ajaxReadTypeahead')}}",
                dataType: "JSON",
                contentType: "application/json",
                type: "GET",
                success: function (result) {
                    if (result.data) {
                        var optionNewFormStudent = '';
                        state.dataStudent = result.data;

                        optionNewFormStudent += '<option> Pilih Peserta </option>';
                        $.each(state.dataStudent, function(index, item) {
                            optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_student+' </option>';
                        });

                        firstStudent.prepend(optionNewFormStudent);
                    }
                },
                error: function (error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Error...',
                        html: "Maaf, gagal mendapatkan data peserta",
                    });

                    console.log(error);
                }
            });
        }

        function addFormStudent() {
            var newFormStudent = '';
            var optionNewFormStudent = '';
            var countFormStudent = state.countFormStudent + 1;
            state.countFormStudent = countFormStudent;

            optionNewFormStudent += '<option> Pilih Peserta </option>';
            $.each(state.dataStudent, function(index, item) {
                optionNewFormStudent += '<option value="'+item.id+'"> '+item.name_student+' </option>';
            });

            newFormStudent += '<div class="form-group row multi-student" id="form_student_'+countFormStudent+'">';
                newFormStudent +='<div class="col-sm-12 col-md-12">';
                    newFormStudent +='<label class="block"> Pilih Peserta </label>';
                    newFormStudent +='<select name="students[]" class="form-control form-control-inverse form-select-student form-student">';
                        newFormStudent += optionNewFormStudent;
                    newFormStudent +='</select>';
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
