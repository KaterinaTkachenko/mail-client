<div class="mailsInFolder">
    @include('layouts.messages')
    @if (! empty($inbox))
        <div class="d-flex flex-wrap">
            <label class="searchL"><input type="text" id="search" placeholder="{{$activeFolder=='[Gmail]/Отправленные' ? 'in:to' : 'in:from'}}" name="search" class="search" value="{{isset($searchStr) ? $searchStr : ''}}"></label>
            <button class="searchBtn mainBtn" data-creteria="{{$activeFolder=='[Gmail]/Отправленные' ? 'to' : 'from'}}">Найти</button>
        </div>
        <table class="table table-striped">  
            <thead>
                @if($activeFolder != '[Gmail]/Вся почта') <th></th> @endif
                <th>Email</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Archivate</th>
            </thead>                       
            @foreach($inbox as $email)
                <?php
                    $overview = imap_fetch_overview($imap_conn, $email, 0);  
                    //dd($overview)                  ;
                    $date = date("d F, Y", strtotime($overview[0]->date));
                ?>
                <tr>
                    @if($activeFolder != '[Gmail]/Вся почта')
                        <td>
                            <input style="width: 40px; height: 20px;" class="checkit" name="checkit" type="checkbox" data-id={{$overview[0]->uid}}>
                        </td>
                    @endif
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        @if($activeFolder == '[Gmail]/Отправленные')
                            Кому: {{$overview[0]->to}}
                        @else
                            {{ $overview[0]->from }}
                        @endif
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        @if(isset($overview[0]->subject))
                            {{$overview[0]->subject}}
                        @endif                        
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">{{$date}}</td>
                    <td>
                        <input style="width: 40px; height: 20px;" class="archivate" name="archivate" type="checkbox" data-id={{$overview[0]->uid}} {{$overview[0]->flagged ? 'checked' : ''}}>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    <?php imap_close($imap_conn, CL_EXPUNGE);?>
</div>
