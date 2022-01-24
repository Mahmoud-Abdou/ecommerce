
@extends('layouts.app')

@section('content')
<style>
.direct-chat-contacts {
  background: #3c7d9a;
  height: 100%;
}
.contacts-list-img {
  border-radius: 0%;
}
</style>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.chat')}} </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
         
          <li class="breadcrumb-item active">{{trans('lang.chat')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">

 
    <div class="card-body">
      <div class="clearfix"></div>
        <div class="row box box-success direct-chat direct-chat-success direct-chat-contacts-open">
            <div class="col-4">  
              <div class="direct-chat-contacts">
                <ul class="contacts-list" id="contacts-list">
                  @foreach ($chats as $user)
                    <li>
                        <a href="#" onclick="getmessages({{$user->id}}, '{{$user->name}}')">
                          <img class="contacts-list-img" src="{{$app_logo}}"  style="background-color: white;">
                          <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              {{$user->name}}
                              <small class="contacts-list-date pull-right"> {{$user->created_at}}</small>
                              <span class="contacts-list-msg">{{$user->mess}}</span>

                            </span>
                          </div>
                          <!-- /.contacts-list-info -->
                        </a>
                    </li> 
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-8">
                <div id="messages" class="list-group" style="padding-left: 19px;padding-right: 20px;height:500px; overflow-y:auto">
                   
                </div>
                <div id="sendmessages">
                   
                </div>

            </div>
        </div>
      <input type="hidden" id="lastmessage">
      <input type="hidden" id="idofclient" value="{{$chats[0]?$chats[0]->id:0}}">
      <input type="hidden" id="nameofclient" value="{{$chats[0]?$chats[0]->name:0}}">
    </div>
  </div>
</div>

  {{-- 
    <div class="box-footer" style="">
    <form action="#" method="post">
        <div class="input-group">
        <input type="text" name="message" placeholder="Type Message ..." class="form-control">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success btn-flat">Send</button>
            </span>
        </div>
    </form>
  </div> 
  --}}
<script>
  function getmessageschats(){
    $.ajax({
      url: "get-chat-list",
      success: function (html) {
        x=``;
        if(html.data.length > 0){
          html.data.forEach(user => {
            x+= `<li>
                      <a href="#" onclick="getmessages(${user.id}, '${user.name}')">
                        <img class="contacts-list-img" src="{{$app_logo}}"  style="background-color: white;">
                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            ${user.name}

                          </span>
                        </div>
                
                      </a>
                  </li> `
            ;
          });
          $('#contacts-list').html(x);
        }
      }, 
      error: function (error) {
          alert(error);
      }
    });
  }
  function getmessages(id=0, name="-"){

        if(id == 0){
          id=$('#idofclient').val();
          name=$('#nameofclient').val();
        }
       
        $('#idofclient').val(id);
        $('#nameofclient').val(name);
        $('#messages').html("");
        $.ajax({
            url: `{{url('get-message')}}?id=0&user_id=${id}`,
            success: function (html) {
                var x=``;
              
                html.data.old.forEach(element => {
                  
                  if($(`#message${element.id}`).length == 0){
                    if(element.to == 0){
                        x+=`
                         <div class="direct-chat-msg" id="message${element.id}">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">${name}</span>
                                <span class="direct-chat-timestamp pull-right">${element.created_at}</span>
                            </div>
                            <!-- /.direct-chat-info -->
                            <img class="direct-chat-img" src="{{$app_logo}}" alt="Message User Image"><!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                ${element.mess}
                            </div>
                            <!-- /.direct-chat-text -->
                          </div>

                        `;
                    } else{
                         x+=`
                            <div class="direct-chat-msg right" id="message${element.id}">
                              <div class="direct-chat-info clearfix">
                                  <span class="direct-chat-name pull-right">${name}</span>
                                  <span class="direct-chat-timestamp pull-left">${element.created_at}</span>
                              </div>
                              <!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{$app_logo}}" alt="Message User Image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text">
                               ${element.mess}
                              </div>
                              <!-- /.direct-chat-text -->
                            </div>
                        `;
                    }
                  }
                  $('#lastmessage').val(element.id);
                });

                $('#messages').html(x);
                $('#sendmessages').html(
                    `
                     <div class="box-footer" style="">
                     
                          <div class="input-group">
                          <input id="textmessage" type="text" name="message" placeholder="Type Message ..." class="form-control">
                              <span class="input-group-btn">
                                  <button type="submit" class="btn btn-success btn-flat" onclick="sendmessage(${id})">Send</button>
                              </span>
                          </div>
                   
                    </div> 
                     
                    `
                );
                $("#messages").animate({ scrollTop: $("#messages")[0].scrollHeight}, 10);
            }, 
            error: function (error) {
                alert(error);
            }
        });
    }
    function sendmessage(id){
        var mess = $("#textmessage").val();
        var today = new Date();
        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+' '+time;
        // $('#messages').append(
        //       `
        //          <div class="direct-chat-msg" >
        //             <div class="direct-chat-info clearfix">
        //                 <span class="direct-chat-name pull-left">Admin</span>
        //                 <span class="direct-chat-timestamp pull-right">${dateTime}</span>
        //             </div>
        //             <!-- /.direct-chat-info -->
        //             <img class="direct-chat-img" src="{{$app_logo}}" alt="Message User Image"><!-- /.direct-chat-img -->
        //             <div class="direct-chat-text">
        //                 ${mess}
        //             </div>
        //             <!-- /.direct-chat-text -->
        //           </div>
        //     `
        //   );
        $("#textmessage").val("");
        $.ajax({
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                mess: mess ,
                user_id:0,
                to:id,
                id: $('#lastmessage').val(),

            },
            url: "send-message",
            success: function (html) {
             
                 var x=``;
              
                html.data.old.forEach(element => {
                  if($(`#message${element.id}`).length == 0){
                    if(element.to == 0){
                        x+=`
                         <div class="direct-chat-msg" id="message${element.id}">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">${name}</span>
                                <span class="direct-chat-timestamp pull-right">${element.created_at}</span>
                            </div>
                            <!-- /.direct-chat-info -->
                            <img class="direct-chat-img" src="{{$app_logo}}" alt="Message User Image"><!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                ${element.mess}
                            </div>
                            <!-- /.direct-chat-text -->
                          </div>

                        `;
                    } else{
                         x+=`
                            <div class="direct-chat-msg right" id="message${element.id}">
                              <div class="direct-chat-info clearfix">
                                  <span class="direct-chat-name pull-right">${name}</span>
                                  <span class="direct-chat-timestamp pull-left">${element.created_at}</span>
                              </div>
                              <!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{$app_logo}}" alt="Message User Image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text">
                               ${element.mess}
                              </div>
                              <!-- /.direct-chat-text -->
                            </div>
                        `;
                    }
                  }
                     $('#lastmessage').val(element.id);
                });

                $('.lastmessagefromme').remove();
                $('#messages').append(x);
                $("#messages").animate({ scrollTop: $("#messages")[0].scrollHeight}, 10);

                
            }, 
            error: function (error) {
                alert(error);
            }
        });
    }
</script>
@endsection
