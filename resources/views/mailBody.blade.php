@if(isset($inbox[0]->from))
    @if(isset($inbox[0]->subject)) 
        <div>Тема: {{$inbox[0]->subject}}</div>
    @endif
    <div>От: {{$inbox[0]->from}}</div>
    <div>Кому: {{$inbox[0]->to}}</div>
    @if(isset($message))  
        <div>Тело письма: </div>  
        <div>{!! $message !!}</div>
    @endif
@endif