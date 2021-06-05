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
                        {{-- <h5>Masukkan Data Kelas</h5> --}}

                        <form action="{{ $action }}" method="post" id="form" >
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group row custom-form">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Kelas </label>
                                            <input class="form-control" type="text" name="name_class_room" id="name_class_room" value="{{old('name_class_room')}}" placeholder="contoh: senin pagi">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Nama Pemateri </label>
                                            <input class="form-control" type="text" name="name_speaker" id="name_speaker" value="{{old('name_speaker')}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12">
                                            <label class="block"> Penjelasan Kelas </label>
                                            <textarea
                                                class="form-control" name="description"
                                                id="description" cols="30" rows="10"
                                                placeholder="contoh : Absensi Kelas IQRO Senin, 12.30 - 14.00 wib/ 13.30 - 15.00 wita"
                                                >{{old('description')}}</textarea>
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
                        <h5> Data Kelas </h5>

                        <table id="class_room"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var data = [];
        var form = $('#form');
        var btnSave = $('#btnSave');
        var idClassRoom = $('#class_room');
        var description = $('#description');
        var nameSpeaker = $('#name_speaker');
        var nameClassRoom = $('#name_class_room');

        $(document).ready(function() {
            readData();
        });

        function edit(index) {
            data = idClassRoom.bootstrapTable('getData');
            form.attr('action', $('#edit_'+index).attr('data-link'));

            nameClassRoom.val(data[index].name_class_room);
            nameSpeaker.val(data[index].name_speaker);
            description.val(data[index].description);
        }

        function remove(index) {
            data = idClassRoom.bootstrapTable('getData');

            Swal.fire({
                title: 'Yakin hapus data kelas <b>'+data[index].name_class_room+'</b> ?',
                showDenyButton: false,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('master/class/delete')}}/${data[index].id}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function readData() {
            idClassRoom.bootstrapTable({
                url: "{{url('/master/class/ajaxRead')}}",
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
                            str += `<a type="button" id="edit_${index}" data-link="{{url('master/class/update')}}/${row.id}" onclick="edit('${index}')" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" id="remove_${index}" data-link="{{url('master/class/delete')}}/${row.id}" onclick="remove('${index}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'name_class_room',
                        title: 'Nama Kelas',
                    },
                    {
                        field: 'name_speaker',
                        title: 'Nama Pemateri',
                    },
                    {
                        field: 'description',
                        title: 'Penjelasan Kelas',
                    },
                ]
            });
        }
    </script>
@endsection
