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
                                                <input type="text" name="date_absen[]" class="form-control form-date-absen" placeholder="format: bulan/tanggal | 05/18">
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
                                        <label class="block"> Tanggal </label>
                                       <input type="date" name="date" id="date" class="form-control">
                                    </div>
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
                                            <th rowspan="2" data-width="2" data-formatter="actionFormatter">#</th>
                                            <th rowspan="2" data-width="20" data-field="name_student">Nama Peserta</th>
                                            <th colspan="4" data-align="center">Pertemuan</th>
                                            <th rowspan="2" data-width="30" data-formatter="valueFormatter">Nilai</th>
                                            <th rowspan="2" data-width="100" data-formatter="gradeFormatter">Grade</th>
                                            <th rowspan="2" data-width="250" data-formatter="noteFormatter">Catatan</th>
                                        </tr>
                                        <tr data="date_absen">
                                            <th data-width="30" data-align="center" data-formatter="absenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="absenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="absenFormatter">-</th>
                                            <th data-width="30" data-align="center" data-formatter="absenFormatter">-</th>
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
            dataQuizStudent: [
                {
                    absens: [
                        {id: 1}
                    ]
                }
            ],
            dataQuizDate: [],
            indexQuizDate: 0,
            quiz_id: '',
            class_room_id: '',
            dataAbsenType: [],
            dataAbsen: [],
        }

        var data = [];
        var form = $('#form');
        var btnSave = $('#btnSave');
        var nameClassRoom = $('#name_quiz');
        var firstStudent = $('#first_student');
        var multiStudent = $('.multi-student');
        var quizStudentTable = $('#quiz_student');

        var filterQuiz = $('#filter_quiz');
        var filterClass = $('#filter_class');

        $(document).ready(function() {
            readData();
            loadDataStudent();
            loadDataAbsenType();

            $('.select2').select2();
            // Using Locales
        });

        function actionFormatter(value, row, index) {
            var str = '';
            str += `<a type="button" id="edit_${index}" data-link="{{url('quiz-student/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
            // str += `<a type="button" id="print_${index}" data-link="{{url('quiz-student/print')}}/${row.id}" onclick="print('${index}')" class="btn btn-success  btn-xsm"><i class="fas fa-file"></i></a>`;

            return str;
        }

        function absenFormatter(value, row) {
            var idSelect = '';
            var selectedOption;
            var selectAbsenType = '';
            var optionAbsenType = '';
            var childOptionAbsenType = '';
            var quiz_student_id = row.id;
            var student_id = row.student_id;
            var date_absen_id = row.date_absen_id;

            optionAbsenType += '<option ></option>';
            $.each(state.dataAbsenType, function(index, item) {
                if(
                    row.absens[state.indexQuizDate]?.absen_type_id == item.id
                    && row.absens[state.indexQuizDate]?.student_id == student_id
                ) {
                    selectedOption = 'selected';
                }else {
                    selectedOption = '';
                }

                // console.log(row.absens[state.indexQuizDate]?.absen_type_id);

                optionAbsenType += '<option value="'+item.id+'" '+selectedOption+' > '+item.name_absen_type+' </option>';
            });

            childOptionAbsenType += `onchange="chooseAbsenType(this.value, '${quiz_student_id}', '${student_id}', '${state.indexQuizDate}')"`;
            idSelect = `'${student_id}'_'${state.indexQuizDate}'`;

            // console.log(state.dataAbsenType[state.indexQuizDate]);

            state.indexQuizDate = state.indexQuizDate == 3 ? 0 : state.indexQuizDate + 1;

            selectAbsenType +='<select id="'+idSelect+'" name="value_date_absen[]"  '+childOptionAbsenType+' class="form-control form-absen-type">';
                selectAbsenType += optionAbsenType;
            selectAbsenType +='</select>';

            return selectAbsenType;
        }

        function valueFormatter(value, row) {
            let str = '';
            let student_id = row.student_id;

            str += `<input class="form-control" id="value_${student_id}" onkeyup="keyupValue(this.value, '${student_id}')" />`;

            return str;
        }

        function gradeFormatter(value, row) {
            let str = '';
            let student_id = row.student_id;

            str += `<input class="form-control" id="grade_${student_id}" onkeyup="keyupGrade(this.value, '${student_id}')" />`;

            return str;
        }

        function noteFormatter(value, row) {
            let str = '';
            let student_id = row.student_id;

            str += `<textarea class="form-control" id="note_${student_id}" onkeyup="keyupNote(this.value, '${student_id}')" ></textarea>`;

            return str;
        }

        // ketika pilih jenis absen di basen. dan langsung update di state.dataAbsen
        function chooseAbsenType(value, quiz_student_id, student_id, indexQuizDate) {
            // console.log(value, quiz_student_id, student_id, state.dataQuizDate[indexQuizDate].id);

            let checkDataAbsen = state.dataAbsen
                                        .some(item => item.student_id == student_id
                                            && item.date_absen_id == state.dataQuizDate[indexQuizDate].id);

            if(checkDataAbsen) {
                state.dataAbsen
                    .map((item, index) => {
                        if(item.student_id == student_id && item.date_absen_id == state.dataQuizDate[indexQuizDate].id) {
                            state.dataAbsen[index] = {
                                ...item,
                                student_id: student_id,
                                quiz_student_id: quiz_student_id,
                                absen_type_id: value,
                                date_absen: state.dataQuizDate[indexQuizDate].date,
                                date_absen_id: state.dataQuizDate[indexQuizDate].id,
                            }
                        }
                    });
            }else {
                // console.log(state.dataQuizDate);

                state.dataAbsen = [
                    ...state.dataAbsen,
                    {
                        student_id: student_id,
                        quiz_student_id: quiz_student_id,
                        absen_type_id: value,
                        date_absen: state.dataQuizDate[indexQuizDate].date,
                        date_absen_id: state.dataQuizDate[indexQuizDate].id,
                    }
                ];
            }


            // console.table(state.dataAbsen);
        }

        // untuk resource tambah form student
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

        function keyupValue(value, student_id) {
            let dataQuizStudent = state.dataQuizStudent
                                        .find(item => item.student_id == student_id);

            dataQuizStudent.value = value;
        }

        function keyupGrade(value, student_id) {
            let dataQuizStudent = state.dataQuizStudent
                                        .find(item => item.student_id == student_id);

            dataQuizStudent.grade = value;
        }

        function keyupNote(value, student_id) {
            let dataQuizStudent = state.dataQuizStudent
                                        .find(item => item.student_id == student_id);

            dataQuizStudent.note = value;
        }

        function sendFilter() {
            state.quiz_id = filterQuiz.val();
            state.class_room_id = filterClass.val();
            // state.dataAbsen = [];

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
                    let listDateAbsen = '';
                    let checkDataAbsen = false;
                    let dataAbsenCompare = [];

                    state.dataQuizStudent = data.rows;
                    state.dataQuizDate = data.quizDate;

                    // console.log(data.rows);

                    // memunculkan tanggal kuis dan reset jika load ulang.
                    handleQuizDate(data, listDateAbsen);

                    data.rows.map(item => {
                        $('#value_'+item.student_id).val(item.value);
                        $('#grade_'+item.student_id).val(item.grade);
                        $('#note_'+item.student_id).val(item.note);
                    });

                    $.each(data.rows, function(index, item) {
                        // mengecek apakah data absen ada atau tidak
                        if(item.absens.length > 0) {
                            $.each(item.absens, function(indexAbsen, itemAbsen) {
                                state.dataAbsen = [
                                    ...state.dataAbsen,
                                    itemAbsen,
                                ];
                            });
                        }
                    });
                },
            });
        }

        function edit(index) {
            let dataQuizStudent = state.dataQuizStudent[index];
            let studentId = dataQuizStudent.student_id;
            let dataAbsen = state.dataAbsen.filter(item => item.student_id == studentId);

            $.ajax({
                url: "{{url('absen/ajaxSave')}}",
                data: JSON.stringify({
                    "dataAbsen": dataAbsen,
                    "dataQuizStudent": dataQuizStudent,
                    "quiz_id": state.quiz_id,
                    "class_room_id": state.class_room_id,
                    "student_id": studentId,
                }),
                dataType: "JSON",
                contentType: "application/json",
                type: "POST",
                success: function (result) {
                    // console.log(result);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: result.data,
                    });
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'warning',
                        title: "Maaf, gagal kirim data absen",
                    });
                }
            });
        }

        function handleQuizDate(data, listDateAbsen) {
            if(data.quizDate.length > 0) {
                quizStudentTable.find('thead > tr:nth-child(2)').empty();

                $.each(data.quizDate, function(index, item){
                    listDateAbsen += '<th data-width="100" data-align="center" style="text-align:center">'+moment(item.date).format("MM/DD")+'</th>';
                });

                quizStudentTable.find('thead > tr:nth-child(2)').prepend(listDateAbsen);
            }else {
                quizStudentTable.find('thead > tr:nth-child(2)').empty();

                for(let i = 0; i <4; i++) {
                    listDateAbsen += '<th data-width="100" data-align="center" style="text-align:center">-</th>';
                }

                quizStudentTable.find('thead > tr:nth-child(2)').prepend(listDateAbsen);
            }
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
