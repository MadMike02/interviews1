<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Form</title>
    
    <!-- bootstrap style sheet link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="container pt-5" style="max-width:70%;margin:0 auto;">
        <form action="/"    
            method="post" 
            enctype="multipart/form-data">
           @csrf
            <div class="row">
                <div class="col-md-6">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Enter Your Name" value="@if(isset($edit)) {{$edit['name']}}@endif"/> 
                </div>
                <div class="col-md-6">
                    <label>Password</label>
                    <input class="form-control" type="text" name="pass" placeholder="Enter Your Password" value="@if(isset($edit)){{$edit['pass']}}@endif"/>
                </div>
                <div class="col-12"></div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input class="form-control" type="email" name="mail" placeholder="Enter Your EmailId" value="@if(isset($edit)){{$edit['mail']}}@endif"/> 
                </div>
                <div class="col-md-6">
                    @if(isset($edit) && isset($edit['img']))
                        <br>
                        <span><b>Previous Image : </b> </span>
                        <img src="{{asset('').$edit['img']}}" style="width:50px;height:50px;" />
                    @else 
                    <label>Image</label>
                    @endif
                    <input class="form-control" type="file" accept="image/*" name="img"/>
                </div>
                <div class="col-12"></div>
                <div class="col-md-6">
                    <label>Mobile</label>
                    <input class="form-control" type="number" min=0 name="number" minlength=10 placeholder="Enter Your Number" value="@if(isset($edit)){{$edit['number']}}@endif"/> 
                </div>
                <div class="col-md-6">
                    <label>Date</label>
                    <input class="form-control" type="date" name="date" value="@if(isset($edit)){{$edit['date']}}@endif"/>
                </div>
                <div class="col-12">
                @if(isset($edit))
                <input type="hidden" name="uId" value="{{$edit['uId']}}"/>
            
                @endif
                </div>
                <div class="col-md-6">
                    <label>Role</label>
                    <select class="form-control" name="role">
                        <option value="">--Select Role--</option>
                        <option value="1" @if(isset($edit) && $edit['role'] == 1){{"selected=selected"}}@endif>Admin</option>
                        <option value="2" @if(isset($edit) && $edit['role'] == 2){{"selected=selected"}}@endif>User</option>
                    </select>
                </div>
                
                @if(isset($edit))
                
                    <input type="submit" class="btn btn-info mt-4" name="update" value="UPDATE INFO"/>
                    <a href="/" class="btn btn-danger mt-4">CANCLE EDIT</a>
                @else
                    <input type="submit" class="btn btn-primary mt-4" value="SAVE INFO"/>
                
                @endif
            </div>
        </form>

        @if(session('PROFILES'))
        <div class='col-12 mt-3'>
            <span class='h3'>USERS</span>
        </div>
        <table class="table table-stripped table-hover mt-2">
            <thead>
                <th>S.NO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Password</th>
                <th>Image</th>
                <th>Date</th>
                <th>Action</th>
            </thead>
            <tbody>
                @php $i=1; @endphp
                @foreach(session('PROFILES') as $data)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$data['name']?$data['name'] : 'NIL'}}</td>
                        <td>{{$data['mail']?$data['mail'] : 'NIL'}}</td>
                        <td>{{$data['number'] ?$data['number'] :'NIL'}}</td>
                        <td>
                            @if($data['role']==1)
                                ADMIN
                            @elseif($data['role']==2)
                                USER
                            @else
                                NIL
                            @endif
                        </td>
                        <td>{{$data['pass']?$data['pass'] :'NIL'}}</td>
                        <td>
                            @if(isset($data["img"]))
                            <img src="{{asset('').$data['img']}}" style="width:80px;height:80px;" />
                            @else
                            NIL
                            @endif
                        </td>
                        <td>{{$data['date']?$data['date']:'NIL'}}</td>
                        <td>
                            <a href="/update/{{$data['uId']}}" class='btn btn-info'>EDIT</a>
                            <a href="/delete/{{$data['uId']}}" class='btn btn-danger'>DELETE</a>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="/submitAll" class="btn btn-success mt-2">FINAL SUBMIT</a>
        @endif
        @if(session('mess'))
        <h4 class='text-success mt-2'>{{session('mess')}}</h4>
        @endif
    </div>
</body>
</html>