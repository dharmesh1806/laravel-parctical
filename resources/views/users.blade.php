@extends('layouts.app')
@section('content')
@include('layouts.header')
<div class="container mt-5">
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="">Manage Users </h4>
        </div>
        <div class="col-12">
            <table id="roleTable" class="display table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Hobbies</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope, $compile) {
        $scope.list = []
        $scope.getList = async function() {
            $scope.table = $('#roleTable').DataTable({
                select: true,
                "processing": true,
                "serverSide": true,
                searching: false,
                "ajax": {
                    "url": 'user-list',
                    "type": "GET",
                    headers: {
                        "Authorization": localStorage.getItem('accessToken') || ""
                    },
                    "data": function(d) {
                        return []
                    },
                    "dataSrc": function(json) {
                        $scope.list = json.data;
                        return json.data;
                    }
                },
                "order": [
                    [0, "desc"]
                ],
                "sort": false,
                "search": false,
                "columns": [{
                        "targets": 0,
                        "data": 'firstName',
                        "defaultContent": null
                    },
                    {
                        "targets": 1,
                        "data": 'lastName',
                        "defaultContent": null
                    },
                    {
                        "targets": 2,
                        "data": 'email',
                        "defaultContent": null
                    },
                    {
                        "targets": 3,
                        "data": 'gender',
                        "defaultContent": null
                    },
                    {
                        "targets": 4,
                        "data": 'hobbies',
                        "defaultContent": null
                    },
                    {
                        "targets": 5,
                        "data": 'role',
                        "defaultContent": null,
                        render: (data, type, row, meta) => {
                            return data ? data : '-'
                        }
                    },
                    {
                        "targets": 6,
                        "data": null,
                        "defaultContent": null,
                        render: (data, type, row, meta) => {
                            let btn = '<button class="btn btn-danger" ng-click="deleteRole(' + row.id + ')" >Delete</button>'
                            return btn
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $compile(angular.element(row).contents())($scope);
                },
                "deferRender": true,
                "language": {
                    "emptyTable": "No Data",
                }
            });
        }

        $scope.getList()

        $scope.deleteRole = (id) => {
            console.log(id)
            new swal({
                title: "Are you sure ?",
                text: "You want to Delete",
                showCancelButton: true,
                confirmButtonText: `Yes`,
            }).then(async (data) => {
                if (data.isConfirmed) {
                    let params = new URLSearchParams();
                    params.append('id', id);
                    let res = await fetch('/delete-user', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/x-www-form-urlencoded',
                            "Authorization": localStorage.getItem('accessToken') || ""
                        },
                        body: params.toString()
                    })
                    let response = await res.json()
                    if (res.status == 200) {
                        toastr.success(response.message)
                        document.cookie = 'accessToken=' + response.token
                        $scope.table.draw();
                    } else {
                        toastr.error(response.message);
                    }
                }
            })

        }
    });
</script>
@endsection