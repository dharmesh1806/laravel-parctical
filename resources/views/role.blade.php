@extends('layouts.app')
@section('content')
@include('layouts.header')
<div class="container mt-5">
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="">Manage Role </h4>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-primary" data-toggle="modal" data-target="#roleAddModel">Add</button>
        </div>
        <div class="col-12">
            <table id="roleTable" class="display table">
                <thead>
                    <tr>
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


<div class="modal fade" id="roleAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Role</h5>
                <button type="button" class="close" ng-click="selectData(0)" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <label>Role Name</label>
                    <input type="text" class="form-control" placeholder="Enter role name" ng-model="roleName" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" ng-click="selectData(0)">Close</button>
                <button type="button" class="btn btn-primary" ng-click="addRole()">Save</button>
            </div>
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
                    "url": 'role-list',
                    "type": "GET",
                    headers: {
                        "Authorization": localStorage.getItem('accessToken') || ""
                    },
                    "data": function(d) {},
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
                        "targets": 1,
                        "data": 'role',
                        "defaultContent": null
                    },
                    {
                        "targets": 1,
                        "data": null,
                        "defaultContent": null,
                        render: (data, type, row, meta) => {
                            let btn = '<button class="btn btn-danger" ng-click="deleteRole(' + row.id + ')" >Delete</button>'
                            btn += '<button class="btn btn-success mx-2" ng-click="selectData(1,' + row.id + ')">Edit</button>'
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
                    params.append('roleId', id);
                    let res = await fetch('/delete-role', {
                        method: 'DELETE',
                        headers: {
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

        $scope.roleName = ""
        $scope.id = ""

        $scope.selectData = (flag = 0, id) => {
            $('#roleAddModel').modal('toggle');
            if (flag == 1) {
                let index = $scope.list.findIndex((d) => d.id == id)
                $scope.roleName = $scope.list[index].role
                $scope.id = $scope.list[index].id
            } else {
                $scope.roleName = ""
                $scope.id = ""
            }
        }

        $scope.addRole = async (id = "") => {
            if (!$scope.roleName) {
                toastr.error("Enter role")
                return
            }
            let params = new URLSearchParams();
            params.append('role', $scope.roleName);
            if ($scope.id) {
                params.append('id', $scope.id);
            }
            let res = await fetch($scope.id ? 'edit-role' : '/add-role', {
                method: $scope.id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    "Authorization": localStorage.getItem('accessToken') || ""
                },
                body: params.toString()
            })
            let response = await res.json()
            if (res.status == 200) {
                toastr.success(response.message)
                $scope.table.draw();
                $scope.selectData(0)
            } else {
                toastr.error(response.message);
            }
        }
    });
</script>
@endsection