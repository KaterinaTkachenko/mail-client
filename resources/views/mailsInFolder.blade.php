<div class="mailsInFolder">
    @include('layouts.messages')
    @if (! empty($inbox))
        <div class="d-flex">
            <label class="searchL"><input type="text" placeholder="{{$activeFolder=='INBOX' ? 'in:from' : 'in:to'}}" name="search" class="search"></label>
            <button class="searchBtn mainBtn" data-creteria="{{$activeFolder=='INBOX' ? 'from' : 'to'}}">Найти</button>
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
                        <?php 
                            if ($activeFolder == '[Gmail]/Отправленные') echo 'Кому: '.$overview[0]->to;
                            else echo $overview[0]->from;; 
                        ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        <?php if(isset($overview[0]->subject)) echo $overview[0]->subject; ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}"><?php echo $date; ?></td>
                    <td>
                        <input style="width: 40px; height: 20px;" class="archivate" name="archivate" type="checkbox" data-id={{$overview[0]->uid}} {{$overview[0]->flagged ? 'checked' : ''}}>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    <?php imap_close($imap_conn, CL_EXPUNGE);?>
</div>
