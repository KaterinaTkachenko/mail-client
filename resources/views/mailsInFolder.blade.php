<div class="mailsInFolder">
    @include('layouts.messages')
    @if (! empty($inbox))
        <div>
            <label class="searchL"><input type="text" name="search" class="search"></label>
        </div>
        <table class="table table-striped">  
            <thead>
                <th></th>    
                <th>Email</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Archivate</th>
            </thead>          
            @foreach($inbox as $email)
                <?php
                    $overview = imap_fetch_overview($imap_conn, $email, 0);  
                    $date = date("d F, Y", strtotime($overview[0]->date));
                ?>
                <tr>
                    <td>
                        <input style="width: 40px; height: 20px;" class="checkit" name="checkit" type="checkbox" data-id={{$overview[0]->uid}}>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        <?php 
                            //if ($folder == 'INBOX') echo $overview[0]->from;
                            if ($folder == '[Gmail]/Отправленные') echo 'Кому: '.$overview[0]->to;
                            else echo $overview[0]->from;; 
                        ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        <?php if(isset($overview[0]->subject)) echo $overview[0]->subject; ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}"><?php echo $date; ?></td>
                    <td>
                        <input style="width: 40px; height: 20px;" class="archivate" name="archivate" type="checkbox" data-id={{$overview[0]->uid}} {{$overview[0]->flagged==1 ? 'checked' : ''}}>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</div>