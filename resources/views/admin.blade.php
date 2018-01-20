@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Manage</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{--  Show all User  --}}
                    <h1>Manage User</h1>
                    <div class="row table" >
                        <div class="col-md-2"><strong>Name</strong></div>
                        <div class="col-md-2"><strong>E-mail</strong></div>
                        @foreach($roles as $role)
                            <div class="col-md-1"><strong>{{$role->name}}</strong></div>
                        @endforeach
                        <div class="col-md-1"></div>
                    </div>
                    @foreach($users as $user)
                    <div class="row table">
                        <form action="{{ url('/home/admin/assign_role') }}" method="POST">
                        {{ csrf_field() }}
                            <div class="col-md-2">{{$user->name}}</div>
                            <div class="col-md-2">{{$user->email}}</div>
                            @foreach($roles as $role)
                                <div class="col-md-1 text-center"><input type="checkbox" {{ $user->authorizeRole($role->name) ? 'checked' : '' }} name="role_{{$role->name}}"></div>
                            @endforeach
                            <input type="hidden" name="user_email" value="{{$user->email}}"/>
                            <div class="col-md-1"><button class="btn btn-default" type="submit">Assign</button></div>
                        </form>
                    </div>    
                    @endforeach
                    <hr>

                    {{--  Show all Role  --}}
                    <h1>Manage Role</h1>
                    <div class="row table" >
                        <div class="col-md-2"><strong>Name</strong></div>
                        <div class="col-md-2"><strong>Slug</strong></div>
                        <div class="col-md-2"><strong>Description</strong></div>
                        @foreach($permissions as $permission)
                            <div class="col-md-1"><strong>{{$permission->name}}</strong></div>
                        @endforeach
                        <div class="col-md-1"></div>
                    </div>
                    @foreach($roles as $role)
                    <div class="row table">
                        <form action="{{ url('/home/admin/assign_permission') }}" method="POST">
                        {{ csrf_field() }}
                            <div class="col-md-2">{{$role->name}}</div>
                            <div class="col-md-2">{{$role->slug}}</div>
                            <div class="col-md-2">{{$role->description}}</div>
                            @foreach($permissions as $permission)
                                <div class="col-md-1 text-center"><input type="checkbox" {{ $role->authorizePermission($permission->name)  ? 'checked' : '' }} name="permission_{{$permission->name}}"></div>
                            @endforeach
                            <input type="hidden" name="role_name" value="{{$role->name}}"/>
                            <div class="col-md-1"><button class="btn btn-default" type="submit">Assign</button></div>
                        </form>
                    </div>    
                    @endforeach
                    <hr>
                    
                    {{--  Adding new Role  --}}
                    <h1>Add New Role</h1>
                    <form action="{{ url('/home/admin/add_role') }}" method="POST">
                        {{ csrf_field() }}    
                        <div class="form-group">
                            <label for="">New Role Name</label>
                            <input class="form-control" type="text" name="name">
                            <label for="">New Role Slug</label>
                            <input class="form-control" type="text" name="slug">
                            <label for="">New Role Description</label>
                            <input class="form-control" type="text" name="description">
                            <button class="btn btn-default" type="submit">Add Role</button>
                        </div>
                    </form>

                    {{--  Adding new Permission  --}}
                    <h1>Add New Permission</h1>
                    <form action="{{ url('/home/admin/add_permission') }}" method="POST">
                        {{ csrf_field() }}    
                        <div class="form-group">
                            <label for="">New Permission Name</label>
                            <input class="form-control" type="text" name="name">
                            <label for="">New Permission Slug</label>
                            <input class="form-control" type="text" name="slug">
                            <label for="">New Permission Description</label>
                            <input class="form-control" type="text" name="description">
                            <button class="btn btn-default" type="submit">Add Permission</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection