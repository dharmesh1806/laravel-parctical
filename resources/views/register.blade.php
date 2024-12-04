@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h4 class="mt-5 mb-4">Register</h4>
    <div class="row">
        <div class="col-4">
            <div>
                <label>First Name</label>
                <input type="text" class="form-control" placeholder="First Name" ng-model="data.fname" />
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" class="form-control" placeholder="Last Name" ng-model="data.lname" />
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Email</label>
                <input type="text" class="form-control" placeholder="Email" ng-model="data.email" />
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" class="form-control" placeholder="Contact Number" ng-model="data.contactNumber" />
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Postal Code</label>
                <input type="text" class="form-control" placeholder="Postal Code" ng-model="data.postCode" />
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" placeholder="Password" ng-model="data.password" />
            </div>
        </div>
        <div class="col-3">
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" class="form-control" placeholder="Confirm Password" ng-model="data.confirmPassword" />
            </div>
        </div>
        <div class="col-3">
            <label>Select Role</label>
            <select class="form-control" ng-model="data.role">
                <option value="">Select Role</option>
                <option value="<%=r.id%>" ng-repeat="r in roleList"><%=r.role%></option>
            </select>
        </div>
        <div class="col-2">
            <div class="mb-3">
                <label>Gender</label>
                <br />
                <input type="radio" value="0" ng-model="data.gender" /> Male
                <input type="radio" value="1" ng-model="data.gender" /> Female
            </div>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Hobbies</label>
                <br />
                <span ng-repeat="h in hobbies">
                    <input type="checkbox" ng-model="hobbies[h]" ng-change="updateHobbies(h)" checked="hobbies.indexOf(h)>-1" /> <%=h%>
                </span>
            </div>
        </div>
        <div class=" col-4">
            <div class="mb-3">
                <label>Profile</label>
                <input type="file" class="form-control" multiple onchange="angular.element(this).scope().updateFiles(this)" />
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" ng-click="submitData()"><i ng-show="loader" class="fa fa-spinner fa-spin mx-1"></i>Submit</button>
        </div>
    </div>
</div>


<script>
    var app = angular.module('myApp', []).config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%=');
        $interpolateProvider.endSymbol('%>');
    });
    app.controller('myCtrl', function($scope) {
        $scope.hobbies = ['Cricket', 'Trawel', 'Play Games', 'Photography'];
        $scope.hb = []
        $scope.file = []
        $scope.roleList = []
        $scope.loader = false
        $scope.data = {
            fname: "",
            lname: "",
            email: "",
            contactNumber: "",
            postCode: "",
            password: "",
            confirmPassword: "",
            role: "",
            gender: "",
        }

        $scope.updateHobbies = function(hobby) {
            if ($scope.hb.indexOf(hobby) > -1) {
                $scope.hb.splice($scope.hb.indexOf(hobby), 1);
            } else {
                $scope.hb.push(hobby)
            }
        }

        $scope.updateFiles = function(element) {
            $scope.file = element.files
        }

        $scope.submitData = async function() {
            if (!$scope.loader) {
                try {
                    validateString($scope.data.fname, 'Enter first name')
                    validateString($scope.data.lname, 'Enter last name')
                    validateString($scope.data.email, 'Enter email')
                    validateEmail($scope.data.email, 'Enter valid email')
                    validateString($scope.data.contactNumber, 'Enter contact number')
                    validatePhoneNumber($scope.data.contactNumber, 'Enter valid contact number')
                    validateString($scope.data.postCode, 'Enter post code')
                    validatePostCode($scope.data.postCode, 'Enter valid post code')
                    validateString($scope.data.password, 'Enter password')
                    validateLenght($scope.data.password, 6, 'Password length should be at least 6')
                    validateString($scope.data.confirmPassword, 'Enter confirm password')
                    if ($scope.data.password != $scope.data.confirmPassword) {
                        throw 'Password and confirm password must be same'
                    }
                    validateString($scope.data.gender, 'Select gender')
                    validateString($scope.hb.join(','), 'Select atleast 1 hobby')
                    validateFile($scope.file, 4, 'Select profile')
                } catch (e) {
                    toastr.error(e)
                    return
                }
                let formData = new FormData()
                formData.append("fname", $scope.data.fname)
                formData.append("lname", $scope.data.lname)
                formData.append("email", $scope.data.email)
                formData.append("contactNumber", $scope.data.contactNumber)
                formData.append("postCode", $scope.data.postCode)
                formData.append("role", $scope.data.role)
                formData.append("gender", $scope.data.gender)
                formData.append("password", $scope.data.password)
                formData.append("hb", $scope.hb)
                if ($scope.file.length) {
                    for (let i of $scope.file) {
                        formData.append("files[]", i);
                    }
                }
                $scope.loader = true
                let res = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: formData
                })
                $scope.loader = false
                let response = await res.json()
                if (res.status == 200) {
                    toastr.success(response.message)
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 500)
                } else {
                    toastr.error(response.message);
                }
            }
        }

        $scope.getRoleList = async function() {
            let res = await fetch('/roles', {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
            let response = await res.json()
            if (res.status == 200) {
                $scope.roleList = response.data
                $scope.$apply()
            } else {
                toastr.error(response.message);
            }
        }
        $scope.getRoleList()
    });
</script>
@endsection