@extends('layouts.appLayout')

@section('style')
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
                        <h5> Cetak Rapot </h5>

                        <table id="report"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script type="text/javascript" src="{{asset('adminty/files/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script>
        var state = {
            class_room_id: '',
            datetime: moment().format('YYYY-MM'),
        }

        let report = $('#report');
        let filterClass = $('#filter_class');
        let filterDatetime = $('#filter_datetime');

        $(document).ready(function() {
            readData();

            filterDatetime.val(state.datetime);
        });

        function sendFilter() {
            state.class_room_id = filterClass.val();
            state.datetime = filterDatetime.val();
            // state.dataAbsen = [];

            report.bootstrapTable('refresh');
        }

        function readData() {
            report.bootstrapTable({
                url: "{{url('/report/ajaxRead')}}",
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
                queryParams: function(params) {
                    params.class_room_id = state.class_room_id;
                    params.datetime = state.datetime;

                    return params;
                },
                //idField: 'NRP',
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            var str = '';
                            str += `<a type="button" id="print_${index}" data-link="{{url('report/print')}}" data-studentId="${row.student_id}" data-classRoomId="${row.class_room_id}" onclick="handlePrint('${index}')" class="btn btn-success btn-xsm"><i class="fas fa-file"></i></a>`;

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

        function handlePrint(index) {
            let url = $('#print_'+index).attr('data-link');
            let student_id = $('#print_'+index).attr('data-studentId');
            let class_room_id = $('#print_'+index).attr('data-classRoomId');
            let date_time = $('#filter_datetime').val();

            window.open(`${url}?student_id=${student_id}&class_room_id=${class_room_id}&datetime=${date_time}`, '_blank');
        }
    </script>
@endsection
