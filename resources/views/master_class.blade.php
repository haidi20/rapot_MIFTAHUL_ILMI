@extends('layouts.appLayout')

@section('content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                {!! session()->get('message') !!}
                <div class="card">
                    <div class="card-body">
                        <h5> Kelas </h5>

                        <table id="br_users"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- @section('script')
    <script>
        $(document).ready(function() {
            readData();
        });

        function remove(pid_member, kode) {
            Swal.fire({
                title: 'Delete data '+kode+'?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: 'red',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.href = `{{url('user/delete')}}/${pid_member}`;
                    // Swal.fire('Data terhapus!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Data tidak dihapus', '', 'info');
                }
            });
        }

        function readData() {
            $('#br_users').bootstrapTable({
                url: "{{url('/user/ajaxRead')}}",
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
                buttonsAlign: "left",
                buttonsClass: "btn btn-success btn-sm",
                buttons: {
                    btnAdd: {
                        text: 'Add',
                        icon: 'fa-plus',
                        event: function () {
                            location.href = "{{url('/user/create')}}";
                        },
                        attributes: {
                            title: 'Adduser',
                        },
                    },
                },
                //idField: 'NRP',
                columns: [
                    {
                        width: 10,
                        title: 'Action',
                        formatter: function (value, row, index) {
                            var str = '';
                            str += `<a type="button" href="{{url('user/edit')}}/${row.pid_member}" class="btn btn-info btn-xsm"><i class="fas fa-pencil-alt"></i></i></a> &nbsp;`;
                            str += `<a type="button" href="#" onclick="remove('${row.pid_member}', '${row.username}')" class="btn btn-danger btn-xsm"><i class="fas fa-trash"></i></a>`;

                            return str;
                        },
                    },
                    {
                        field: 'username',
                        title: 'Username',
                    },
                    {
                        field: 'full_name',
                        title: 'Full Name',
                    },
                    {
                        // field: 'email',
                        title: 'Email',
                        formatter: function (value, row, index) {
                            var str = `${row.email}<span class="text-danger"> <i class="fas fa-close"></i> </span>`;

                            if(row.is_verified == 1) {
                                str = `${row.email}<span class="text-success"> <i class="fas fa-check"></i> </span>`;
                            }

                            return str;
                        }
                    },
                    {
                        field: 'phone_number',
                        title: 'Phone Number',
                    },
                    {
                        field: 'id_telegram',
                        title: 'ID Discord',
                    },
                    {
                        field: 'residency',
                        title: 'Residency',
                    },
                ]
            });
        }
    </script>
@endsection --}}
