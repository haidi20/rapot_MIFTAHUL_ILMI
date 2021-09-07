@extends('layouts.appLayout')

@section('style')
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset('adminty/files/bower_components/select2/css/select2.min.css')}}"/>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/pages/advance-elements/css/bootstrap-datetimepicker.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/icon/font-awesome/css/font-awesome.min.css')}}"> --}}
    <style>
        .btn-filter {
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-md-12">
                {!! session()->get('message') !!}
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-3">
                                <label class="block"> Bulan </label>
                                <input type="month" name="filter_datetime" id="filter_datetime" class="form-control">
                            </div>
                            {{-- <div class="col-sm-12 col-md-3">
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
                            <div class="col-sm-12 col-md-3">
                                <label class="block"> Tingkatan </label>
                                <select name="filter_quiz_id" id="filter_quiz" class="form-control form-control-inverse">
                                    <option value="">Pilih Tingkatan</option>
                                    @foreach ($quiz as $index => $item )
                                        <option
                                            value="{{$item->id}}">
                                            {{$item->description}}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-sm-12 col-md-3">
                                <button type="button" class="btn btn-sm btn-success btn-filter" onclick="sendFilter()">Pilih</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                {!! session()->get('message') !!}
                <div class="card">
                    <div class="card-body">
                        <h5> Daftar Peserta Aktif </h5>

                        <table id="student_active"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script>
        let state = {
            quiz_id: '',
            class_room_id: '',
            datetime: moment().format('YYYY-MM'),
        }

        let data = [];
        let tblStudentActive = $('#student_active');
        let filterQuiz = $('#filter_quiz');
        let filterClass = $('#filter_class');
        let filterDatetime = $('#filter_datetime');

        $(document).ready(function() {
            readData();

            filterDatetime.val(state.datetime);
        });

        function remove(index) {
            data = tblStudentActive.bootstrapTable('getData');

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
                    location.href = `{{url('student-active/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function sendFilter() {
            state.quiz_id = filterQuiz.val();
            state.class_room_id = filterClass.val();
            state.datetime = filterDatetime.val();
            // state.dataAbsen = [];

            tblStudentActive.bootstrapTable('refresh');
        }

        function readData() {
            tblStudentActive.bootstrapTable({
                url: "{{url('student-active/ajaxRead')}}",
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
                queryParams: function(params) {
                    // params.quiz_id = state.quiz_id;
                    // params.class_room_id = state.class_room_id;
                    params.datetime = state.datetime;

                    return params;
                },
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            let str = '';
                            str += `<a type="button" id="remove_${index}" data-link="{{url('student-active/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'name_class_room',
                        title: 'Nama Kelas',
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
