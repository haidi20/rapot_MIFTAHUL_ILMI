@extends('layouts.appLayout')

@section('style')
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset('adminty/files/bower_components/select2/css/select2.min.css')}}"/>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/pages/advance-elements/css/bootstrap-datetimepicker.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('adminty/files/assets/icon/font-awesome/css/font-awesome.min.css')}}"> --}}
    <style>
        .bootstrap-table .fixed-table-container .table thead th {
            vertical-align: middle;
        }
        .custom-form {
            margin-top: 50px;
        }
        .form-select-student {
            width: 85%;
            display: inline-block;
        }
        .btn-delete-student {
            float: right;
            cursor: pointer;
        }
        .btn-filter {
            margin-top: 30px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0px;
            padding-left: 10px;
            color: white;
            /* height: calc(2.25rem + 2px); */
        }

        .form-absen-type {
            padding: 0px;
        }

        #quiz_student > thead > tr:nth-child(1) > th:nth-child(1) > div.th-inner {
            width: 15px;
        }
        /* .form-date-absen {
            width: 100px;
        } */


    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12 col-md-3">
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
                                            <select name="class_room_id" id="class_room_id" class="form-control select2 form-control-inverse">
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
                                            <label class="block"> Kuis </label>
                                            <select name="quiz_id" id="quiz_id" class="form-control select2 form-control-inverse">
                                                <option value="">Pilih Kuis</option>
                                                @foreach ($quiz as $index => $item )
                                                    <option
                                                        value="{{$item->id}}">
                                                        {{$item->name_quiz}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="form-group row">
                                            <div class="col-sm-12 col-md-12">
                                                <label class="block"> Pertemuan {{$i + 1}} </label>
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen" placeholder="contoh: 6/3">
                                            </div>
                                        </div>
                                    @endfor
                                    {{-- <div class="multi-student"></div> --}}
                                    <div class="form-group row multi-student">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Peserta </label>
                                            <select name="students[]" id="first_student" class=" form-control select2 form-student">
                                                <option value="">Pilih Peserta</option>
                                                @foreach ($student as $index => $item )
                                                    <option
                                                        value="{{$item->id}}">
                                                        {{$item->name_student}}
                                                    </option>
                                                @endforeach
                                            </select>
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
            <div class="col-sm-12 col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        {!! session()->get('message') !!}
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
                                        <select name="filter_quiz_id" id="filter_quiz" class="form-control form-control-inverse">
                                            <option value="">Pilih Kuis</option>
                                            @foreach ($quiz as $index => $item )
                                                <option
                                                    value="{{$item->id}}">
                                                    {{$item->name_quiz}}
                                                </option>
                                            @endforeach
                                        </select>
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
                                <h5> Pengisian Raport </h5>

                                <table id="quiz_student">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" data-width="15" data-formatter="actionFormatter">#</th>
                                            <th rowspan="2" data-width="50" data-field="name_student">Nama Peserta</th>
                                            <th colspan="4" data-align="center">Pertemuan</th>
                                            <th rowspan="2" data-width="30" data-field="value">Nilai</th>
                                            <th rowspan="2" data-width="30" data-field="grade">Grade</th>
                                            <th rowspan="2" data-width="30" data-field="note">Catatan</th>
                                        </tr>
                                        <tr data="date_absen">
                                            <th data-width="30" data-align="center" data-formatter="valueAbsenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="valueAbsenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="valueAbsenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="valueAbsenFormatter">-</th>
                                        </tr>
                                    </thead>
                                </table>
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
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript" src="{{asset('adminty/files/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        var state = {
            countFormStudent: 1,
            dataStudent: [],
            dataQuizStudent: [{absens: [{id: 1}]}],
            dataQuizDate: [],
            indexQuizDate: 0,
            quiz_id: '',
            class_room_id: '',
            date_absen: [],
            dataAbsenType: [],
        }

        var data = [];
        var quizStudentTable = $('#quiz_student');
        var form = $('#form');
        var btnSave = $('#btnSave');
        var nameClassRoom = $('#name_quiz');
        var firstStudent = $('#first_student');
        var multiStudent = $('.multi-student');

        var filterQuiz = $('#filter_quiz');
        var filterClass = $('#filter_class');

        $(document).ready(function() {
            readData();
            loadDataStudent();
            loadDataAbsenType();

            $('.select2').select2();
            // Using Locales

        });

        function sendFilter() {
            state.quiz_id = filterQuiz.val();
            state.class_room_id = filterClass.val();

            quizStudentTable.bootstrapTable('refresh');
        }

        function readData() {
            quizStudentTable.bootstrapTable({
                url: "{{url('/quiz-student/ajaxRead')}}",
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
                    params.class_room_id = state.class_room_id;

                    return params;
                },
                onLoadSuccess: function(data) {
                    console.log('success');
                    var listDateAbsen = '';

                    state.dataQuizStudent = data.rows;
                    state.dataQuizDate = data.quizDate;

                    if(data.quizDate.length > 0) {
                        quizStudentTable.find('thead > tr:nth-child(2)').empty();

                        $.each(data.quizDate, function(index, item){
                            listDateAbsen += '<th data-width="100" data-align="center" style="text-align:center">'+moment(item.date).format("MM/DD")+'</th>';
                        });

                        quizStudentTable.find('thead > tr:nth-child(2)').prepend(listDateAbsen);
                    }
                },
            });
        }

        function actionFormatter(value, row, index) {
            var str = '';
            str += `<a type="button" id="edit_${index}" data-link="{{url('quiz-student/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
            // str += `<a type="button" id="remove_${index}" data-link="{{url('quiz-student/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

            return str;
        }

        function valueAbsenFormatter(value, row, index) {
            var selectAbsenType = '';
            var optionAbsenType = '';
            var childOptionAbsenType = '';
            var quiz_student_id = row.id;
            var student_id = row.student_id;

            optionAbsenType += '<option ></option>'
            $.each(state.dataAbsenType, function(index, item) {
                optionAbsenType += '<option value="'+item.id+'"> '+item.name_absen_type+' </option>';
            });


            childOptionAbsenType += `onchange="chooseAbsenType(this.value, '${quiz_student_id}', '${student_id}', '${state.indexQuizDate}')"`;

            state.indexQuizDate = state.indexQuizDate == 3 ? 0 : state.indexQuizDate + 1;

            selectAbsenType +='<select name="value_date_absen[]"  '+childOptionAbsenType+' class="form-control form-absen-type">';
                selectAbsenType += optionAbsenType;
            selectAbsenType +='</select>';

            // console.log(state.indexQuizDate);

            return selectAbsenType;
        }

        function chooseAbsenType(value, quiz_student_id, student_id, indexQuizDate) {
            console.log(value, quiz_student_id, student_id);

            state.dataQuizStudent
                .filter(item => item.student_id == student_id)
                .reduce((filtered, item) => {
                    console.log(item);
                });

            // let dataQuizStudent = state.dataQuizStudent[indexQuizDate];
            // let checkDataAbsen = dataQuizStudent.absens?.some(item => item.student_id == student_id);

            // if(!checkDataAbsen) {
            //     console.log("null");
            //     dataQuizStudent.absens.push({
            //             student_id: student_id,
            //             quiz_student_id: quiz_student_id,
            //             absen_type_id: value,
            //             date_absen: state.dataQuizDate[indexQuizDate].date,
            //         });
            // }
            // else {

            // }

            // .push({
            //             student_id: student_id,
            //             quiz_student_id: quiz_student_id,
            //             absen_type_id: value,
            //             date_absen: state.dataQuizDate[indexQuizDate].date,
            //         });

            // console.log(state.dataQuizStudent[indexQuizDate]);

            // var findIndex = state.dataQuizStudent.findIndex(item => item.student_id == student_id);
            // var findDataQuizStudent = state.dataQuizStudent[findIndex];
            // var findDataQuizDate = state.dataQuizDate[indexQuizDate];
            // var findIndexAbsen = findDataQuizStudent.absens.findIndex(item =>
            //                                                         item.student_id == student_id &&
            //                                                         item.date_absen == moment(findDataQuizDate.date).format('MM/DD')
            //                                                     );

            // if(findIndexAbsen < 0) {
            //     findDataQuizStudent.absens = [
            //         ...findDataQuizStudent.absens,
            //         {
            //             student_id: student_id,
            //             quiz_student_id: quiz_student_id,
            //             absen_type_id: value,
            //             date_absen: findDataQuizDate.date,
            //         },
            //     ];
            // }else {
            //     findDataQuizStudent.absens[findIndexAbsen] = {
            //         ...findDataQuizStudent.absens[findIndexAbsen],
            //         student_id: student_id,
            //         quiz_student_id: quiz_student_id,
            //         absen_type_id: value,
            //         date_absen: moment(findDataQuizDate.date).format('MM/DD'),
            //     }
            // }
        }

        function edit(index) {
            $.ajax({
                url: "{{url('absen/ajaxSave')}}",
                data: JSON.stringify(state.dataQuizStudent[index]),
                dataType: "JSON",
                contentType: "application/json",
                type: "POST",
                success: function (result) {
                    console.log(result);
                },
                error: function (error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Error...',
                        html: "Maaf, gagal kirim data absen",
                    });

                    console.log(error);
                }
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

        function loadDataAbsenType() {
            $.ajax({
                url: "{{url('master/absen-type/ajaxReadTypeahead')}}",
                dataType: "JSON",
                contentType: "application/json",
                type: "GET",
                success: function (result) {
                    if (result.data) {
                        state.dataAbsenType = result.data;
                    }
                },
                error: function (error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Error...',
                        html: "Maaf, gagal mendapatkan data jenis absen",
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
                    newFormStudent +='<label class="block"> Pilih Peserta </label> <i onclick="removeFormStudent('+countFormStudent+')" class="fas fa-close text-danger btn-delete-student"></i>';
                    newFormStudent +='<select name="students[]" class="form-control select2 form-control-inverse">';
                        newFormStudent += optionNewFormStudent;
                    newFormStudent +='</select>';
                    // newFormStudent +='<button type="submit" class="btn btn-sm btn-danger btn-delete-student" onclick="removeFormStudent('+countFormStudent+')">';
                    //     newFormStudent += '<i class="fas fa-close"></i>';
                    // newFormStudent +='</button>';
                newFormStudent +='</div>';
            newFormStudent +='</div>';

            multiStudent.after(newFormStudent);

            $('.select2').select2();
        }

        function removeFormStudent(index) {
            var countFormStudent = state.countFormStudent - 1;
            state.countFormStudent = countFormStudent;

            $('#form_student_'+index).remove();
        }

        // function remove(index) {
        //     data = quizStudentTable.bootstrapTable('getData');

        //     Swal.fire({
        //         title: 'Yakin hapus peserta <b>'+data[index].name_student+'</b> di kelas <b>'+data[index].name_class_room+'</b> dan <b>'+data[index].name_quiz+'</b> ?',
        //         showDenyButton: false,
        //         icon: 'question',
        //         showCancelButton: true,
        //         confirmButtonText: `Delete`,
        //         confirmButtonColor: 'red',
        //     }).then((result) => {
        //         /* Read more about isConfirmed, isDenied below */
        //         if (result.isConfirmed) {
        //             location.href = `{{url('quiz-student/delete')}}/${data[index].id}`;
        //             // Swal.fire('Data terhapus!', '', 'success')
        //         } else if (result.isDenied) {
        //             Swal.fire('Data tidak dihapus', '', 'info');
        //         }
        //     });
        // }
    </script>
@endsection
