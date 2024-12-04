@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-5 mx-auto">
            <h4 class="mt-5 mb-4">Login</h4>
            <div class="row">
                <div class="col-12">
                    <div>
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Email" ng-model="data.email" />
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="text" class="form-control" placeholder="Password" ng-model="data.password" />
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" ng-click="submitData()"><i ng-show="loader" class="fa fa-spinner fa-spin mx-1"></i>Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var app = angular.module('myApp', [])
    app.controller('myCtrl', function($scope, ) {
        $scope.data = {
            email: "",
            password: ""
        }
        $scope.loader = false
        $scope.submitData = async function name() {
            if (!$scope.loader) {
                // try {
                //     validateString($scope.data.email, 'Enter email')
                //     validateString($scope.data.password, 'Enter password')
                // } catch (e) {
                //     toastr.error(e)
                //     return
                // }
                let params = new URLSearchParams();
                params.append('email', $scope.data.email);
                params.append('password', $scope.data.password);
                $scope.loader = true
                let res = await fetch('/user-login', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params.toString()
                })
                $scope.loader = false
                let response = await res.json()
                if (res.status == 200) {
                    toastr.success(response.message)
                    localStorage.setItem('accessToken', response.token)
                    setTimeout(() => {
                        window.location.href = '/users';
                    }, 500)
                } else {
                    toastr.error(response.message);
                }
                $scope.$apply()
            }
        }
    });
</script>
@endsection